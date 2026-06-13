@extends('layouts.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Match Detail</li>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        {{-- Main Content --}}
        <div class="col-xl-9 col-lg-8">

            {{-- Match Header --}}
            <div class="match-hero mb-4">
                <div class="match-hero__competition">
                    @if(!empty($competition['logo']))
                        <img src="{{ $competition['logo'] }}" alt="{{ $competition['name'] ?? '' }}" class="comp-logo-lg" loading="lazy">
                    @endif
                    <div>
                        <span class="comp-name">{{ $competition['name'] ?? 'Competition' }}</span>
                        @if(!empty($matchData['venue']))
                            <span class="venue-name"><i class="bi bi-geo-alt"></i> {{ $matchData['venue']['name'] ?? $matchData['venue'] }}</span>
                        @endif
                    </div>
                    @php
                        $status = $matchData['status'] ?? 'unknown';
                        $isLive = in_array(strtolower($status), ['live', 'in_progress', 'inprogress', '1h', '2h', 'ht']);
                    @endphp
                    <div class="ms-auto">
                        @if($isLive)
                            <span class="badge-live-lg"><span class="live-dot"></span> LIVE</span>
                        @elseif(strtolower($status) === 'finished')
                            <span class="badge bg-secondary">FT</span>
                        @else
                            <span class="badge bg-info text-dark">{{ strtoupper($status) }}</span>
                        @endif
                    </div>
                </div>

                <div class="match-hero__teams">
                    <div class="team-hero team--home">
                        @if(!empty($homeTeam['logo']))
                            <img src="{{ $homeTeam['logo'] }}" alt="{{ $homeTeam['name'] ?? 'Home' }}" class="team-logo-xl" loading="lazy">
                        @else
                            <div class="team-logo-xl-placeholder"><i class="bi bi-shield-fill"></i></div>
                        @endif
                        <h3 class="team-name-lg">{{ $homeTeam['name'] ?? 'Home Team' }}</h3>
                        <span class="team-country">{{ $homeTeam['country'] ?? '' }}</span>
                    </div>

                    <div class="score-hero">
                        @php
                            $score = $matchData['score'] ?? [];
                        @endphp
                        @if($isLive || strtolower($status) === 'finished')
                            <div class="score-display" id="live-score">
                                {{ $score['home'] ?? $score['home_score'] ?? '0' }}
                                <span class="score-sep">-</span>
                                {{ $score['away'] ?? $score['away_score'] ?? '0' }}
                            </div>
                            @if($isLive)
                                <div class="match-clock" id="match-clock">
                                    {{ $matchData['minute'] ?? $matchData['match_time'] ?? '' }}'
                                </div>
                            @endif
                        @else
                            <div class="kickoff-display">
                                @if(!empty($matchData['start_time']))
                                    <div class="kickoff-date">{{ \Carbon\Carbon::parse($matchData['start_time'])->format('D, M j') }}</div>
                                    <div class="kickoff-time-lg">{{ \Carbon\Carbon::parse($matchData['start_time'])->format('H:i') }}</div>
                                @else
                                    <div class="kickoff-time-lg">TBD</div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="team-hero team--away">
                        @if(!empty($awayTeam['logo']))
                            <img src="{{ $awayTeam['logo'] }}" alt="{{ $awayTeam['name'] ?? 'Away' }}" class="team-logo-xl" loading="lazy">
                        @else
                            <div class="team-logo-xl-placeholder"><i class="bi bi-shield-fill"></i></div>
                        @endif
                        <h3 class="team-name-lg">{{ $awayTeam['name'] ?? 'Away Team' }}</h3>
                        <span class="team-country">{{ $awayTeam['country'] ?? '' }}</span>
                    </div>
                </div>

                {{-- Watch Button --}}
                @if(!empty($matchData['streams']))
                <div class="text-center mt-3">
                    <a href="{{ route('match.stream', $matchId) }}" class="btn btn-danger btn-lg watch-btn">
                        <i class="bi bi-play-circle-fill"></i> Watch Live Stream
                    </a>
                </div>
                @endif
            </div>

            {{-- Tabs --}}
            <ul class="nav nav-tabs match-tabs mb-3" id="matchTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#tab-events"><i class="bi bi-lightning"></i> Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab-stats"><i class="bi bi-bar-chart"></i> Stats</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab-lineups"><i class="bi bi-people"></i> Lineups</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab-standings"><i class="bi bi-list-ol"></i> Standings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab-h2h"><i class="bi bi-arrow-left-right"></i> H2H</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab-highlights"><i class="bi bi-play-btn"></i> Highlights</a>
                </li>
            </ul>

            <div class="tab-content match-tab-content">
                {{-- Events Tab --}}
                <div class="tab-pane fade show active" id="tab-events">
                    <div id="events-container" class="tab-loading">
                        <div class="loading-spinner"><div class="spinner-border text-primary"></div><p>Loading events...</p></div>
                    </div>
                </div>

                {{-- Stats Tab --}}
                <div class="tab-pane fade" id="tab-stats">
                    <div id="stats-container" class="tab-loading">
                        <div class="loading-spinner"><div class="spinner-border text-primary"></div><p>Loading stats...</p></div>
                    </div>
                </div>

                {{-- Lineups Tab --}}
                <div class="tab-pane fade" id="tab-lineups">
                    <div id="lineups-container" class="tab-loading">
                        <div class="loading-spinner"><div class="spinner-border text-primary"></div><p>Loading lineups...</p></div>
                    </div>
                </div>

                {{-- Standings Tab --}}
                <div class="tab-pane fade" id="tab-standings">
                    <div id="standings-container" class="tab-loading">
                        <div class="loading-spinner"><div class="spinner-border text-primary"></div><p>Loading standings...</p></div>
                    </div>
                </div>

                {{-- H2H Tab --}}
                <div class="tab-pane fade" id="tab-h2h">
                    <div id="h2h-container" class="tab-loading">
                        <div class="loading-spinner"><div class="spinner-border text-primary"></div><p>Loading head-to-head...</p></div>
                    </div>
                </div>

                {{-- Highlights Tab --}}
                <div class="tab-pane fade" id="tab-highlights">
                    <div id="highlights-container" class="tab-loading">
                        <div class="loading-spinner"><div class="spinner-border text-primary"></div><p>Loading highlights...</p></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-xl-3 col-lg-4">
            {{-- Sidebar Ad --}}
            <div class="ad-zone ad-sidebar mb-4">
                {!! app(\App\Services\AdManager::class)->renderSlot('sidebar') !!}
            </div>

            {{-- Match Info Card --}}
            <div class="info-card mb-4">
                <h6 class="info-card__title"><i class="bi bi-info-circle"></i> Match Info</h6>
                <div class="info-list">
                    @if(!empty($matchData['venue']))
                    <div class="info-item">
                        <span class="info-label">Venue</span>
                        <span class="info-value">{{ $matchData['venue']['name'] ?? $matchData['venue'] }}</span>
                    </div>
                    @endif
                    @if(!empty($matchData['referee']))
                    <div class="info-item">
                        <span class="info-label">Referee</span>
                        <span class="info-value">{{ $matchData['referee'] }}</span>
                    </div>
                    @endif
                    @if(!empty($matchData['start_time']))
                    <div class="info-item">
                        <span class="info-label">Kickoff</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($matchData['start_time'])->format('M j, Y H:i') }}</span>
                    </div>
                    @endif
                    @if(!empty($competition['country']))
                    <div class="info-item">
                        <span class="info-label">Country</span>
                        <span class="info-value">{{ $competition['country'] }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const MATCH_ID = '{{ $matchId }}';
const IS_LIVE = {{ $isLive ? 'true' : 'false' }};

const API = {
    live: `/api/match/${MATCH_ID}/live`,
    standings: `/api/match/${MATCH_ID}/standings`,
    h2h: `/api/match/${MATCH_ID}/h2h`,
    highlights: `/api/match/${MATCH_ID}/highlights`,
};

function fetchAndRender(url, containerId, renderer) {
    fetch(url)
        .then(r => r.json())
        .then(data => {
            document.getElementById(containerId).innerHTML = renderer(data);
        })
        .catch(() => {
            document.getElementById(containerId).innerHTML = '<div class="text-center text-muted py-4"><i class="bi bi-wifi-off"></i> Failed to load data.</div>';
        });
}

// Render Events
function renderEvents(data) {
    const incidents = data.incidents?.data ?? data.incidents ?? [];
    if (!incidents.length) return '<div class="text-center text-muted py-5"><i class="bi bi-lightning display-4"></i><p>No events yet.</p></div>';
    return `<div class="events-timeline">${incidents.map(i => `
        <div class="event-item event-item--${(i.type||'').toLowerCase()}">
            <div class="event-time">${i.minute ?? i.time ?? ''}${i.extra_time ? '+'+i.extra_time : ''}'</div>
            <div class="event-icon">${getEventIcon(i.type)}</div>
            <div class="event-detail">
                <strong>${i.player?.name ?? i.player_name ?? ''}</strong>
                ${i.assist?.name ? `<small class="text-muted">(${i.assist.name})</small>` : ''}
                <small>${i.type ?? ''}</small>
            </div>
            <div class="event-team">${i.team === 'home' ? '⬅' : '➡'}</div>
        </div>`).join('')}</div>`;
}

function getEventIcon(type) {
    const icons = { goal: '⚽', 'yellow_card': '🟨', 'red_card': '🟥', 'substitution': '🔄', 'penalty': '⚽🎯' };
    return icons[(type||'').toLowerCase()] ?? '•';
}

// Render Stats
function renderStats(data) {
    const stats = data.stats?.data ?? data.stats ?? [];
    if (!stats.length) return '<div class="text-center text-muted py-5"><i class="bi bi-bar-chart display-4"></i><p>No stats available.</p></div>';
    return `<div class="stats-list">${stats.map(s => `
        <div class="stat-row">
            <span class="stat-home">${s.home ?? 0}</span>
            <div class="stat-bar-wrap">
                <span class="stat-name">${s.name ?? s.type ?? ''}</span>
                <div class="stat-bar">
                    <div class="stat-bar__home" style="width:${calcPct(s.home, s.away)}%"></div>
                    <div class="stat-bar__away" style="width:${calcPct(s.away, s.home)}%"></div>
                </div>
            </div>
            <span class="stat-away">${s.away ?? 0}</span>
        </div>`).join('')}</div>`;
}

function calcPct(a, b) {
    const total = (parseFloat(a)||0) + (parseFloat(b)||0);
    return total > 0 ? Math.round((parseFloat(a)||0) / total * 100) : 50;
}

// Render Lineups
function renderLineups(data) {
    const lineups = data.lineups?.data ?? data.lineups ?? {};
    if (!lineups || (!lineups.home && !lineups.away)) {
        return '<div class="text-center text-muted py-5"><i class="bi bi-people display-4"></i><p>Lineups not available.</p></div>';
    }
    const renderTeam = (team, label) => `
        <div class="lineup-team">
            <h6 class="lineup-label">${label}</h6>
            <div class="lineup-formation">${team.formation ?? ''}</div>
            <ul class="lineup-list">
                ${(team.players ?? []).map(p => `
                <li class="lineup-player">
                    <span class="player-number">${p.number ?? p.jersey_number ?? ''}</span>
                    <span class="player-name">${p.name ?? p.player?.name ?? ''}</span>
                    <span class="player-position text-muted small">${p.position ?? ''}</span>
                </li>`).join('')}
            </ul>
        </div>`;
    return `<div class="lineup-grid">
        ${renderTeam(lineups.home ?? {}, 'Home')}
        ${renderTeam(lineups.away ?? {}, 'Away')}
    </div>`;
}

// Render Standings
function renderStandings(data) {
    const rows = data.standing?.data ?? data.standings?.data ?? data.data ?? [];
    if (!rows.length) return '<div class="text-center text-muted py-5"><i class="bi bi-list-ol display-4"></i><p>Standings not available.</p></div>';
    return `<div class="table-responsive">
        <table class="table table-dark table-hover standings-table">
            <thead><tr><th>#</th><th>Team</th><th>P</th><th>W</th><th>D</th><th>L</th><th>GD</th><th>Pts</th></tr></thead>
            <tbody>${rows.map(r => `
            <tr>
                <td>${r.position ?? r.rank ?? ''}</td>
                <td class="team-cell">
                    ${r.team?.logo ? `<img src="${r.team.logo}" class="mini-logo" loading="lazy">` : ''}
                    ${r.team?.name ?? r.team_name ?? ''}
                </td>
                <td>${r.played ?? r.matches_played ?? ''}</td>
                <td>${r.won ?? r.wins ?? ''}</td>
                <td>${r.drawn ?? r.draws ?? ''}</td>
                <td>${r.lost ?? r.losses ?? ''}</td>
                <td>${r.goal_difference ?? r.gd ?? ''}</td>
                <td class="fw-bold">${r.points ?? r.pts ?? ''}</td>
            </tr>`).join('')}</tbody>
        </table>
    </div>`;
}

// Render H2H
function renderH2H(data) {
    const matches = data.h2h?.data ?? data.data ?? [];
    if (!matches.length) return '<div class="text-center text-muted py-5"><i class="bi bi-arrow-left-right display-4"></i><p>No previous meetings found.</p></div>';
    return `<div class="h2h-list">${matches.slice(0,10).map(m => `
        <div class="h2h-item">
            <span class="h2h-date">${m.date ? new Date(m.date).toLocaleDateString() : ''}</span>
            <div class="h2h-teams">
                <span class="h2h-team">${m.home_team?.name ?? ''}</span>
                <span class="h2h-score">${m.score?.home ?? 0} - ${m.score?.away ?? 0}</span>
                <span class="h2h-team">${m.away_team?.name ?? ''}</span>
            </div>
            <span class="h2h-competition text-muted small">${m.competition?.name ?? ''}</span>
        </div>`).join('')}</div>`;
}

// Render Highlights
function renderHighlights(data) {
    const videos = data.highlights?.data ?? data.data ?? [];
    if (!videos.length) return '<div class="text-center text-muted py-5"><i class="bi bi-play-btn display-4"></i><p>No highlights available yet.</p></div>';
    return `<div class="highlights-grid">${videos.map(v => `
        <div class="highlight-card">
            ${v.thumbnail ? `<img src="${v.thumbnail}" class="highlight-thumb" loading="lazy">` : '<div class="highlight-thumb-ph"><i class="bi bi-play-circle display-3"></i></div>'}
            <div class="highlight-info">
                <p class="highlight-title">${v.title ?? 'Highlight'}</p>
                <a href="${v.url ?? v.embed_url ?? '#'}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-play-fill"></i> Watch
                </a>
            </div>
        </div>`).join('')}</div>`;
}

// Load initial data
fetchAndRender(API.live, 'events-container', data => renderEvents(data));
fetchAndRender(API.live, 'stats-container', data => renderStats(data));
fetchAndRender(API.live, 'lineups-container', data => renderLineups(data));

// Load on tab click (lazy)
const tabsLoaded = {};
document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
    tab.addEventListener('shown.bs.tab', e => {
        const target = e.target.getAttribute('href');
        if (tabsLoaded[target]) return;
        tabsLoaded[target] = true;

        if (target === '#tab-standings') fetchAndRender(API.standings, 'standings-container', renderStandings);
        if (target === '#tab-h2h') fetchAndRender(API.h2h, 'h2h-container', renderH2H);
        if (target === '#tab-highlights') fetchAndRender(API.highlights, 'highlights-container', renderHighlights);
    });
});

// Live refresh
if (IS_LIVE) {
    setInterval(() => {
        fetchAndRender(API.live, 'events-container', renderEvents);
        fetchAndRender(API.live, 'stats-container', renderStats);
    }, 30000);
}
</script>
@endpush