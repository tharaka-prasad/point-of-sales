@extends('layouts.master')

@section('title')
    <h3>{{ $menu }}</h3>
@endsection

@section('breadcumb')
    @parent
    <li class="breadcrumb-item active" aria-current="page">{{ $menu }}</li>
@endsection

@section('content')
    <div class="app-content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h1>Hello! This is the Reports section.</h1>
                    <p>From here you can navigate to different reports like Product Report, Sales Report, etc.</p>
                    <ul>
                        <!-- Add more report links here -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
