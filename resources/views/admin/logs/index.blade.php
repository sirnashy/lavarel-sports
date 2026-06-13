@extends('layouts.admin')
@section('title', 'Activity Logs')

@section('content')
<div class="p-4">
    <div class="admin-card">
        <div class="admin-card__body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 small">
                    <thead><tr><th>User</th><th>Action</th><th>Description</th><th>Subject</th><th>IP</th><th>Time</th></tr></thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->user?->name ?? 'System' }}</td>
                            <td>
                                <span class="badge bg-{{ $log->action === 'deleted' ? 'danger' : ($log->action === 'created' ? 'success' : 'info') }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td>{{ $log->description }}</td>
                            <td class="text-muted">{{ class_basename($log->subject_type ?? '') }} #{{ $log->subject_id }}</td>
                            <td><code>{{ $log->ip_address }}</code></td>
                            <td>{{ $log->created_at->format('M j, H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No activity recorded.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($logs->hasPages())
        <div class="admin-card__footer">{{ $logs->links() }}</div>
        @endif
    </div>
</div>
@endsection