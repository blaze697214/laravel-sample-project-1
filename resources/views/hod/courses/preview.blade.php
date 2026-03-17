@extends('layouts.hod')

@section('content')
    
    @include('partials.level-structure-table')

    <div class="flex gap-4 mt-9">

        <a href="{{ route('hod.courses.configure', $level->id) }}">

            <button class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded">
                Back & Edit
            </button>

        </a>


        <form method="POST" action="{{ route('hod.courses.finalize',$level->id) }}">
            
            @csrf

            <input type="hidden" name="scheme_id" value="{{ $level->curriculum_year_id }}">

            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">
                Save Level Configuration
            </button>

        </form>

    </div>
@endsection
