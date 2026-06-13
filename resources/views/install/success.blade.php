@extends('layouts.install')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <h1 class="h3 mb-3">Installation Complete</h1>
                    <p class="text-muted mb-4">Your site has been configured, the database has been migrated, and the administrator account has been created successfully.</p>

                    <div class="mb-4">
                        <a href="{{ route('login') }}" class="btn btn-success me-2">Login to Admin</a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">View Site</a>
                    </div>

                    <p class="small text-muted">If you have trouble logging in, verify the email and password you entered during setup.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
