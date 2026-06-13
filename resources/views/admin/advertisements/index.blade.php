@extends('layouts.admin')
@section('title', 'Advertisements')

@section('actions')
    <a href="{{ route('admin.advertisements.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> New Ad
    </a>
@endsection

@section('content')
<div class="p-4">
    <div class="admin-card">
        <div class="admin-card__header">
            <h5><i class="bi bi-megaphone"></i> Advertisement Management</h5>
        </div>
        <div class="admin-card__body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Name</th><th>Slot</th><th>Position</th>
                            <th>Schedule</th><th>Impressions</th><th>Status</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ads as $ad)
                        <tr>
                            <td>{{ $ad->name }}</td>
                            <td><span class="badge bg-secondary">{{ $slots[$ad->slot_key] ?? $ad->slot_key }}</span></td>
                            <td>{{ $ad->position }}</td>
                            <td class="small">
                                @if($ad->starts_at || $ad->ends_at)
                                    {{ $ad->starts_at?->format('M j') ?? '∞' }} → {{ $ad->ends_at?->format('M j') ?? '∞' }}
                                @else
                                    Always
                                @endif
                            </td>
                            <td>{{ number_format($ad->impressions) }}</td>
                            <td>
                                @if($ad->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.advertisements.edit', $ad) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('admin.advertisements.toggle', $ad) }}">@csrf
                                        <button class="btn btn-outline-{{ $ad->is_active ? 'warning' : 'success' }}" title="Toggle">
                                            <i class="bi bi-toggle-{{ $ad->is_active ? 'on' : 'off' }}"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.advertisements.destroy', $ad) }}">@csrf @method('DELETE')
                                        <button class="btn btn-outline-danger" onclick="return confirm('Delete this ad?')"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No advertisements yet. <a href="{{ route('admin.advertisements.create') }}">Create one</a></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($ads->hasPages())
        <div class="admin-card__footer">
            {{ $ads->links() }}
        </div>
        @endif
    </div>
</div>
@endsection