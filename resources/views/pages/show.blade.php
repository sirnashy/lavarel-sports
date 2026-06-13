@extends('layouts.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">{{ $page->title }}</li>
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="page-title mb-4">{{ $page->title }}</h1>
            <div class="cms-content">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</div>
@endsection