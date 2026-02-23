@extends('Layout.Layout')

@section('title', 'Portfolio')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Add your portfolio content here -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800">Portfolio Projects</h2>
            <p class="text-gray-600 mt-2">Your portfolio content goes here</p>
        </div>
    </div>
@endsection
