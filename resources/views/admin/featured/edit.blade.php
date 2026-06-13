@extends('layouts.admin')
@section('title', 'Edit Featured Match')

@section('content')
<div class="p-4">
    <div class="admin-card" style="max-width:600px">
        <div class="admin-card__header"><h5>Edit Featured Match</h5></div>
        <div class="admin-card__body">
            <form method="POST" action="{{ route('admin.featured.update', $featured) }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Match ID *</label>
                    <input type="text" class="form-control" name="match_id" value="{{ old('match_id', $featured->match_id) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Display Title</label>
                    <input type="text" class="form-control" name="title" value="{{ old('title', $featured->title) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Kickoff Time</label>
                    <input type="datetime-local" class="form-control" name="match_starts_at"
                           value="{{ old('match_starts_at', $featured->match_starts_at?->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', $featured->sort_order) }}" min="0">
                </div>
                <div class="form-check form-switch mb-3">
                    <input type="hidden" name="is_active" value="0">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ $featured->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.featured.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection