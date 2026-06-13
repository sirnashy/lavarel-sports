@extends('layouts.admin')
@section('title', 'Add Featured Match')

@section('content')
<div class="p-4">
    <div class="admin-card" style="max-width:600px">
        <div class="admin-card__header"><h5>Add Featured Match</h5></div>
        <div class="admin-card__body">
            <form method="POST" action="{{ route('admin.featured.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Match ID (from SportSRC) *</label>
                    <input type="text" class="form-control" name="match_id" value="{{ old('match_id') }}" required>
                    <div class="form-text">The match ID from the SportSRC API.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Display Title</label>
                    <input type="text" class="form-control" name="title" value="{{ old('title') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Kickoff Time</label>
                    <input type="datetime-local" class="form-control" name="match_starts_at" value="{{ old('match_starts_at') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                </div>
                <div class="form-check form-switch mb-3">
                    <input type="hidden" name="is_active" value="0">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('admin.featured.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection