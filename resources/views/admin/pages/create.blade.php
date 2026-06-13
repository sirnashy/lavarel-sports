@extends('layouts.admin')

@section('title', 'Create Page')

@section('actions')
    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-light">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.pages.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label">Page Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug (Optional)</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" placeholder="e.g. privacy-policy (auto-generated if empty)">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Page Content <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="15" required>{{ old('content') }}</textarea>
                        <div class="form-text text-muted">HTML tags are supported.</div>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4 border-start">
                    <h5 class="mb-3">Publish Settings</h5>
                    
                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Publish (Active)</label>
                    </div>

                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="show_in_nav" name="show_in_nav" value="1" {{ old('show_in_nav') ? 'checked' : '' }}>
                        <label class="form-check-label" for="show_in_nav">Show in Navigation</label>
                    </div>

                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <h5 class="mb-3">SEO Details (Optional)</h5>

                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror" id="meta_title" name="meta_title" value="{{ old('meta_title') }}">
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description" rows="4">{{ old('meta_description') }}</textarea>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-3 text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Save Page
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
