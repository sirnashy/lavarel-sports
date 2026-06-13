@extends('layouts.admin')
@section('title', 'Featured Matches')

@section('actions')
    <a href="{{ route('admin.featured.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> Add Featured
    </a>
@endsection

@section('content')
<div class="p-4">
    <div class="admin-card">
        <div class="admin-card__body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead><tr><th>#</th><th>Match ID</th><th>Title</th><th>Kickoff</th><th>Status</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($featured as $fm)
                        <tr>
                            <td>{{ $fm->sort_order }}</td>
                            <td><code>{{ $fm->match_id }}</code></td>
                            <td>{{ $fm->title ?? '—' }}</td>
                            <td>{{ $fm->match_starts_at?->format('M j, Y H:i') ?? '—' }}</td>
                            <td>
                                @if($fm->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.featured.edit', $fm) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('admin.featured.destroy', $fm) }}">@csrf @method('DELETE')
                                        <button class="btn btn-outline-danger" onclick="return confirm('Remove this match?')"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No featured matches.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection