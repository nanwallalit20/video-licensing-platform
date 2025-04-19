<?php

namespace App\Http\Controllers;

use App\Enums\MediaTypes;
use App\Enums\Rating;
use App\Enums\RevenuePlanStatus;
use App\Enums\Roles;
use App\Enums\TitleCompletion;
use App\Enums\TitleContact;
use App\Enums\TitleFestival;
use App\Enums\TitleStatus;
use App\Enums\TitleType;
use App\Helpers\FileUploadHelper;
use App\Helpers\Helpers;
use App\Helpers\Docusign\DocusignHelper;
use App\Http\Requests\SeasonRequest;
use App\Http\Requests\TitleDocumentRequest;
use App\Http\Requests\RevenuePlanRequest;
use App\Http\Requests\TitleContactRequest;
use App\Http\Requests\TitleProfileRequest;
use App\Http\Requests\TitleConversationRequest;
use App\Http\Requests\TitleVideoRequest;
use App\Mail\AdminNotificationToSeller;
use App\Mail\DocusignMail;
use App\Models\Actor;
use App\Models\Advisory;
use App\Models\Cast;
use App\Models\Composers;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Director;
use App\Models\Episode;
use App\Models\Festival;
use App\Models\Keywords;
use App\Models\MapContactTitle;
use App\Models\MapEpisodeMedia;
use App\Models\MapTitleMedia;
use App\Models\Media;
use App\Models\OfficialRating;
use App\Models\OttPlatforms;
use App\Models\Producer;
use App\Models\RevenuePlan;
use App\Models\Season;
use App\Models\SeasonMedia;
use App\Models\Tag;
use App\Models\Title;
use App\Models\TitleConversation;
use App\Models\Writer;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TitleController extends Controller {


    public function index(Request $request) {
        return match (auth()->user()->getUserProfile->role_id->value) {
            Roles::Superadmin->value => $this->superAdminTitleIndex($request),
            Roles::Seller->value => $this->sellerTitleIndex($request),
            Roles::Buyer->value => redirect()->route('dashboard'),
            default => abort(404)
        };
    }

    public function superAdminTitleIndex($request) {
        $query = Title::where('isSubmitted', TitleCompletion::Completed->value);
        if($request->filled('seller_id')){
            $query->where('user_id', $request->seller_id);
        }

        if ($request->filled('title_name')) {
            $query->where('name', 'LIKE', '%' . $request->title_name . '%');
        }

        if ($request->filled('title_uuid')) {
            $query->where('uuid', $request->title_uuid);
        }

        // Filter by Title Type (assuming a relation 'type' exists)
        if ($request->filled('title_type')) {
            $query->where('type', $request->title_type);
        }

        // Filter by Seller Name
        if ($request->filled('seller_name')) {
            $query->whereHas('getUser', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->seller_name . '%');
            });
        }

        // Filter by Seller Email
        if ($request->filled('seller_email')) {
            $query->whereHas('getUser', function ($q) use ($request) {
                $q->where('email', 'LIKE', '%' . $request->seller_email . '%');
            });
        }

        // Filter by Approval Status
        if ($request->filled('approval_status')) {
            $query->where('status', $request->approval_status);
        }

        $titles = $query->orderBy('id', 'desc')->paginate();
        if($request->filled('isAjax')){
            return view('backend.Seller.Component.title-list',['titles'=>$titles])->render();
        }
        return view('backend.titles.list', compact('titles'));
    }

    public function sellerTitleIndex($request) {
        $query = Title::query()->where('user_id', auth()->id());

        // Filter by Name (partial match)
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter by Type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by Admin Approve Status
        if ($request->filled('status')) {
            // If you use "pending" as a status in your view but it isn’t stored in the database, adjust accordingly.
            $query->where('status', $request->status);
        }

        // Filter by Compilation Status (assuming you have a field for this)
        if ($request->filled('isSubmitted')) {
            $query->where('isSubmitted', $request->isSubmitted);
        }

        // Optionally, use pagination for better usability
        $titles = $query->orderBy('id', 'desc')->paginate();

        return view('seller.videos.list', compact('titles'));
    }

    public function create($type): RedirectResponse {
        $title = Title::create([
            'user_id' => auth()->id(),
            'uuid' => Str::uuid(),
            'slug' => Helpers::createUniqueSlug(Title::class, 'New Title'),
            'status' => TitleStatus::Pending,
            'reason' => null,
            'type' => $type
        ]);
        return redirect()->route('titleEdit', ['uuid' => $title->uuid]);
    }

    public function edit($uuid): Renderable {

        $title = Title::where(['uuid' => $uuid])->first();
        $id = $title->id;
        if ($title->getRevenuePlan && $title->getRevenuePlan->status->value == RevenuePlanStatus::Final->value) {
            abort(403);
        }

        $ratings = Rating::cases();
        $countries = Country::orderBy('country_code')->groupBy('country_code')->select('country_code')->get();
        // Fetch primary contact
        $primaryContact = MapContactTitle::where('title_id', $id)
            ->whereHas('getContact', function ($query) {
                $query->where('type', TitleContact::Primary->value); // Type 0 for primary contact
            })
            ->with('getContact')
            ->first();

        // Fetch secondary contact
        $secondaryContact = MapContactTitle::where('title_id', $id)
            ->whereHas('getContact', function ($query) {
                $query->where('type', TitleContact::Secondary->value); // Type 1 for secondary contact
            })
            ->with('getContact')
            ->first();

        $seasons = $movies = [];
        if ($title->type->value == TitleType::Movie->value) {

            $poster = $title->getTitleMediaMapping()->whereHas('getMedia', function ($query) {
                return $query->where('file_type', MediaTypes::Image->value);
            })->first();

            $poster_landscape = $title->getTitleMediaMapping()->whereHas('getMedia', function ($query) {
                return $query->where('file_type', MediaTypes::Image_Landscape->value);
            })->first();

            $mainVideo = $title->getTitleMediaMapping()->whereHas('getMedia', function ($query) {
                return $query->where('file_type', MediaTypes::MainVideo->value);
            })->first();

            $trailer = $title->getTitleMediaMapping()->whereHas('getMedia', function ($query) {
                return $query->where('file_type', MediaTypes::Trailer->value);
            })->first();

            $captions = $title->getTitleMediaMapping()->whereHas('getMedia', function ($query) {
                return $query->where('file_type', MediaTypes::Caption->value);
            })->get();

            $languages = $title->getTitleMediaMapping()->whereHas('getMedia', function ($query) {
                return $query->where('file_type', MediaTypes::AdditionalLanguage->value);
            })->get();

            $movies = new \stdClass();
            $movies->poster = $poster;
            $movies->poster_landscape = $poster_landscape;
            $movies->main_video = $mainVideo;
            $movies->trailer = $trailer;
            $movies->captions = $captions;
            $movies->languages = $languages;

        } else {
            $seasons = Season::where('title_id', $id)->get();
        }
        $legal_doc = $title->getTitleMediaMapping()->whereHas('getMedia', function ($query) {
            return $query->where(['file_type' => MediaTypes::LegalDoc->value]);
        })->first();

        return view('titles.edit', compact('title', 'ratings', 'primaryContact', 'secondaryContact', 'seasons', 'movies','legal_doc', 'countries'));
    }

    public function titleEditProfile(TitleProfileRequest $request, $uuid) {

        $title = Title::where(['uuid' => $uuid])
            ->whereHas('getUser')->first();

        $title->update(['name' => $request->title_name]);
        $title->getMeta()->updateOrCreate(['title_id' => $title->id], $request->only('synopsis', 'sales_pitch'));

        if ($title->type->value == TitleType::Movie->value) {
            $title->getMovieMeta()->updateOrCreate(['title_id' => $title->id], $request->only('release_date', 'duration'));
        }

        $title->getGenreMappings()->delete();
        if ($request->has('genres') && !empty($request->genres)) {
            foreach ($request->genres as $genreId) {
                $title->getGenreMappings()->create(['genre_id' => $genreId,]);
            }
        }

        $title->getKeywordMappings()->delete();
        if ($request->has('keywords') && !empty($request->keywords)) {
            foreach ($request->keywords as $keywordId) {
                if (!is_numeric($keywordId)) {
                    $keywordId = Keywords::firstOrCreate(['name' => $keywordId])->id;
                }
                $title->getKeywordMappings()->create(['keyword_id' => $keywordId,]);
            }

        }

        $title->getLicenceCountryMapping()->delete();
        if ($request->has('licence_country') && !empty($request->licence_country)) {
            foreach ($request->licence_country as $lic_country) {
                if (is_numeric($lic_country)) {
                    $title->getLicenceCountryMapping()->create(['country_id' => $lic_country,]);
                }
            }
        }

        $title->getProductionCountriesMappings()->delete();
        if ($request->has('production_countries') && !empty($request->production_countries)) {
            foreach ($request->production_countries as $countryId) {
                $title->getProductionCountriesMappings()->create(['country_id' => $countryId]);
            }
        }

        $title->getOfficialRatingMappings()->delete();
        if ($request->has('rating') && !empty($request->rating)) {
            foreach ($request->rating as $rating) {
                if (!is_numeric($rating)) {
                    $rating = OfficialRating::firstOrCreate(['name' => $rating])->id;
                }
                $title->getOfficialRatingMappings()->create(['official_rating_id' => $rating]);
            }
        }

        // Save IMDb link in catalogs
        $title->getCatalog()->updateOrCreate(['title_id' => $title->id], ['imdb_url' => $request->imdb_link]);


        // Remove all existing entries for the given title_id
        Cast::where('title_id', $title->id)->delete();
        // Re-insert the new entries into Cast
        if ($request->has('actor') && !empty($request->actor)) {
            foreach ($request->actor as $index => $actorName) {
                if (!empty($actorName)) {
                    $actor = Actor::updateOrCreate(['name' => $actorName]);
                    Cast::create([
                        'title_id' => $title->id,
                        'actor_id' => $actor->id,
                        'character' => $request->character[$index] ?? null,
                    ]);
                }
            }
        }

        $title->getDirectorMappings()->delete();
        if ($request->has('directors') && !empty($request->directors)) {
            foreach ($request->directors as $director) {
                if (!is_numeric($director)) {
                    $director = Director::firstOrCreate(['name' => $director])->id;
                }
                $title->getDirectorMappings()->create(['director_id' => $director]);
            }
        }


        $title->getProducerMappings()->delete();
        // Re-insert the new entries into MapProducerTitle
        if ($request->has('producers') && !empty($request->producers)) {
            foreach ($request->producers as $producer) {
                if (!is_numeric($producer)) {
                    $producer = Producer::firstOrCreate(['name' => $producer])->id;
                }
                $title->getProducerMappings()->create(['producer_id' => $producer]);
            }
        }

        $title->getWriterMappings()->delete();
        if ($request->has('writers') && !empty($request->writers)) {
            foreach ($request->writers as $writer) {
                if (!is_numeric($writer)) {
                    $writer = Writer::firstOrCreate(['name' => $writer])->id;
                }
                $title->getWriterMappings()->create(['writer_id' => $writer]);
            }
        }

        $title->getComposerMappings()->delete();
        if ($request->has('composers') && !empty($request->composers)) {
            foreach ($request->composers as $composer) {
                if (!is_numeric($composer)) {
                    $composer = Composers::firstOrCreate(['name' => $composer])->id;
                }
                $title->getComposerMappings()->create(['composer_id' => $composer]);
            }
        }

        $title->getTagMappings()->delete();
        if ($request->has('tags') && !empty($request->tags)) {
            foreach ($request->tags as $tag) {
                if (!is_numeric($tag)) {
                    $tag = Tag::firstOrCreate(['name' => $tag])->id;
                }
                $title->getTagMappings()->create(['tag_id' => $tag]);
            }
        }


        $title->getAdvisoryMappings()->delete();
        if ($request->has('advisories') && !empty($request->advisories)) {
            foreach ($request->advisories as $advisoryId) {
                if (!is_numeric($advisoryId)) {
                    $advisoryId = Advisory::firstOrCreate(['name' => $advisoryId])->id;
                }
                $title->getAdvisoryMappings()->create(['advisory_id' => $advisoryId]);
            }
        }

        $title->getFestivalMappings()->where(['type' => TitleFestival::Accepted->value])->delete();
        if ($request->has('festival_accepted') && !empty($request->festival_accepted)) {
            foreach ($request->festival_accepted as $festival) {
                if (!is_numeric($festival)) {
                    $randomSlug = Str::random(3) . '-' . Str::random(3);
                    $festival = Festival::firstOrCreate(
                        ['name' => $festival],
                        ['slug' => $randomSlug]
                    )->id;
                }
                $title->getFestivalMappings()->create(['festival_id' => $festival, 'type' => TitleFestival::Accepted->value]);
            }
        }

        $title->getFestivalMappings()->where(['type' => TitleFestival::Won->value])->delete();
        if ($request->has('festivals_won') && !empty($request->festivals_won)) {
            foreach ($request->festivals_won as $festival) {
                if (!is_numeric($festival)) {
                    $randomSlug = Str::random(3) . '-' . Str::random(3);
                    $festival = Festival::firstOrCreate(
                        ['name' => $festival],
                        ['slug' => $randomSlug]
                    )->id;
                }
                $title->getFestivalMappings()->create(['festival_id' => $festival, 'type' => TitleFestival::Won->value]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Profile tab successfully saved.',
            'function' => 'switchTab'
        ], 200);

    }

    public function titleEditContact(TitleContactRequest $request, $uuid) {
        $title = Title::where(['uuid' => $uuid])
            ->whereHas('getUser')->first();
        $this->saveOrUpdateContact(
            $title->id,
            [
                'name' => $request->primary_name,
                'email' => $request->primary_email,
                'phone' => $request->primary_phone,
                'whatsapp_number' => $request->primary_whatsapp_number,
                'phone_country_code' => $request->primary_phone_code,
                'whatsapp_country_code' => $request->primary_whatsapp_code,
                'role' => $request->primary_role,
                'type' => TitleContact::Primary->value, // Primary contact
            ]
        );

        // Handle Secondary Contact if provided
        $this->saveOrUpdateContact(
            $title->id,
            [
                'name' => $request->sameContact ? $request->primary_name : $request->secondary_name,
                'email' => $request->sameContact ? $request->primary_email : $request->secondary_email,
                'phone' => $request->sameContact ? $request->primary_phone : $request->secondary_phone,
                'whatsapp_number' => $request->sameContact ? $request->primary_whatsapp_number : $request->secondary_whatsapp_number,
                'phone_country_code' => $request->sameContact ? $request->primary_phone_code : $request->secondary_phone_code,
                'whatsapp_country_code' => $request->sameContact ? $request->primary_whatsapp_code : $request->secondary_whatsapp_code,
                'role' => $request->sameContact ? $request->primary_role : $request->secondary_role,
                'type' => TitleContact::Secondary->value, // Secondary contact
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Contacts saved successfully!',
            'function' => 'switchTab'
        ], 200);
    }

    private function saveOrUpdateContact($titleId, $contactData) {

        // Check if a contact already exists for the given title and type
        $existingMapping = MapContactTitle::where('title_id', $titleId)
            ->whereHas('getContact', function ($query) use ($contactData) {
                $query->where('type', $contactData['type']);
            })
            ->first();
        if ($existingMapping) {
            // Update the existing contact
            $contact = $existingMapping->getContact;
            $contact->update($contactData);
        } else {
            // Create a new contact
            $contact = Contact::create($contactData);

            // Map the new contact to the title
            $this->assignContactToTitle($titleId, $contact->id);
        }

        return $contact;
    }

    private function assignContactToTitle($titleId, $contactId) {
        MapContactTitle::create([
            'title_id' => $titleId,
            'contact_id' => $contactId,
        ]);
    }

    public function titleEditDocument(TitleDocumentRequest $request, $uuid) {
        $title = Title::where(['uuid' => $uuid])
            ->whereHas('getUser')->first();
        if (!$title) {
            abort(404);
        }

        $docInput = $request->uploaded_e_sign;

        if ($title->type->value == TitleType::Movie->value) {
            $directory = MediaTypes::moviePath($title->uuid, MediaTypes::LegalDoc->directoryPath());
        } else {
            $directory = MediaTypes::seasonPath($title->uuid, MediaTypes::LegalDoc->directoryPath());
        }
        $modal = MapTitleMedia::class;

        $toDelete = $modal::where('title_id', $title->id)->whereHas('getMedia', function ($query) use ($docInput) {
            return $query->where(['file_type' => MediaTypes::LegalDoc->value])->whereNotIn('file_name', [$docInput]);
        })->get();

        foreach ($toDelete as $delete) {
            FileUploadHelper::deleteFile($delete->getMedia->file_name, $delete->getMedia->file_path);
            $delete->getMedia->delete();
            $delete->delete();
        }

        $exist = $modal::where('title_id', $title->id)->whereHas('getMedia', function ($query) use ($docInput) {
            return $query->where(['file_type' => MediaTypes::LegalDoc->value, 'file_name' => $docInput]);
        })->first();

        $existFileName = $exist->getMedia->file_name ?? null;
        if ($exist && $existFileName == $docInput) {
            return response()->json(['status' => true, 'message' => 'Document saved successfully!', 'function' => 'switchTab'], 200);
        }

        $mediaId = Media::create([
            'file_path' => $directory,
            'file_type' => MediaTypes::LegalDoc->value,
            'file_name' => $docInput,
        ])->id;

        $modal::create([
            'title_id' => $title->id,
            'media_id' => $mediaId,
        ]);
        FileUploadHelper::moveFile($docInput, $directory);
        return response()->json(['status' => true, 'message' => 'Document saved successfully!', 'function' => 'switchTab'], 200);
    }

    public function titleEditVideo(TitleVideoRequest $request, $uuid) {
        $title = Title::where(['uuid' => $uuid])->whereHas('getUser')->first();
        if (!$title) {
            abort(404);
        }

        $multiInput = [
            ['input' => $request->input('uploaded_caption') ?? [], 'text' => $request->input('uploaded_caption_text') ?? [], 'path' => MediaTypes::Caption->directoryPath(), 'type' => MediaTypes::Caption->value],
            ['input' => $request->input('uploaded_language') ?? [], 'text' => $request->input('uploaded_language_text') ?? [], 'path' => MediaTypes::AdditionalLanguage->directoryPath(), 'type' => MediaTypes::AdditionalLanguage->value],
        ];

        foreach ($multiInput as $input) {
            // Caption check/remove and cerate
            $fileNames = $input['input'];
            $fileTitle = $input['text'];
            // existing which are not submitted by form and removed
            $notExisting = MapTitleMedia::where('title_id', $title->id)->whereHas('getMedia', function ($query) use ($fileNames, $input) {
                return $query->where(['file_type' => $input['type']])->whereNotIn('file_name', $fileNames);
            })->with('getMedia')->get();

            foreach ($notExisting as $notExist) {
                FileUploadHelper::deleteFile($notExist->getMedia->file_name, $notExist->getMedia->file_path);
                $notExist->getMedia->delete(); // first remove file entry
                $notExist->delete(); // then remove mapping
            }

            $filePath = $title->type == TitleType::Movie ? MediaTypes::moviePath($uuid, $input['path']) : MediaTypes::seasonPath($uuid, $input['path']);

            foreach ($fileNames as $key => $caption) {
                $isExistCaption = $title->getTitleMediaMapping()->where('title_id', $title->id)->whereHas('getMedia', function ($query) use ($input, $caption) {
                    return $query->where(['file_type' => $input['type'], 'file_name' => $caption]);
                })->first();

                if ($isExistCaption) {
                    $isExistCaption->update(['text' => $fileTitle[$key] ?? null]);
                } else {
                    $mediaId = Media::create([
                        'file_path' => $filePath,
                        'file_type' => $input['type'],
                        'file_name' => $caption,
                    ])->id;
                    $title->getTitleMediaMapping()->create(['media_id' => $mediaId, 'text' => $fileTitle[$key] ?? null]);
                    FileUploadHelper::moveFile($caption, $filePath);
                }
            }
        }

        $singleInput = [
            ['input' => $request->input('uploaded_poster'), 'path' => MediaTypes::Image->directoryPath(), 'type' => MediaTypes::Image->value, 'transcode' => false],
            ['input' => $request->input('uploaded_poster_landscape'), 'path' => MediaTypes::Image_Landscape->directoryPath(), 'type' => MediaTypes::Image_Landscape->value, 'transcode' => false],
            ['input' => $request->input('uploaded_trailer'), 'path' => MediaTypes::Trailer->directoryPath(), 'type' => MediaTypes::Trailer->value, 'transcode' => true],
            ['input' => $request->input('uploaded_main_video'), 'path' => MediaTypes::MainVideo->directoryPath(), 'type' => MediaTypes::MainVideo->value, 'transcode' => true],
        ];

        foreach ($singleInput as $input) {
            if (!$input['input']) {
                continue;
            }

            $filePath = $title->type == TitleType::Movie ? MediaTypes::moviePath($uuid, $input['path']) : MediaTypes::seasonPath($uuid, $input['path']);

            $toDelete = MapTitleMedia::where('title_id', $title->id)->whereHas('getMedia', function ($query) use ($input) {
                return $query->where(['file_type' => $input['type']])->whereNotIn('file_name', [$input['input']]);
            })->get();

            foreach ($toDelete as $delete) {
                FileUploadHelper::deleteFile($delete->getMedia->file_name, $delete->getMedia->file_path, $input['transcode']);
                $delete->getMedia->delete();
                $delete->delete();
            }

            $exist = MapTitleMedia::where('title_id', $title->id)->whereHas('getMedia', function ($query) use ($input) {
                return $query->where(['file_type' => $input['type'], 'file_name' => $input['input']]);
            })->first();

            $existFileName = $exist->getMedia->file_name ?? null;
            if ($exist && $existFileName == $input['input']) {
                continue;
            }

            $mediaId = Media::create([
                'file_path' => $filePath,
                'file_type' => $input['type'],
                'file_name' => $input['input'],
            ])->id;
            $title->getTitleMediaMapping()->create(['media_id' => $mediaId]);
            FileUploadHelper::moveFile($input['input'], $filePath, $input['transcode']);
        }

        return response()->json([
            'status' => true,
            'message' => 'Video Form saved successfully!',
            'function' => 'switchTab'
        ], 200);
    }

    public function titleEditSeason(SeasonRequest $request, $uuid) {

        $title = Title::where(['uuid' => $uuid])->whereHas('getUser')->first();
        if (!$title) {
            abort(404);
        }
        $title_id = $title->id;
        // dd($request);
        foreach ($request->seasons as $season) {

            $seasn_id = Season::updateOrCreate(
                ['id' => $season['season_id'] ?? null],
                [
                    'title_id' => $title_id,
                    'uuid' => $season['season_id'] ? Season::find($season['season_id'])->uuid : Str::uuid(),
                    'name' => $season['season_name'] ?? null,
                    'synopsis' => $season['synopsis'] ?? null,
                    'release_date' => $season['release_date'] ?? null,
                ]
            );
            $uploadSeasonData = [];
            if (isset($season['season_image']) && !empty($season['season_image'])) {
                $uploadSeasonData[MediaTypes::Image->value] = [
                    'path' => MediaTypes::seasonPath($title->uuid, MediaTypes::Image->directoryPath(), $seasn_id->uuid),
                    'filename' => $season['season_image'],
                    'transcode' => false
                ];
            }
            if (isset($season['season_image_landscape']) && !empty($season['season_image_landscape'])) {
                $uploadSeasonData[MediaTypes::Image_Landscape->value] = [
                    'path' => MediaTypes::seasonPath($title->uuid, MediaTypes::Image_Landscape->directoryPath(), $seasn_id->uuid),
                    'filename' => $season['season_image_landscape'],
                    'transcode' => false
                ];
            }
            if (isset($season['season_trailer']) && !empty($season['season_trailer'])) {
                $uploadSeasonData[MediaTypes::Trailer->value] = [
                    'path' => MediaTypes::seasonPath($title->uuid, MediaTypes::Trailer->directoryPath(), $seasn_id->uuid),
                    'filename' => $season['season_trailer'],
                    'transcode' => true
                ];
            }
            foreach ($uploadSeasonData as $SeasonMediaType => $MediaDetails) {

                $existingSeason = SeasonMedia::where('season_id', $seasn_id->id)->whereHas('getMedia', function ($query) use ($SeasonMediaType, $MediaDetails) {
                    $query->where('file_type', $SeasonMediaType)->where('file_name', '!=', $MediaDetails['filename']);
                })->get();

                foreach ($existingSeason as $existingSeasonMedia) {
                    FileUploadHelper::deleteFile($existingSeasonMedia->getMedia->file_name, $existingSeasonMedia->getMedia->file_path, $MediaDetails['transcode']);
                    $existingSeasonMedia->getMedia->delete();
                    $existingSeasonMedia->delete();
                }

                $exist = SeasonMedia::where('season_id', $seasn_id->id)->whereHas('getMedia', function ($query) use ($MediaDetails, $SeasonMediaType) {
                    return $query->where(['file_type' => $SeasonMediaType, 'file_name' => $MediaDetails['filename']]);
                })->first();

                $existFileName = $exist->getMedia->file_name ?? null;
                if ($exist && $existFileName == $MediaDetails['filename']) {
                    continue;
                }

                $mediaId = Media::create([
                    'file_path' => $MediaDetails['path'],
                    'file_type' => $SeasonMediaType,
                    'file_name' => $MediaDetails['filename'],
                ])->id;
                SeasonMedia::create([
                    'season_id' => $seasn_id->id,
                    'media_id' => $mediaId,
                ]);
                FileUploadHelper::moveFile($MediaDetails['filename'], $MediaDetails['path'], $MediaDetails['transcode']);
            }
            if (!empty($season['episodes'])) {
                foreach ($season['episodes'] as $episode) {
                    $epsode_id = Episode::updateOrCreate(
                        [
                            'id' => $episode['episode_id'] ?? null // Check if 'id' exists in the $episode array
                        ],
                        [
                            'season_id' => $seasn_id->id,
                            'uuid' => $episode['episode_id'] ? Episode::find($episode['episode_id'])->uuid : Str::uuid(),
                            'name' => $episode['episode_name'] ?? null,
                            'synopsis' => $episode['synopsis'] ?? null,
                            'release_date' => $episode['release_date'] ?? null,
                        ]
                    );
                    $uploadSingleInputData = [];
                    if (isset($episode['main_video']) && !empty($episode['main_video'])) {
                        $uploadSingleInputData[] = [
                            'path' => MediaTypes::episodePath($title->uuid, $seasn_id->uuid, $epsode_id->uuid, MediaTypes::MainVideo->directoryPath()),
                            'filename' => $episode['main_video'],
                            'transcode' => true,
                            'mediaType' => MediaTypes::MainVideo->value
                        ];
                    }

                    foreach ($uploadSingleInputData as $details) {

                        $mediaType = $details['mediaType'];

                        $existingEpisode = MapEpisodeMedia::where('episode_id', $epsode_id->id)
                            ->whereHas('getMedia', function ($query) use ($mediaType, $details) {
                                $query->where('file_type', $mediaType)->where('file_name', '!=', $details['filename']);
                            })->get();

                        foreach ($existingEpisode as $existingEpisodeMedia) {
                            FileUploadHelper::deleteFile($existingEpisodeMedia->getMedia->file_name, $existingEpisodeMedia->getMedia->file_path, $details['transcode']);
                            $existingEpisodeMedia->getMedia->delete();
                            $existingEpisodeMedia->delete();
                        }
                        $ExistEpisodeSingleMedia = MapEpisodeMedia::where('episode_id', $epsode_id->id)->whereHas('getMedia', function ($query) use ($details) {
                            return $query->where(['file_type' => $details['mediaType'], 'file_name' => $details['filename']]);
                        })->first();

                        $existEpisodeSingleFileName = $ExistEpisodeSingleMedia->getMedia->file_name ?? null;
                        if ($ExistEpisodeSingleMedia && $existEpisodeSingleFileName == $details['filename']) {
                            continue;
                        }

                        $mediaId = Media::create([
                            'file_path' => $details['path'],
                            'file_type' => $details['mediaType'],
                            'file_name' => $details['filename'],
                        ])->id;
                        MapEpisodeMedia::create([
                            'episode_id' => $epsode_id->id,
                            'media_id' => $mediaId
                        ]);
                        FileUploadHelper::moveFile($details['filename'], $details['path'], $details['transcode']);
                    }

                    $uploadMultipleInputData = [];
                    if (isset($episode['caption']) && count($episode['caption']) > 0) {
                        foreach ($episode['caption'] as $caption) {
                            $uploadMultipleInputData[] = [
                                'path' => MediaTypes::episodePath($title->uuid, $seasn_id->uuid, $epsode_id->uuid, MediaTypes::Caption->directoryPath()),
                                'files' => $episode['caption'],
                                'mediaType' => MediaTypes::Caption->value,
                            ];
                        }
                    }
                    if (isset($episode['dubbed_language']) && count($episode['dubbed_language']) > 0) {
                        foreach ($episode['dubbed_language'] as $language) {
                            $uploadMultipleInputData[] = [
                                'path' => MediaTypes::episodePath($title->uuid, $seasn_id->uuid, $epsode_id->uuid, MediaTypes::AdditionalLanguage->directoryPath()),
                                'files' => $episode['dubbed_language'],
                                'mediaType' => MediaTypes::AdditionalLanguage->value
                            ];
                        }
                    }

                    foreach ($uploadMultipleInputData as $MultiInputdetails) {
                        $fileNames = [];
                        $fileTitle = [];
                        foreach ($MultiInputdetails['files'] as $file) {
                            $fileNames[] = $file['file'];
                            $fileTitle[] = $file['text'];
                        }

                        $notEpisodeExsits = MapEpisodeMedia::where('episode_id', $epsode_id->id)->whereHas('getMedia', function ($query) use ($fileNames, $MultiInputdetails) {
                            return $query->where(['file_type' => $MultiInputdetails['mediaType']])->whereNotIn('file_name', $fileNames);
                        })->with('getMedia')->get();

                        foreach ($notEpisodeExsits as $notEpisodeExsit) {
                            FileUploadHelper::deleteFile($notEpisodeExsit->getMedia->file_name, $notEpisodeExsit->getMedia->file_path);
                            $notEpisodeExsit->getMedia->delete(); // first remove file entry
                            $notEpisodeExsit->delete(); // then remove mapping
                        }


                        foreach ($fileNames as $key => $filename) {
                            $isExistCaption = MapEpisodeMedia::where('episode_id', $epsode_id->id)->whereHas('getMedia', function ($query) use ($MultiInputdetails, $filename) {
                                return $query->where(['file_type' => $MultiInputdetails['mediaType'], 'file_name' => $filename]);
                            })->first();

                            if ($isExistCaption) {
                                $isExistCaption->update(['text' => $fileTitle[$key] ?? null]);
                            } else {
                                $mediaId = Media::create([
                                    'file_path' => $MultiInputdetails['path'],
                                    'file_type' => $MultiInputdetails['mediaType'],
                                    'file_name' => $filename,
                                ])->id;
                                MapEpisodeMedia::create([
                                    'episode_id' => $epsode_id->id,
                                    'media_id' => $mediaId,
                                    'text' => $fileTitle[$key] ?? null
                                ]);
                                FileUploadHelper::moveFile($filename, $MultiInputdetails['path']);
                            }
                        }
                    }

                }
            }
        }
        return response()->json(['success' => true, 'message' => 'Seasons saved successfully'], 200);
    }


    public function submitForReview(Request $request, $uuid) {


        $title = Title::where(['uuid' => $uuid])->first();

        $errors = [];
        if (!count($title->getGenres)) {
            $errors['profile_tab'] = 'Profile tab is incomplete. Please fill in all required fields.';
        }

        if (!count($title->getContacts)) {
            $errors['contact_tab'] = 'Contact tab is incomplete. Please fill in all required fields.';
        }

        $legalDoc = $title->getTitleMediaMapping()->whereHas('getMedia', function ($query) {
            return $query->where(['file_type' => MediaTypes::LegalDoc->value]);
        })->first();

        if (!$legalDoc) {
            $errors['Document_tab'] = 'Document tab is incomplete. Please fill in all required fields.';
        }

        if ($title->type == TitleType::Movie) {
            $mainVideo = $title->getTitleMediaMapping()->whereHas('getMedia', function ($query) {
                return $query->where('file_type', MediaTypes::MainVideo->value);
            })->first();

            if (!$mainVideo) {
                $errors['video_tab'] = 'Video tab is incomplete. Please fill in all required fields.';
            }
        } else {
            $season = $title->getSeasons()
                ->whereHas('getMedia', function ($query) {
                    $query->where('file_type', MediaTypes::Image->value);
                })->first();

            if (!$season) {
                $errors['season_tab'] = 'Season tab is incomplete. Please fill in all required fields.';
            }
        }

        if ($errors) {
            $errorMessages = '<ul><li>' . implode('</li><li>', array_values($errors)) . '</li></ul>';
            return response()->json(['status' => false, 'message' => $errorMessages], 422);
        }

        $title->isSubmitted = TitleCompletion::Completed;
        $title->save();

        return response()->json(['status' => true, 'message' => 'Title submitted successfully'], 200);
    }

    public function updateStatus(Request $request): JsonResponse {
        $title = Title::find($request->id);
        if ($request->status == TitleStatus::Rejected->value) {
            $title->status = $request->status;
            $title->reason = $request->message;
        } else {
            $title->status = $request->status;
        }
        $title->save();
        $head_msg = $request->message ?? null;
        Session::put('success', 'Title status has updated successfully.');
        $mailable = new AdminNotificationToSeller($title, $head_msg, ($request->message ?? null));
        Helpers::sendMail($mailable, $title->getUser->email);
        return response()->json(['success' => true]);
    }

    public function addMessage(TitleConversationRequest $request, $slug): JsonResponse {
        $title = Title::where('slug', $slug)->first();
        if (!$title) {
            abort(404);
        }
        $message = $request->message;
        $subject = $request->subject ?? AdminNotificationToSeller::$defaultSubject;
        TitleConversation::create([
            'title_id' => $title->id,
            'message' => $message,
            'subject' => $subject
        ]);
        $head_msg = $request->message;
        Session::put('success', 'Message added to title conversation successfully.');
        $mailable = new AdminNotificationToSeller($title->getUser->name, $head_msg, ($request->message ?? null));
        Helpers::sendMail($mailable, $title->getUser->email);
        return response()->json(['success' => true]);
    }

    public function revenuePlan($slug): View {
        $title = Title::where(['slug' => $slug, 'user_id' => auth()->id(), 'status' => TitleStatus::Accepted->value])
            ->whereHas('getUser', function ($query) {
                return $query->whereHas('getUserProfile', function ($queryProfile) {
                    return $queryProfile->where('role_id', Roles::Seller->value);
                });
            })->first();
        if (!$title) {
            abort(404);
        }
        $revenuePlan = $title->getRevenuePlan;
        if ($revenuePlan) {
            if ($revenuePlan->status->value != RevenuePlanStatus::Pending->value) {
                abort(403);
            }
        }
        return view('titles.revenue.revenuePlan', compact('title'));
    }

    public function revenuePlanSubmit($slug, RevenuePlanRequest $request): JsonResponse {
        $title = Title::where(['slug' => $slug, 'user_id' => auth()->id(), 'status' => TitleStatus::Accepted->value])
            ->whereHas('getUser', function ($query) {
                return $query->whereHas('getUserProfile', function ($queryProfile) {
                    return $queryProfile->where('role_id', Roles::Seller->value);
                });
            })->first();
        if (!$title) {
            abort(404);
        }

        if ($title->getRevenuePlan && $title->getRevenuePlan->status->value != RevenuePlanStatus::Pending->value) {
            abort(403);
        }

        $title->getRevenuePlan = RevenuePlan::updateOrCreate(
            ['title_id' => $title->id],
            [
                'type' => $request->get('type'),
                'status' => RevenuePlanStatus::Pending->value,
            ]);

        $embedFormData = null;
        if ($title->getRevenuePlan->status->value == RevenuePlanStatus::Pending->value) {
            $docusign = new DocusignHelper();
            $embedFormData = $docusign->getFormUrlData($title->id);
        }

        return response()->json(['status' => true, 'message' => 'Please Sign Your Document.', 'data' => $embedFormData, 'function' => 'docusignWebEmbedForm']);
    }

    public function revenuePlanSign($slug, Request $request) {
        $title = Title::where(['slug' => $slug, 'user_id' => auth()->id(), 'status' => TitleStatus::Accepted->value])
            ->whereHas('getUser', function ($query) {
                return $query->whereHas('getUserProfile', function ($queryProfile) {
                    return $queryProfile->where('role_id', Roles::Seller->value);
                });
            })->whereHas('getRevenuePlan', function ($query) {
                return $query->where('status', RevenuePlanStatus::Pending->value);
            })
            ->first();

        if (!$title) {
            abort(403);
        }

        // Save envelope id for view document and update status
        $envelopeId = $request->get('envelope_id');
        $title->getRevenuePlan->update(['status' => RevenuePlanStatus::InReview->value, 'envelope_id' => $envelopeId]);
        Session::flash('success', 'Review form signed. Please wait for admin to verify.');

        Helpers::sendMail(new DocusignMail($title), $title->getUser->email);

        return response()->json(['status' => true, 'url' => route('titles')]);
    }

    public function agreementStatus($slug, $status) {
        $title = Title::where(['slug' => $slug, 'status' => TitleStatus::Accepted->value])
            ->whereHas('getUser', function ($query) {
                return $query->whereHas('getUserProfile', function ($queryProfile) {
                    return $queryProfile->where('role_id', Roles::Seller);
                });
            })->whereHas('getRevenuePlan', function ($query) {
                return $query->where('status', RevenuePlanStatus::InReview->value);
            })
            ->first();

        if (!$title || !in_array($status, [RevenuePlanStatus::Pending->value, RevenuePlanStatus::Final->value])) {
            abort(404);
        }

        $title->getRevenuePlan->update(['status' => $status]);

        return response()->json(['status' => true, 'message' => 'Agreement Status Updated', 'function' => 'agreementStatusUpdate']);
    }

    public function viewAgreement($slug) {
        $title = Title::where(['slug' => $slug, 'status' => TitleStatus::Accepted->value])
            ->whereHas('getUser', function ($query) {
                return $query->whereHas('getUserProfile', function ($queryProfile) {
                    return $queryProfile->where('role_id', Roles::Seller);
                });
            })->whereHas('getRevenuePlan', function ($query) {
                return $query->whereIn('status', [RevenuePlanStatus::InReview->value, RevenuePlanStatus::Final->value]);
            })
            ->first();
        if (!$title) {
            abort(404);
        }
        $envelopeId = $title->getRevenuePlan->envelope_id;
        if (!$envelopeId) {
            abort(404);
        }
        $docusign = new DocusignHelper();
        $documents = $docusign->getEnvelopeDocuments($envelopeId);
        return response()->json(['status' => true, 'data' => $documents]);
    }

    public function titleProfile($slug, $seasonSlug = null) {
        $title = Title::where(['uuid' => $slug])->first();
        if (!$title) {
            abort(404);
        }
        $season = null;
        if($seasonSlug){
            $season = Season::where(['uuid' => $seasonSlug])->first();
            if(!$season){
                abort(404);
            }
        }

        $trailerMedia = null;
        if($title->type == TitleType::Series){
            if($season){
                $trailerMedia = $season->getMedia()->where('file_type', MediaTypes::Trailer->value)->first();
            }else{
                $trailer = $title->getSeasons()
                ->orderBy('created_at', 'desc')
                ->with(['getMedia' => function($query) {
                    $query->where('file_type', MediaTypes::Trailer->value);
                }])->first();
                if($trailer){
                    $trailerMedia = $trailer->getMedia->first();
                }
            }
        } else {
            $trailer = $title->getTitleMediaMapping()->whereHas('getMedia', function ($query) {
                return $query->where('file_type', MediaTypes::Trailer->value);
            })->first();
            if ($trailer) {
                $trailerMedia = $trailer->getMedia;
            }
        }
        if ($trailerMedia) {
            $fileObject = FileUploadHelper::filePathUrl($trailerMedia->file_name, $trailerMedia->file_path);
            $url = $fileObject ? $fileObject->url : null;
            $title->trailerUrl = $url;
        } else {
            $title->trailerUrl = null;
        }

        return view('titles.viewProfile', compact('title','season'))->render();
    }

    public function sellerAnalytics(Request $request) {
        $dateRange = (int) $request->input('date_range')  ?? null;
        $analyticsData['platforms'] = OttPlatforms::all();
        $startDate = $dateRange ? now()->subDays($dateRange) : $request->input('start_date') ?? now()->subDays(30);
        $endDate = $dateRange ? now() : Carbon::parse($request->input('end_date'))->endOfDay() ?? now();
        $userId = $request->input('sellerId') ?? Auth::id();

        $analyticsData['approvedTitles'] = Title::where('user_id', $userId)
        ->whereHas('getRevenuePlan', function($query) {
            $query->where('status', RevenuePlanStatus::Final->value);
        })
        ->get(['id', 'name']);

        $selectedPlatformId = $request->filled('platform_id') ? $request->platform_id : 'all';
        $platformIds = $selectedPlatformId === 'all' ? $analyticsData['platforms']->pluck('id')->toArray() : [$selectedPlatformId];
        $analyticsData['selectedTitleId'] = $request->filled('title_id') ? $request->title_id : 'all';

        $titleIds = $analyticsData['selectedTitleId'] === 'all'
            ? $analyticsData['approvedTitles']->pluck('id')->toArray()
            : [$analyticsData['selectedTitleId']];

        $baseQuery = DB::table('title_video_analytics')
            ->whereIn('title_id', $titleIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('platform_id', $platformIds);

        $analyticsData['totalViews'] = $baseQuery->sum('total_views');
        $analyticsData['totalUniqueViews'] = $baseQuery->sum('unique_views');

        $analyticsData['totalWatchTime'] = $baseQuery->sum('total_watch_time') / 3600;

        $analyticsData['avgCompletionRate'] = $baseQuery->avg('completion_rate');

        // Get daily views for line chart
        $analyticsData['dailyViews'] = DB::table('title_video_analytics')
        ->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_views) as total_views'),
            DB::raw('SUM(unique_views) as unique_views'),
            DB::raw('SUM(total_watch_time) / 3600 as watch_time_hours'),
            DB::raw('AVG(completion_rate) as completion_rate')
        )
        ->whereIn('title_id', $titleIds)
        ->whereIn('platform_id', $platformIds)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        $analyticsData['countryData'] = DB::table('title_video_analytics')
        ->join('countries', 'title_video_analytics.country_id', '=', 'countries.id')
        ->select(
            'countries.name',
            DB::raw('SUM(total_views) as total_views'),
            DB::raw('AVG(completion_rate) as completion_rate')
        )
        ->whereIn('title_video_analytics.title_id', $titleIds)
        ->whereIn('platform_id', $platformIds)
        ->whereBetween('title_video_analytics.created_at', [$startDate, $endDate])
        ->groupBy('countries.id', 'countries.name')
        ->orderBy('total_views', 'desc')
        ->limit(10) // Top 10 countries
        ->get();

        if($request->isAjax){
            return view('backend.Seller.Component.analytics', compact('analyticsData'))->render();
        }


        return view('seller.analytics', compact('analyticsData'));
    }
}
