@extends('layouts.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('match.detail', $matchId) }}">Match</a></li>
    <li class="breadcrumb-item active">Live Stream</li>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-xl-9 col-lg-8">

            {{-- Stream Header --}}
            <div class="stream-header mb-3">
                <h1 class="stream-title">
                    <span class="live-dot"></span>
                    {{ ($matchData['home_team']['name'] ?? 'Home') . ' vs ' . ($matchData['away_team']['name'] ?? 'Away') }}
                </h1>
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <span class="badge bg-danger"><i class="bi bi-broadcast"></i> LIVE</span>
                    @if(!empty($matchData['competition']['name']))
                        <span class="badge bg-secondary">{{ $matchData['competition']['name'] }}</span>
                    @endif
                    <button class="btn btn-sm btn-outline-light ms-auto" id="fullscreen-btn">
                        <i class="bi bi-fullscreen"></i> Fullscreen
                    </button>
                </div>
            </div>

            {{-- Video Ad --}}
            <div class="ad-zone ad-video mb-3">
                {!! app(\App\Services\AdManager::class)->renderSlot('video') !!}
            </div>

            {{-- Stream Player --}}
            <div class="stream-player" id="stream-player">
                @if(!empty($streams))
                    @php $activeStream = $streams[0]; @endphp
                    <div class="stream-iframe-wrapper" id="stream-wrapper">
                        <iframe
                            id="stream-iframe"
                            src="{{ $activeStream['embed_url'] ?? $activeStream['url'] ?? '' }}"
                            allowfullscreen
                            allow="autoplay; encrypted-media; fullscreen; picture-in-picture"
                            scrolling="no"
                            frameborder="0"
                            width="100%"
                            height="100%"
                            loading="lazy"
                        ></iframe>
                    </div>

                    {{-- Stream Source Switcher --}}
                    @if(count($streams) > 1)
                    <div class="stream-sources mt-3">
                        <span class="text-muted small me-2"><i class="bi bi-broadcast"></i> Sources:</span>
                        @foreach($streams as $i => $stream)
                            <button class="btn btn-sm {{ $i === 0 ? 'btn-primary' : 'btn-outline-secondary' }} stream-source-btn me-2"
                                    data-src="{{ $stream['embed_url'] ?? $stream['url'] ?? '' }}"
                                    data-index="{{ $i }}">
                                Stream {{ $i + 1 }}
                                @if(!empty($stream['quality']))
                                    <span class="badge bg-success ms-1">{{ $stream['quality'] }}</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                    @endif
                @else
                    <div class="stream-unavailable">
                        <div class="stream-unavailable__inner">
                            <i class="bi bi-broadcast-pin display-1 text-muted"></i>
                            <h3>Stream Not Available</h3>
                            <p class="text-muted">The stream for this match is not available yet.<br>Please check back closer to kickoff time.</p>
                            <a href="{{ route('match.detail', $matchId) }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left"></i> Back to Match Info
                            </a>
                        </div>
                    </div>
                @endif
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="col-xl-3 col-lg-4">
            {{-- Sidebar Ad --}}
            <div class="ad-zone ad-sidebar mb-4">
                {!! app(\App\Services\AdManager::class)->renderSlot('sidebar') !!}
            </div>

            {{-- Live Score Mini --}}
            <div class="info-card mb-4">
                <h6 class="info-card__title"><i class="bi bi-broadcast"></i> Live Score</h6>
                <div class="live-score-mini">
                    <div class="d-flex align-items-center justify-content-between py-3">
                        <span>{{ $matchData['home_team']['name'] ?? 'Home' }}</span>
                        <span class="score-mini fw-bold">
                            {{ $matchData['score']['home'] ?? 0 }} - {{ $matchData['score']['away'] ?? 0 }}
                        </span>
                        <span>{{ $matchData['away_team']['name'] ?? 'Away' }}</span>
                    </div>
                </div>
                <a href="{{ route('match.detail', $matchId) }}" class="btn btn-outline-primary btn-sm w-100 mt-2">
                    <i class="bi bi-info-circle"></i> Full Match Info
                </a>
            </div>

            {{-- Mobile Ad --}}
            <div class="ad-zone ad-mobile d-lg-none mb-4">
                {!! app(\App\Services\AdManager::class)->renderSlot('mobile') !!}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Fullscreen
document.getElementById('fullscreen-btn')?.addEventListener('click', function() {
    const wrapper = document.getElementById('stream-wrapper');
    if (wrapper.requestFullscreen) wrapper.requestFullscreen();
    else if (wrapper.webkitRequestFullscreen) wrapper.webkitRequestFullscreen();
});

// Stream source switching
document.querySelectorAll('.stream-source-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const src = this.dataset.src;
        document.getElementById('stream-iframe').src = src;
        document.querySelectorAll('.stream-source-btn').forEach(b => {
            b.classList.remove('btn-primary');
            b.classList.add('btn-outline-secondary');
        });
        this.classList.add('btn-primary');
        this.classList.remove('btn-outline-secondary');
    });
});
</script>
@endpush