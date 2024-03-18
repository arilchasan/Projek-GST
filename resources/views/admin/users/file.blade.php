@extends('admin.components.app')
@section('container')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" />
@endsection
<style>
    @import url('/assets/table.css');
</style>

<div class="container mx-auto mt-8">
    <h2 class="text-center text-2xl font-bold mb-4">Uploaded File by {{ $user->name }} </h2>
    @foreach ($tax as $i)
        <div class="w-full mb-3 mr-5">
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-md">
                <div class="px-4 py-3 bg-gray-100">
                    <h3 class="text-lg font-semibold">{{ $i->file_name }}</h3>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-600">Uploaded by: {{ $i->user->name }}</p>
                    <p class="text-sm text-gray-600">Uploaded at: {{ date('H:i j F, Y', strtotime($i->created_at)) }}
                    </p>
                </div>
                <div class="bg-gray-100 px-4 py-3 flex justify-end">
                    <a href="{{ url('/storage/' . $i->file_path) }}"
                        class="text-blue-500 hover:text-blue-700 font-semibold">Download</a>
                </div>
            </div>
        </div>
    @endforeach
    @if ($tax->isEmpty())
        <div class="w-full md:w-1/ mx-auto">
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-md mb-2">
                <div class="p-4 text-center">
                    <p class="text-md text-gray-600">No files uploaded yet</p>
                </div>
            </div>
        </div>
    @endif
    <div class="w-full  mb-3">
        <a href="/dashboard/user" class="font-semibold hover:underline">Back</a>
    </div>
</div>

@endsection
