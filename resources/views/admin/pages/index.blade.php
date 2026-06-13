@extends('layouts.admin')

@section('title', 'Pages Management')

@section('actions')
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Create Page
    </a>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">Order</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Show in Nav</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th class="text-end" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td>
                                <span class="badge bg-secondary">{{ $page->sort_order }}</span>
                            </td>
                            <td>
                                <strong>{{ $page->title }}</strong>
                            </td>
                            <td>
                                <code>/page/{{ $page->slug }}</code>
                            </td>
                            <td>
                                @if($page->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Draft</span>
                                @endif
                            </td>
                            <td>
                                @if($page->show_in_nav)
                                    <span class="badge bg-info">Yes</span>
                                @else
                                    <span class="badge bg-light text-dark">No</span>
                                @endif
                            </td>
                            <td>{{ $page->creator->name ?? 'System' }}</td>
                            <td>{{ $page->created_at->format('Y-m-d H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-light me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this page?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">No pages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-3">
            {{ $pages->links() }}
        </div>
    </div>
</div>
@endsection
