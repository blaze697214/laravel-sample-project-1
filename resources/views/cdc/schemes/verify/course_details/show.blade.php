@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Course Details
    </h1>


    <div class="bg-white p-6 rounded-xl shadow mb-8">

        <p class="text-gray-700">
            Department :
            <strong>{{ $department->name }}</strong>
        </p>

        <p class="text-gray-600 mt-1">
            Level :
            <strong>{{ $level->order_no }} - {{ $level->name }}</strong>
        </p>

    </div>



    <div class="bg-white p-6 rounded-xl shadow mb-8">

        @include('partials.level-structure-table')

    </div>



    <div class="flex justify-between">

        <a href="{{ route('cdc.schemes.verify.courseDetails', [$scheme->id, $department->id]) }}">

            <button class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded">
                Back
            </button>

        </a>


        <a href="{{ route('cdc.schemes.verify.courseDetails.download', [$scheme->id, $department->id, $level->id]) }}">

        <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
            Download PDF
        </button>

        </a>

    </div>
@endsection
