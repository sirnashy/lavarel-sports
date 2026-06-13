@extends('layouts.admin')

@section('title', 'SportSRC API Debug')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row gx-4 gy-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>SportSRC API Debug</h4>
                    <p class="text-muted">Displays API status, cache response payloads, usage, and recent errors.</p>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-header">API Key Status</div>
                <div class="card-body">
                    <p><strong>Status:</strong>
                        @if(!empty($account['data']) || !empty($account['status']))
                            <span class="badge bg-success">Valid</span>
                        @else
                            <span class="badge bg-danger">Invalid / Missing</span>
                        @endif
                    </p>
                    <p><strong>Usage Today:</strong> {{ $usage }}</p>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-header">Account Response</div>
                <div class="card-body">
                    <pre class="small text-muted">{{ json_encode($account, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12">
            <div class="card">
                <div class="card-header">Categories Response</div>
                <div class="card-body">
                    <pre class="small text-muted">{{ json_encode($sports, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">Live Matches Response</div>
                <div class="card-body">
                    <pre class="small text-muted">{{ json_encode($matches, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">Last API Errors</div>
                <div class="card-body">
                    @if(empty($errors))
                        <p class="text-muted">No recent errors logged.</p>
                    @else
                        <ul class="list-unstyled">
                            @foreach($errors as $error)
                                <li class="mb-3">
                                    <div><strong>{{ $error['timestamp'] }}</strong></div>
                                    <div>{{ $error['message'] }}</div>
                                    <div class="text-muted small">Status: {{ $error['status'] ?? 'n/a' }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
