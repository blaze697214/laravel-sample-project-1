@extends('layouts.hod')

@section('content')
    


    @include('partials.class-award-table')



    {{-- Buttons --}}

    <div class="mt-6 flex gap-4">

        <a href="{{ route('hod.classAward') }}">

            <button class="px-4 py-2 bg-gray-600 text-white rounded font-medium">
                Back & Edit
            </button>

        </a>


    </div>
@endsection
