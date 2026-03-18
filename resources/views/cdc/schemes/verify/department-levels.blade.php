@extends('layouts.cdc')

@section('content')

    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Department Level Details
    </h1>


    {{-- error message --}}
    @if (session('error'))
        <div class="mb-6 px-4 py-3 bg-red-100 border border-red-300 text-red-800 rounded">
            {{ session('error') }}
        </div>
    @endif


    <div class="bg-white p-6 rounded-xl shadow">


        @include('partials.department-structure-table')


        {{-- buttons --}}
        <div class="mt-8 flex justify-between items-center">

            <div>

                <a href="{{ route('cdc.schemes.verify.summary', [$schemeId, $department->id]) }}">

                    <button class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm">
                        ← Back
                    </button>

                </a>

            </div>


            <div class="flex gap-4">

                <a href="{{ route('cdc.schemes.verify.departmentLevels.pdf', [$schemeId, $department->id]) }}">

                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded text-sm">
                        Download PDF
                    </button>

                </a>

            </div>

        </div>

    </div>

@endsection
