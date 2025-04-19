@php
    use App\Enums\TitleType;
    use App\Enums\TitleFestival;
@endphp
<form id="movie-form" class="ajaxForm" method="post" action="{{ route('titleEditProfile',['uuid' => $title->uuid]) }}">
    @csrf
    <input type="hidden" name="title_id" id="title_id" value="{{ $title->id }}">
    <input type="hidden" name="title_type" value="{{$title->type}}">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="title_name">Title Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title_name" name="title_name"
                       placeholder="Title Name" value="{{ $title->name ?? '' }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="Synopsis">Synopsis</label>
                <textarea class="form-control" id="synopsis" name="synopsis" placeholder="Synopsis"
                          rows="3">{{ $title->getMeta->synopsis ?? '' }}</textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="sales_pitch">Sales Pitch</label>
                <textarea class="form-control" id="sales_pitch" name="sales_pitch" rows="3"
                          placeholder="Sales Pitch">{{ $title->getMeta->sales_pitch ?? '' }}</textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="genres">Genres<span class="text-danger">*</span></label>
                <select multiple class="form-control select2-dropdown" id="genres" name="genres[]"
                        data-placeholder="Select Genres" data-url="{{ route('genres.get') }}"
                        data-id="{{ $title->id }}">
                    @foreach ($title->getGenres as $genre)
                        <option value="{{ $genre->id }}" selected>{{ $genre->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group ">
                <label for="keywords">Keywords</label>
                <select id="keywords" name="keywords[]" class="form-control select2-dropdown"
                        multiple="multiple" style="width: 100%" data-tags="true"
                        data-placeholder="Type Keywords..." data-url="{{ route('keywords.get') }}"
                        data-id="{{ $title->id }}">
                    @foreach ($title->getKeywords as $keyword)
                        <option value="{{ $keyword->id }}" selected>{{ $keyword->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="genres">Licence Country<span class="text-danger">*</span></label>
                <select multiple class="form-control select2-dropdown" id="licence" name="licence_country[]"
                        data-placeholder="Select Country" data-url="{{ route('countries.get') }}"
                        data-id="{{ $title->id }}">
                    {{ $title->getLicenceCountry }}
                    @foreach ($title->getLicenceCountry as $licenceCountry)
                        <option value="{{ $licenceCountry->id }}" selected>{{ $licenceCountry->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    @if ($title->type->value == TitleType::Movie->value)
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="duration">Movie Duration<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="duration" name="duration"
                           value="{{$title->getMovieMeta->duration ?? ''}}"
                           placeholder="Enter movie duration in minutes">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="release-date">Original Release Date<span class="text-danger">*</span></label>
                    <input type="date" id="release-date" value="{{$title->getMovieMeta->release_date ?? ''}}"
                           name="release_date" class="form-control">
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="imdb-link">IMDB Link (Optional)</label>
                <input type="text" placeholder="Enter IMDB Link(if available)" id="imdb-link"
                       name="imdb_link" class="form-control" value="{{ $title->getCatalog->imdb_url ?? "" }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="production_countries">Countries of Production<span class="text-danger">*</span></label>
                <select multiple class="form-control select2-dropdown" id="production_countries"
                        name="production_countries[]" data-placeholder="Select Country"
                        data-url="{{ route('countries.get') }}" data-id="{{ $title->id }}">
                    @foreach ($title->getProductionCountries as $country)
                        <option value="{{ $country->id }}" selected>{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="rating">Rating<span class="text-danger">*</span></label>
                <select multiple="multiple" class="form-control select2-dropdown" id="rating" name="rating[]"
                        data-placeholder="Select Rating" data-url="{{ route('ratings.get') }}"
                        data-id="{{ $title->id }}">

                    @foreach ($title->getRatings as $rating)
                        <option value="{{ $rating->id }}" selected>{{ $rating->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div id="cast-list">
        @if($title->getActors->count() > 0)
            @foreach($title->getActors as $key => $actor)
                <div class="row" id="cast-row-{{ $key }}">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="actor">Actor</label>
                            <input type="text" id="actor" name="actor[]" class="form-control"
                                value="{{ $actor->name }}">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="character">Character</label>
                            <input type="text" id="character" name="character[]" class="form-control"
                                value="{{ $actor->pivot->character }}">
                        </div>
                    </div>
                    <div class="col-md-2 mt-auto">
                        <div class="form-group">
                            <label for="add_cast"></label>
                            <button class="form-control w-auto" type="button" onclick="removeCast('cast-row-{{ $key }}')"
                                    id="remove_cast">Remove
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
        <div class="row cast-row">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="actor">Actor<span class="text-danger">*</span></label>
                    <input type="text" name="actor[]" class="form-control">
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="character">Character<span class="text-danger">*</span></label>
                    <input type="text" name="character[]" class="form-control">
                </div>
            </div>
        </div>
        @endif
        @include('titles.form_sections.addCastTemplate')

    </div>
    <div class="row mb-3">
        <div class="col-md-2">
            <a href="javascript:void(0)" class="text-primary d-flex align-items-center" onclick="addCast()">
                <i class="fas fa-plus-circle me-1"></i>
                <span>Add More</span>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="directors">Directors<span class="text-danger">*</span></label>
                <select id="directors" name="directors[]" class="form-control select2-dropdown"
                        multiple="multiple" style="width: 100%" data-tags="true"
                        data-placeholder="Type Directors..." data-url="{{ route('directors.get') }}"
                        data-id="{{ $title->id }}">
                    @foreach ($title->getDirectors as $director)
                        <option value="{{ $director->id }}" selected>{{ $director->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="producers">Producers</label>
                <select id="producers" name="producers[]" class="form-control select2-dropdown"
                        multiple="multiple" style="width: 100%" data-tags="true"
                        data-placeholder="Type Producers..." data-url="{{ route('producers.get') }}"
                        data-id="{{ $title->id }}">
                    @foreach ($title->getProducers as $producer)
                        <option value="{{ $producer->id }}" selected>{{ $producer->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="writers">Writers</label>
                <select id="writers" name="writers[]" class="form-control select2-dropdown"
                        multiple="multiple" style="width: 100%" data-placeholder="Type Writers..."
                        data-tags="true" data-url="{{ route('writers.get') }}" data-id="{{ $title->id }}">
                    @foreach ($title->getWriters as $writer)
                        <option value="{{ $writer->id }}" selected>{{ $writer->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="composers">Composers</label>
                <select id="composers" name="composers[]" class="form-control select2-dropdown"
                        multiple="multiple" style="width: 100%" data-placeholder="Type Composers..."
                        data-tags="true" data-url="{{ route('composers.get') }}" data-id="{{ $title->id }}">
                    @foreach ($title->getComposers as $composer)
                        <option value="{{ $composer->id }}" selected>{{ $composer->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="tags">Tags<span class="text-danger">*</span></label>
                <select id="tags" name="tags[]" class="form-control select2-dropdown" multiple="multiple"
                        style="width: 100%" data-tags="true" data-placeholder="Type Tags..."
                        data-url="{{ route('tags.get') }}" data-id="{{ $title->id }}">
                    @foreach ($title->getTags as $tag)
                        <option value="{{ $tag->id }}" selected>{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="advisory">Advisory<span class="text-danger">*</span></label>
                <select multiple class="form-control select2-dropdown" data-placeholder="Select Advisory"
                        id="advisory" name="advisories[]" data-url="{{ route('advisories.get') }}"
                        data-id="{{ $title->id }}">
                    @foreach ( $title->getAdvisoryMappings as $mapAdvisoryTitle )
                        <option value="{{ $mapAdvisoryTitle->getAdvisory->id }}"
                                selected>{{ $mapAdvisoryTitle->getAdvisory->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="festival_accepted">Festivals Accepted</label>
                <select id="festival_accepted" name="festival_accepted[]"
                        class="form-control select2-dropdown" multiple="multiple" style="width: 100%"
                        data-tags="true" data-placeholder="Type Festival Accepted..."
                        data-url="{{ route('festival.get') }}" data-id="{{ $title->id }}">
                    @foreach ($title->getFestivals as $festival)
                        @if ($festival->pivot->type == TitleFestival::Accepted->value)
                            <option value="{{ $festival->id }}" selected>{{ $festival->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="festivals_won">Festivals Won</label>
                <select id="festivals_won" name="festivals_won[]" class="form-control select2-dropdown"
                        multiple="multiple" style="width: 100%" data-tags="true"
                        data-placeholder="Type Festivals Won..." data-url="{{ route('festival.get') }}"
                        data-id="{{ $title->id }}">
                    @foreach ($title->getFestivals as $festival)
                        @if ($festival->pivot->type == TitleFestival::Won->value)
                            <option value="{{ $festival->name }}" selected>{{ $festival->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row d-flex justify-content-end">
        <div class="col-md-2 d-flex justify-content-end">
            <button type="submit" class="form-control custom_submit_btn text-white w-75">Save & Next</button>
        </div>
    </div>
</form>
