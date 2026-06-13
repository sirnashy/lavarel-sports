@extends('layouts.admin')
@section('title', 'Create Advertisement')

@section('content')
<div class="p-4">
    <div class="admin-card" style="max-width:800px">
        <div class="admin-card__header">
            <h5><i class="bi bi-plus-circle"></i> Create Advertisement</h5>
        </div>
        <div class="admin-card__body">
            <form method="POST" action="{{ route('admin.advertisements.store') }}">
                @csrf
                @include('admin.advertisements._form', ['slots' => $slots])
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Save Advertisement</button>
                    <a href="{{ route('admin.advertisements.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection