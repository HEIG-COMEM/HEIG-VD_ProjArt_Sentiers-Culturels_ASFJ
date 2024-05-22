@extends('layouts.vue')

@section('title', 'Sentiers Culturels')

@section('app')
    @vite(['resources/js/app.js', 'resources/css/app.css'])
@endsection

@section('content')
    <div class="flex flex-row">
        <app-debug />
    </div>
@endsection
