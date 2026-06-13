@extends('layouts.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Search</li>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <h1 class="page-title mb-4"><i class="bi bi-search"></i> Search Matches</h1>

    {{-- Search Form --}}
    <form action="{{ route('search') }}" method="GET" class="search-form mb-5">
        <div class="input-group input-group-lg">
            <input type="search" class="form-control search-input-lg" name="q"
                   value="{{ $query }}" placeholder="Search for teams, matches, competitions..."
                   autofocus>
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-search"></i> Search
            </button>
        </div>
    </form>

    {{-- Results --}}
    @if($query)
        @php $resultData = $results['data'] ?? $results; @endphp
        <h2 class="section-title mb-3">
            Results for "{{ $query }}"
            <span class="badge bg-secondary ms-2">{{ count((array)$resultData) }}</span>
        </h2>

        @if(!empty($resultData))
            <div class="matches-grid">
                @foreach((array)$resultData as $match)
                    @php
                        $status = strtolower($match['status'] ?? '');
                        $isLive = in_array($status, ['live', 'in_progress', '1h', '2h', 'ht']);
                    @endphp
                    @include('partials.match-card', ['match' => $match, 'isLive' => $isLive])
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-search display-1"></i>
                <h3>No results found</h3>
                <p class="text-muted">Try a different search term. For example, search for a team name or competition.</p>
            </div>
        @endif
    @else
        <div class="search-suggestions">
            <h5 class="text-muted mb-3">Popular Sports</h5>
            <div class="d-flex flex-wrap gap-2">
                @php $sportsData = $sports['data'] ?? $sports; @endphp
                @foreach((array)$sportsData as $sport)
                    <a href="{{ route('home') }}?sport={{ $sport['id'] ?? '' }}" class="sport-pill">
                        {{ $sport['name'] ?? 'Sport' }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection