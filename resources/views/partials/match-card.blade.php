@php
    $homeTeam = $match['home_team'] ?? [];
    $awayTeam = $match['away_team'] ?? [];
    $score = $match['score'] ?? [];
    $competition = $match['competition'] ?? $match['tournament'] ?? [];
    $status = $match['status'] ?? 'unknown';
    $matchId = $match['id'] ?? $match['match_id'] ?? '';
    $startTime = $match['start_time'] ?? $match['kickoff'] ?? null;
@endphp
@if(!empty($matchId))
<a href="{{ route('match.detail', ['matchId' => $matchId]) }}" class="match-card-link">
@endif
    <div class="match-card {{ $isLive ? 'match-card--live' : '' }}">
        {{-- Competition Header --}}
        <div class="match-card__competition">
            @if(!empty($competition['logo']))
                <img src="{{ $competition['logo'] }}" alt="{{ $competition['name'] ?? '' }}" class="competition-logo" loading="lazy">
            @else
                <i class="bi bi-trophy-fill"></i>
            @endif
            <span>{{ $competition['name'] ?? 'Competition' }}</span>
            @if($isLive)
                <span class="ms-auto live-tag"><span class="live-dot-sm"></span> LIVE</span>
            @elseif($startTime)
                <span class="ms-auto text-muted small">{{ \Carbon\Carbon::parse($startTime)->format('D H:i') }}</span>
            @endif
        </div>

        {{-- Teams & Score --}}
        <div class="match-card__body">
            <div class="team team--home">
                @if(!empty($homeTeam['logo']))
                    <img src="{{ $homeTeam['logo'] }}" alt="{{ $homeTeam['name'] ?? 'Home' }}" class="team-logo" loading="lazy">
                @else
                    <div class="team-logo-placeholder"><i class="bi bi-shield-fill"></i></div>
                @endif
                <span class="team-name">{{ $homeTeam['name'] ?? $homeTeam['short_name'] ?? 'Home Team' }}</span>
            </div>

            <div class="score-box">
                @if($isLive || $status === 'finished')
                    <div class="score">
                        <span class="score__home">{{ $score['home'] ?? $score['home_score'] ?? '0' }}</span>
                        <span class="score__sep">-</span>
                        <span class="score__away">{{ $score['away'] ?? $score['away_score'] ?? '0' }}</span>
                    </div>
                    @if($isLive)
                        <div class="match-minute text-danger">{{ $match['minute'] ?? $match['match_time'] ?? "'" }}</div>
                    @endif
                @else
                    <div class="vs-text">VS</div>
                    @if($startTime)
                        <div class="kickoff-time">{{ \Carbon\Carbon::parse($startTime)->format('H:i') }}</div>
                    @endif
                @endif
            </div>

            <div class="team team--away">
                @if(!empty($awayTeam['logo']))
                    <img src="{{ $awayTeam['logo'] }}" alt="{{ $awayTeam['name'] ?? 'Away' }}" class="team-logo" loading="lazy">
                @else
                    <div class="team-logo-placeholder"><i class="bi bi-shield-fill"></i></div>
                @endif
                <span class="team-name">{{ $awayTeam['name'] ?? $awayTeam['short_name'] ?? 'Away Team' }}</span>
            </div>
        </div>

        {{-- Footer --}}
        <div class="match-card__footer">
            <span class="btn-watch {{ $isLive ? 'btn-watch--live' : '' }}">
                @if($isLive)
                    <i class="bi bi-broadcast"></i> Watch Live
                @else
                    <i class="bi bi-info-circle"></i> Details
                @endif
            </span>
        </div>
    </div>
@if(!empty($matchId))
</a>
@endif