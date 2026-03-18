@extends('layouts.cdc')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">
    Class Award Configuration
</h1>


<div class="bg-white p-6 rounded-xl shadow mb-8">

    {{-- Class Award Table Partial --}}
    @include('partials.class-award-table')

</div>


<div class="flex justify-between">

    <a href="{{ route('cdc.schemes.verify.summary', [$scheme->id, $department->id]) }}">

        <button class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded">
            Back
        </button>

    </a>


    <a href="{{ route('cdc.schemes.verify.classAward.download', [$scheme->id, $department->id]) }}">

        <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
            Download PDF
        </button>

    </a>

</div>

@endsection