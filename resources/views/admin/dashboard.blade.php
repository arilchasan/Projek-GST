@extends('admin.components.app')

@section('container')
    @if (session()->has('error'))
        <div class="w-1/1 relative py-3 pl-4 pr-10 leading-normal text-red-700 bg-red-100 rounded-lg mt-5 mb-2 mr-3"
            style="height: 50px;" role="alert" id="error-alert">
            <p>{{ session('error') }}</p>
            <span class="absolute inset-y-0 right-0 flex items-center mr-4" onclick="closeAlert('error-alert')">
                <svg class="w-4 h-4 fill-current" role="button" viewBox="0 0 20 20">
                    <path
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" fill-rule="evenodd"></path>
                </svg>
            </span>
        </div>
    @endif
    @if (session()->has('success'))
        <div class="w-1/1 relative py-3 pl-4 pr-10 leading-normal text-green-700 bg-green-100 rounded-lg mt-5 mb-2 mr-3"
            style="height: 50px;" role="alert" id="success-alert">
            <p>{{ session('success') }}</p>
            <span class="absolute inset-y-0 right-0 flex items-center mr-4" onclick="closeAlert('success-alert')">
                <svg class="w-4 h-4 fill-current" role="button" viewBox="0 0 20 20">
                    <path
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" fill-rule="evenodd"></path>
                </svg>
            </span>
        </div>
    @endif
    
@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection
<style>
    @import url('/assets/table.css');
</style>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-start font-semibold text-2xl text-[#4f46e5]">Welcome to Dashboard Admin GST</h1>
        </div>
    </div>
</div>


<!-- component -->
<div class="flex-1 bg-[#f3f4f6] rounded-lg shadow-xl mt-4 p-8 mr-3 relative z-10">
    <h4 class="text-xl text-gray-900 font-bold">Activity log</h4>
    <div class="relative px-4">
        <div class="absolute h-full border border-dashed border-opacity-20 border-secondary"></div>
        @foreach ($activity as $i)
            <div class="flex items-center w-full my-6 -ml-1.5">
                <div class="w-1/12 z-10">
                    <div class="w-3.5 h-3.5 bg-blue-600 rounded-full"></div>
                </div>
                @php
                    $propertiesArray = json_decode($i->properties, true);
                    $url = $propertiesArray['url'];
                @endphp
                <div class="w-11/12">
                    <p class="text-sm">{{ $i->description }}</p>
                    <a href="{{ url('/storage' . $url) }}" class="text-xs text-gray-500 hover:underline">Lihat File</a>
                </div>
            </div>
        @endforeach

        @if ($activity->isEmpty())
            <div class="flex items-center w-full my-6 -ml-1.5">
                <div class="w-1/12 z-10">
                    <div class="w-3.5 h-3.5 bg-blue-600 rounded-full"></div>
                </div>
                <div class="w-11/12">
                    <p class="text-md font-semibold">Nothing Activity</p>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- Total User Section -->
<button type="button"
    class="flex w-1/3 mt-5 mx-auto justify-center rounded-md bg-[#6366f1] px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-[#6366f1] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#6366f1]">Users
    Registered: {{ $user }} Users</button>

@endsection
<script>
    function closeAlert(alertId) {
        var alert = document.getElementById(alertId);
        if (alert) {
            alert.style.display = 'none';
        }
    }
</script>
