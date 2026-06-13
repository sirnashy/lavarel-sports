<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Ad Name *</label>
        <input type="text" class="form-control" name="name" value="{{ old('name', $ad->name ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Slot *</label>
        <select class="form-select" name="slot_key" required>
            @foreach($slots as $key => $label)
                <option value="{{ $key }}" {{ old('slot_key', $ad->slot_key ?? '') === $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Position</label>
        <select class="form-select" name="position">
            <option value="any" {{ old('position', $ad->position ?? 'any') === 'any' ? 'selected' : '' }}>Any</option>
            <option value="desktop" {{ old('position', $ad->position ?? '') === 'desktop' ? 'selected' : '' }}>Desktop Only</option>
            <option value="mobile" {{ old('position', $ad->position ?? '') === 'mobile' ? 'selected' : '' }}>Mobile Only</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Sort Order</label>
        <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', $ad->sort_order ?? 0) }}" min="0">
    </div>
    <div class="col-md-4">
        <label class="form-label">Active</label>
        <div class="form-check form-switch mt-2">
            <input type="hidden" name="is_active" value="0">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                   id="is_active" {{ old('is_active', $ad->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Enabled</label>
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Start Date (optional)</label>
        <input type="datetime-local" class="form-control" name="starts_at"
               value="{{ old('starts_at', isset($ad) ? $ad->starts_at?->format('Y-m-d\TH:i') : '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">End Date (optional)</label>
        <input type="datetime-local" class="form-control" name="ends_at"
               value="{{ old('ends_at', isset($ad) ? $ad->ends_at?->format('Y-m-d\TH:i') : '') }}">
    </div>
    <div class="col-12">
        <label class="form-label">Ad Code (HTML/JS/AdSense) *</label>
        <textarea class="form-control font-monospace" name="code" rows="8" required
                  placeholder="Paste your ad code here...">{{ old('code', $ad->code ?? '') }}</textarea>
        <div class="form-text">Paste raw HTML, JavaScript, or Google AdSense code.</div>
    </div>
</div>