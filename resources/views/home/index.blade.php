@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 px-lg-4">

    {{-- Hero Section --}}
    @if($featuredMatches->isNotEmpty())
    <section class="featured-section py-4">
        <div class="section-header d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-title"><i class="bi bi-star-fill text-warning"></i> Featured Matches</h2>
        </div>
        <div class="row g-3">
            @foreach($featuredMatches->take(3) as $fm)
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('match.detail', $fm->match_id) }}" class="featured-card-link">
                    <div class="featured-card">
                        <div class="featured-badge">FEATURED</div>
                        <div class="featured-card-body">
                            @if($fm->thumbnail)
                                <img src="{{ $fm->thumbnail }}" alt="{{ $fm->title }}" class="featured-thumb" loading="lazy">
                            @else
                                <div class="featured-thumb-placeholder"><i class="bi bi-play-circle-fill"></i></div>
                            @endif
                            <div class="featured-info">
                                <h5 class="featured-title">{{ $fm->title ?: 'Featured Match' }}</h5>
                                @if($fm->match_starts_at)
                                <p class="featured-time"><i class="bi bi-clock"></i> {{ $fm->match_starts_at->format('D, M j \\a\\t H:i') }}</p>
                                @endif
                                <span class="btn btn-primary btn-sm"><i class="bi bi-play-fill"></i> Watch Now</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Sport Filter --}}
    <div class="sport-filter-bar mb-4">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-muted small">Filter:</span>
            <a href="{{ route('home') }}" class="sport-pill {{ !$sportId ? 'active' : '' }}">All Sports</a>
            @php $sportsData = $sports['data'] ?? $sports; @endphp
            @foreach((array)$sportsData as $sport)
            <a href="{{ route('home') }}?sport={{ $sport['id'] ?? '' }}"
               class="sport-pill {{ $sportId == ($sport['id'] ?? '') ? 'active' : '' }}">
                {{ $sport['name'] ?? 'Sport' }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- Live Matches --}}
    @php $liveData = $liveMatches['data'] ?? $liveMatches; @endphp
    <section class="matches-section mb-5">
        <div class="section-header d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-title">
                <span class="live-indicator"></span>
                <span class="live-badge-text">LIVE</span> Matches
                @if(!empty($liveData))
                    <span class="badge bg-danger ms-2">{{ count((array)$liveData) }}</span>
                @endif
            </h2>
        </div>
        @if(!empty($liveData))
            <div class="matches-grid">
                @foreach((array)$liveData as $match)
                    @include('partials.match-card', ['match' => $match, 'isLive' => true])
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-broadcast"></i>
                <p>No live matches at the moment. Check back soon!</p>
            </div>
        @endif
    </section>

    {{-- Inline Ad --}}
    <div class="ad-zone ad-in-article my-4">
        {!! app(\App\Services\AdManager::class)->renderSlot('in-article') !!}
    </div>

    {{-- Upcoming Matches --}}
    @php $upcomingData = $upcomingMatches['data'] ?? $upcomingMatches; @endphp
    <section class="matches-section mb-5">
        <div class="section-header d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-title"><i class="bi bi-calendar-event text-info"></i> Upcoming Matches</h2>
        </div>
        @if(!empty($upcomingData))
            <div class="matches-grid">
                @foreach((array)$upcomingData as $match)
                    @include('partials.match-card', ['match' => $match, 'isLive' => false])
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-calendar"></i>
                <p>No upcoming matches scheduled yet.</p>
            </div>
        @endif
    </section>

    {{-- Finished Matches --}}
    @php $finishedData = $finishedMatches['data'] ?? $finishedMatches; @endphp
    <section class="matches-section mb-5">
        <div class="section-header d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-title"><i class="bi bi-check2-circle text-success"></i> Recent Results</h2>
        </div>
        @if(!empty($finishedData))
            <div class="matches-grid">
                @foreach((array)$finishedData as $match)
                    @include('partials.match-card', ['match' => $match, 'isLive' => false])
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-archive"></i>
                <p>No recent results available.</p>
            </div>
        @endif
    </section>

</div>
@endsection

@push('scripts')
<script>
// Auto-refresh live matches every 60 seconds
if (document.querySelector('.live-matches-section')) {
    setInterval(() => {
        fetch(window.location.href)
            .then(r => r.text())
            .then(() => { /* Update only live section via AJAX in production */ });
    }, 60000);
}
</script>
@endpush