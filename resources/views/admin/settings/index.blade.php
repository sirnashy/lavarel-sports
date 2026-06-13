@extends('layouts.admin')

@section('title', 'Global Site Settings')

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        {{-- Navigation Tabs --}}
        <div class="list-group shadow-sm" id="settings-tabs" role="tablist">
            @foreach($settings as $group => $items)
                <button class="list-group-item list-group-item-action text-capitalize {{ $loop->first ? 'active' : '' }}" 
                    id="tab-{{ $group }}" 
                    data-bs-toggle="list" 
                    href="#group-{{ $group }}" 
                    role="tab">
                    <i class="bi bi-folder-symlink me-2"></i> {{ str_replace('_', ' ', $group) }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf

                    <div class="tab-content" id="settings-tabContent">
                        @foreach($settings as $group => $items)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                id="group-{{ $group }}" 
                                role="tabpanel">
                                
                                <h4 class="mb-4 text-capitalize border-bottom pb-2">{{ str_replace('_', ' ', $group) }} Settings</h4>
                                
                                @foreach($items as $setting)
                                    <div class="mb-3">
                                        <label for="setting-{{ $setting->key }}" class="form-label">
                                            {{ $setting->label }}
                                        </label>
                                        
                                        @if($setting->type === 'textarea')
                                            <textarea class="form-control" 
                                                id="setting-{{ $setting->key }}" 
                                                name="{{ $setting->key }}" 
                                                rows="5">{{ old($setting->key, $setting->value) }}</textarea>
                                        
                                        @elseif($setting->type === 'boolean')
                                            <div class="form-check form-switch">
                                                <input type="hidden" name="{{ $setting->key }}" value="0">
                                                <input class="form-check-input" 
                                                    type="checkbox" 
                                                    id="setting-{{ $setting->key }}" 
                                                    name="{{ $setting->key }}" 
                                                    value="1" 
                                                    {{ old($setting->key, $setting->value) == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label text-muted" for="setting-{{ $setting->key }}">Enable / Disable</label>
                                            </div>
                                            
                                        @elseif($setting->type === 'json')
                                            <textarea class="form-control" 
                                                id="setting-{{ $setting->key }}" 
                                                name="{{ $setting->key }}" 
                                                rows="6" 
                                                placeholder="Enter JSON format">{{ old($setting->key, $setting->value) }}</textarea>
                                            <div class="form-text text-muted">Must be valid JSON formatting.</div>

                                        @else {{-- Default: text / image --}}
                                            <input type="text" 
                                                class="form-control" 
                                                id="setting-{{ $setting->key }}" 
                                                name="{{ $setting->key }}" 
                                                value="{{ old($setting->key, $setting->value) }}">
                                        @endif
                                        
                                        @error($setting->key)
                                            <div class="text-danger mt-1 small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                    <hr class="mt-4">

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-save"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
