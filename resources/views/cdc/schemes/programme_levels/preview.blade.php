@extends('layouts.cdc')

@section('content')
    
    @include('partials.department-structure-table')

    <div class="mt-6 flex justify-between">

        <a href="{{ route('cdc.schemes.departmentLevels.create', [$schemeId, $department->id]) }}">

            <button class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                Back & Edit
            </button>

        </a>


        <form method="POST" action="{{ route('cdc.schemes.departmentLevels.finalize', [$schemeId, $department->id]) }}">

            @csrf

            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Save & Return to Department List
            </button>

        </form>

    </div>
@endsection
