@extends('layouts.admin')

@section('title', 'SEO Settings')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title mb-3">SEO Template Management</h5>
        <p class="text-muted">
            You can configure global SEO metadata templates below. You can use dynamic variables which will be replaced on runtime:<br>
            <code>@{{ site_name }}</code>, <code>@{{ title }}</code>, <code>@{{ team_home }}</code>, <code>@{{ team_away }}</code>, <code>@{{ competition }}</code>, <code>@{{ query }}</code>
        </p>
    </div>
</div>

<form action="{{ route('admin.settings.seo.update') }}" method="POST">
    @csrf

    <div class="row">
        @foreach($pageKeys as $key)
            @php
                $seo = $seoSettings->get($key);
            @endphp
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-dark d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 text-capitalize">{{ $key }} Page SEO Template</h6>
                        <span class="badge bg-secondary">Page Key: {{ $key }}</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="seo-{{ $key }}-title" class="form-label">Title Template</label>
                            <input type="text" 
                                class="form-control" 
                                id="seo-{{ $key }}-title" 
                                name="seo[{{ $key }}][meta_title_template]" 
                                value="{{ old("seo.$key.meta_title_template", $seo->meta_title_template ?? '') }}" 
                                placeholder="e.g. Watch @{{ title }} Live Stream | @{{ site_name }}">
                        </div>

                        <div class="mb-3">
                            <label for="seo-{{ $key }}-desc" class="form-label">Description Template</label>
                            <textarea class="form-control" 
                                id="seo-{{ $key }}-desc" 
                                name="seo[{{ $key }}][meta_description_template]" 
                                rows="3" 
                                placeholder="e.g. Watch @{{ team_home }} vs @{{ team_away }} live stream on @{{ site_name }}. HD coverage, no signups.">{{ old("seo.$key.meta_description_template", $seo->meta_description_template ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="seo-{{ $key }}-twitter" class="form-label">Twitter Card Type</label>
                            <select class="form-select" id="seo-{{ $key }}-twitter" name="seo[{{ $key }}][twitter_card]">
                                <option value="summary_large_image" {{ old("seo.$key.twitter_card", $seo->twitter_card ?? '') == 'summary_large_image' ? 'selected' : '' }}>Summary with Large Image</option>
                                <option value="summary" {{ old("seo.$key.twitter_card", $seo->twitter_card ?? '') == 'summary' ? 'selected' : '' }}>Summary</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body text-end">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-save"></i> Save SEO Templates
            </button>
        </div>
    </div>
</form>
@endsection
