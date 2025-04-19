<?php

namespace App\Http\Controllers;

use App\Enums\MediaTypes;
use App\Enums\TitleCompletion;
use App\Helpers\FileUploadHelper;
use App\Models\Episode;
use App\Models\Media;
use App\Models\MapTitleMedia;
use App\Models\Season;
use App\Models\Title;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\OttPlatforms;
use App\Enums\RevenuePlanStatus;

class SellerController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * @param $sellerId
     * @return RedirectResponse
     */

    public function destroy($sellerId): RedirectResponse {
        $user = User::find($sellerId);
        $user->delete();

        Session::put('success', 'Seller deleted successfully');

        return redirect()->route('superadmin.sellerlist');
    }

    /**
     * @param $sellerId
     * @return Renderable
     */
    public function view(Request $request, $sellerId): Renderable {
        $seller = User::find($sellerId);
        $query = Title::where('user_id', $sellerId)->where('isSubmitted', TitleCompletion::Completed->value);
        $titles = $query->orderBy('id', 'desc')->paginate(10);

        $analyticsData['platforms'] = OttPlatforms::all();
        $startDate = now()->subDays(30);
        $endDate = now();

        $analyticsData['approvedTitles'] = Title::where('user_id', $sellerId)
        ->whereHas('getRevenuePlan', function($query) {
            $query->where('status', RevenuePlanStatus::Final->value);
        })
        ->get(['id', 'name']);

        $baseQuery = DB::table('title_video_analytics')
        ->whereBetween('created_at', [$startDate, $endDate]);


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
        ->whereBetween('title_video_analytics.created_at', [$startDate, $endDate])
        ->groupBy('countries.id', 'countries.name')
        ->orderBy('total_views', 'desc')
        ->limit(10)
        ->get();

        return view('seller.editForm', compact('seller', 'titles', 'analyticsData'));
    }

    public function removeMedia(Request $request, $id = null) {

        if (isset($request->mediaFilename)) {
            $tempfileurl = storage_path('app/public/temp/' . $request->mediaFilename);
            if (file_exists($tempfileurl)) {
                unlink($tempfileurl);
            } else {
                return response()->json([
                    'message' => 'File not found.',
                    'success' => false,
                ], 404);

            }
            return response()->json([
                'message' => 'File deleted successfully.',
                'success' => true,
            ], 200);

        }

        $media = Media::find($id);
        if ($media) {
            $fileurl = storage_path('app/public/' . $media->file_path . '/' . $media->file_name);
            if (file_exists($fileurl)) {
                unlink($fileurl);
            }
            $media->delete();
        } else {
            return response()->json(['success' => false, 'message' => 'Media not found'], 404);
        }
        return response()->json([
            'message' => 'File deleted successfully.',
            'success' => true,
        ], 200);
    }


    public function removeEpisode($id) {
        $episode = Episode::find($id);
        if ($episode) {
            $episodeMedia = $episode->getMedia;
            if (!empty($episodeMedia)) {
                foreach ($episodeMedia as $media) {
                    $media->delete();
                }
            }
            $episode->delete();
        }
        return response()->json([
            'message' => 'Episode deleted successfully.',
            'success' => true,
        ], 200);
    }

    public function removeSeason($id) {
        $season = Season::find($id);
        if ($season) {
            if (!empty($season->getMedia)) {
                foreach ($season->getMedia as $media) {
                    $media->delete();
                }
            }
            if (!empty($season->getEpisodes())) {
                foreach ($season->getEpisodes() as $episode) {
                    if (!empty($episode->getMedia)) {
                        foreach ($episode->getMedia as $media) {
                            $media->delete();
                        }
                    }
                    $episode->delete();
                }
            }
            $season->delete();
            return response()->json([
                'message' => 'Season deleted successfully.',
                'success' => true,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Season not found.',
                'success' => false,
            ], 200);
        }
    }

    public function removeMovieMedia($id) {

        $media = Media::find($id);
        if ($media) {
            MapTitleMedia::where('media_id', $media->id)->delete();

            $media->delete();

            return response()->json([
                'message' => 'Media deleted successfully.',
                'success' => true, // Changed to true since deletion was successful
            ], 200);
        } else {
            return response()->json([
                'message' => 'Media not found.',
                'success' => false,
            ], 404);
        }
    }

    public function viewMediaUrl($slug) {
        $title = Title::where('slug', $slug)->first();
        if (!$title) {
            abort(404);
        }
        return view('backend.Seller.Models.titleUrls', compact('title'))->render();
    }

    public function viewDocument($slug) {
        $title = Title::where('slug', $slug)->first();
        if (!$title) {
            abort(404);
        }
        $legal_doc = $title->getTitleMediaMapping()->whereHas('getMedia', function ($query) {
            return $query->where(['file_type' => MediaTypes::LegalDoc->value]);
        })->first();
        $documentsList = [];
        if($legal_doc){
            $isExist = FileUploadHelper::filePathUrl($legal_doc->getMedia->file_name, $legal_doc->getMedia->file_path);

            if ($isExist) {
                $documentsList[] = $isExist;
            }
        }
        return (object)$documentsList;
    }

}
