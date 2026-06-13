@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="p-4">
    {{-- Stats Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card stat-card--blue">
                <div class="stat-icon"><i class="bi bi-eye"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ number_format($todayVisitors) }}</div>
                    <div class="stat-label">Today's Visitors</div>
                    <div class="stat-sub">{{ number_format($weekVisitors) }} this week</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card stat-card--red">
                <div class="stat-icon"><i class="bi bi-broadcast"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ number_format($todayStreams) }}</div>
                    <div class="stat-label">Stream Views Today</div>
                    <div class="stat-sub">Unique sessions</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card stat-card--green">
                <div class="stat-icon"><i class="bi bi-cloud"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ number_format($apiUsage) }}</div>
                    <div class="stat-label">API Calls Today</div>
                    <div class="stat-sub">SportSRC requests</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card stat-card--purple">
                <div class="stat-icon"><i class="bi bi-trophy"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ $popularMatches->count() }}</div>
                    <div class="stat-label">Popular Matches</div>
                    <div class="stat-sub">Streamed today</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Visitor Chart --}}
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card__header">
                    <h5><i class="bi bi-graph-up"></i> Visitor Trend (30 days)</h5>
                </div>
                <div class="admin-card__body">
                    <canvas id="visitorChart" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- Popular Matches --}}
        <div class="col-lg-4">
            <div class="admin-card">
                <div class="admin-card__header">
                    <h5><i class="bi bi-fire"></i> Popular Matches</h5>
                </div>
                <div class="admin-card__body p-0">
                    @forelse($popularMatches as $pm)
                        <div class="popular-item">
                            <span class="popular-id text-muted small">{{ $pm->match_id }}</span>
                            <span class="badge bg-primary">{{ $pm->views }} views</span>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">No stream views today</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="col-12">
            <div class="admin-card">
                <div class="admin-card__header d-flex justify-content-between">
                    <h5><i class="bi bi-journal-text"></i> Recent Activity</h5>
                    <a href="{{ route('admin.logs.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                </div>
                <div class="admin-card__body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead><tr><th>User</th><th>Action</th><th>Description</th><th>IP</th><th>Time</th></tr></thead>
                            <tbody>
                                @forelse($recentLogs as $log)
                                <tr>
                                    <td>{{ $log->user?->name ?? 'System' }}</td>
                                    <td><span class="badge bg-{{ $log->action === 'deleted' ? 'danger' : ($log->action === 'created' ? 'success' : 'info') }}">{{ $log->action }}</span></td>
                                    <td>{{ $log->description }}</td>
                                    <td><code>{{ $log->ip_address }}</code></td>
                                    <td>{{ $log->created_at->diffForHumans() }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">No activity yet</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('visitorChart').getContext('2d');
const chartData = @json($visitorChart);
new Chart(ctx, {
    type: 'line',
    data: {
        labels: chartData.map(d => d.date),
        datasets: [{
            label: 'Visitors',
            data: chartData.map(d => d.count),
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13,110,253,0.1)',
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { labels: { color: '#adb5bd' } } },
        scales: {
            x: { ticks: { color: '#adb5bd' }, grid: { color: 'rgba(255,255,255,0.05)' } },
            y: { ticks: { color: '#adb5bd' }, grid: { color: 'rgba(255,255,255,0.05)' } },
        }
    }
});
</script>
@endpush