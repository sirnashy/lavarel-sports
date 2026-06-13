@extends('layouts.admin')
@section('title', 'Edit Advertisement')

@section('content')
<div class="p-4">
    <div class="admin-card" style="max-width:800px">
        <div class="admin-card__header">
            <h5><i class="bi bi-pencil"></i> Edit Advertisement</h5>
        </div>
        <div class="admin-card__body">
            <form method="POST" action="{{ route('admin.advertisements.update', $advertisement) }}">
                @csrf @method('PUT')
                @include('admin.advertisements._form', ['slots' => $slots, 'ad' => $advertisement])
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Update Advertisement</button>
                    <a href="{{ route('admin.advertisements.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection