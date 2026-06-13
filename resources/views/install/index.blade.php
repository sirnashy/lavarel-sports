@extends('layouts.install')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h3 mb-3">Installation Wizard</h1>
                    <p class="text-muted">Complete the setup below to configure your site for shared hosting. The installer creates your environment file, runs database migrations, and creates the first admin user.</p>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('install.perform') }}">
                        @csrf

                        <div class="mb-4">
                            <h5>Site Configuration</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="app_name">Site Name</label>
                                    <input id="app_name" name="app_name" type="text" class="form-control" value="{{ old('app_name', config('app.name', 'SportStream')) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="app_url">Site URL</label>
                                    <input id="app_url" name="app_url" type="url" class="form-control" value="{{ old('app_url', env('APP_URL', url('/'))) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Database Settings</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="db_host">Database Host</label>
                                    <input id="db_host" name="db_host" type="text" class="form-control" value="{{ old('db_host', env('DB_HOST', '127.0.0.1')) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="db_port">Database Port</label>
                                    <input id="db_port" name="db_port" type="text" class="form-control" value="{{ old('db_port', env('DB_PORT', '3306')) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="db_database">Database Name</label>
                                    <input id="db_database" name="db_database" type="text" class="form-control" value="{{ old('db_database', env('DB_DATABASE', 'laravel')) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="db_username">Database User</label>
                                    <input id="db_username" name="db_username" type="text" class="form-control" value="{{ old('db_username', env('DB_USERNAME', 'root')) }}" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label" for="db_password">Database Password</label>
                                    <input id="db_password" name="db_password" type="password" class="form-control" value="{{ old('db_password', env('DB_PASSWORD')) }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Administrator Account</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="admin_name">Admin Name</label>
                                    <input id="admin_name" name="admin_name" type="text" class="form-control" value="{{ old('admin_name') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="admin_email">Admin Email</label>
                                    <input id="admin_email" name="admin_email" type="email" class="form-control" value="{{ old('admin_email') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="admin_password">Password</label>
                                    <input id="admin_password" name="admin_password" type="password" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="admin_password_confirmation">Confirm Password</label>
                                    <input id="admin_password_confirmation" name="admin_password_confirmation" type="password" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-text text-muted">The installer will write a new <code>.env</code> file, generate your application key, run migrations, and create an administrator account.</div>
                        </div>

                        <button type="submit" class="btn btn-primary">Install Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
