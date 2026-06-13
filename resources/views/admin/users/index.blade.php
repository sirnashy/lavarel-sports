@extends('layouts.admin')
@section('title', 'Users')

@section('content')
<div class="p-4">
    <div class="admin-card">
        <div class="admin-card__body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Last Login</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->is_admin)
                                    <span class="badge bg-danger">Admin</span>
                                @else
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-secondary">{{ $role->display_name }}</span>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $user->last_login_at?->diffForHumans() ?? 'Never' }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}">@csrf @method('DELETE')
                                        <button class="btn btn-outline-danger" onclick="return confirm('Delete user?')"><i class="bi bi-trash"></i></button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4">No users found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
        <div class="admin-card__footer">{{ $users->links() }}</div>
        @endif
    </div>
</div>
@endsection