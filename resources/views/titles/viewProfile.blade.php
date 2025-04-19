<style>
    .title-details-container {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .video-section {
        width: 100%;
        height: 250px;
        background: #000;
        margin-bottom: 30px;
    }

    .video-section video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .info-row {
        display: flex;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .info-label {
        width: 150px;
        min-width: 150px;
        color: #6c757d;
        padding-right: 20px;
        font-weight: 700;
    }

    .info-value {
        flex: 1;
    }

    .tag-container {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .separator {
        color: #6c757d;
        font-size: 1.2em;
        line-height: 1;
    }

    .tag-container span {
        display: inline-flex;
        align-items: center;
    }

    .title-heading {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 25px;
        color: #344767;
    }
</style>
<div class="title-details-container">
    <!-- Video Section -->
    <div class="video-section">
        @include('components.shaka_player',['url' => $title->trailerUrl])
    </div>

    <!-- Title Info Section -->
    <div class="title-info-section">
        <div class="info-row">
            <div class="info-label">Title Name</div>
            <div class="info-value">
                <div class="tag-container">
                    <span>{{ $title->name }}</span>
                </div>
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Synopsis</div>
            <div class="info-value">
                <div class="tag-container">
                    <span >{{ $season ? $season->synopsis : $title->getMeta->synopsis ?? 'No synopsis available' }}</span>
                </div>
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Genres</div>
            <div class="info-value">
                <div class="tag-container">
                    @foreach($title->getGenres as $genre)
                        <span >{{ $genre->name }}</span>
                        @if(!$loop->last)
                            <span class="separator">•</span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">License Country</div>
            <div class="info-value">
                <div class="tag-container">
                    @foreach($title->getLicenceCountry as $licenseCountry)
                        <span >{{ $licenseCountry->name }}</span>
                        @if(!$loop->last)
                            <span class="separator">•</span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Advisory</div>
            <div class="info-value">
                <div class="tag-container">
                    @foreach($title->getAdvisories as $advisory)
                        <span >{{ $advisory->name }}</span>
                        @if(!$loop->last)
                            <span class="separator">•</span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Directors</div>
            <div class="info-value">
                <div class="tag-container">
                    @foreach($title->getDirectors as $director)
                        <span >{{ $director->name }}</span>
                        @if(!$loop->last)
                            <span class="separator">•</span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Writers</div>
            <div class="info-value">
                <div class="tag-container">
                    @foreach($title->getWriters as $writer)
                        <span >{{ $writer->name }}</span>
                        @if(!$loop->last)
                            <span class="separator">•</span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Composers</div>
            <div class="info-value">
                <div class="tag-container">
                    @foreach($title->getComposers as $composer)
                        <span >{{ $composer->name }}</span>
                        @if(!$loop->last)
                            <span class="separator">•</span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const AWS_LICENCE_WIDEVINE_URL = '{{ env('AWS_LICENCE_WIDEVINE_URL') }}';
    const AWS_LICENCE_PLAYREADY_URL = '{{ env('AWS_LICENCE_PLAYREADY_URL') }}';
</script>
<script src="{{ asset('plugins/shaka/dist/shaka-player.compiled.js') }}"></script>
<script src="{{ asset('plugins/shaka/player.js') }}"></script>
<script>
    shaka_player.ready();
</script>