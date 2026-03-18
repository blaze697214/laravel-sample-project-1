@extends('layouts.hod')

@section('content')
    <script>
        window.dbPlacements = @json($placements);
    </script>
    <h1 class="text-xl font-bold mb-6">
        Configure Semester Placement
    </h1>

    <form method="POST" action="{{ route('hod.semesterPlacement.store') }}">
        @csrf
        <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">


        <div class="grid grid-cols-2 gap-6">

            {{-- LEFT PANEL --}}
            <div class="border p-4 bg-white">

                <h3 class="font-semibold mb-3">
                    Available Courses
                </h3>

                <div id="availableCourses" class="space-y-2 min-h-[200px]">

                    @foreach ($courses as $dc)
                        <div class="p-2 border bg-gray-50 cursor-move" data-type="course" data-id="{{ $dc->id }}">
                            <span class="text-gray-500">☰</span>
                            <span>{{ $dc->course->title }}</span>
                        </div>
                    @endforeach

                </div>


                <h3 class="font-semibold mt-6 mb-3">
                    Elective Groups
                </h3>

                <div id="availableElectives" class="space-y-2">

                    @foreach ($electiveGroups as $group)
                        <div class="p-2 border bg-gray-100 cursor-move" data-type="elective_group"
                            data-id="{{ $group->id }}">

                            {{ $group->name }}
                            (ANY {{ $group->min_select_count }})
                        </div>
                    @endforeach

                </div>

            </div>



            {{-- RIGHT PANEL --}}
            <div>

                @foreach ($years as $index => $year)
                    <div class="border p-4 mb-6 bg-white">

                        <h3 class="font-semibold mb-4">

                            {{ $year }} Year

                        </h3>

                        <div class="grid grid-cols-2 gap-4">

                            {{-- ODD SEM --}}
                            <div>

                                <h4 class="text-sm font-medium mb-2">
                                    Odd Semester
                                </h4>

                                <div class="semester-drop min-h-[120px] border p-2" data-year="{{ $index + 1 }}"
                                    data-semester="odd">

                                </div>

                            </div>


                            {{-- EVEN SEM --}}
                            <div>

                                <h4 class="text-sm font-medium mb-2">
                                    Even Semester
                                </h4>

                                <div class="semester-drop min-h-[120px] border p-2" data-year="{{ $index + 1 }}"
                                    data-semester="even">

                                </div>

                            </div>

                        </div>

                    </div>
                @endforeach
                <button type="button" id="clearPlacements" class="bg-red-600 text-white px-4 py-2 rounded">
                    Clear Layout
                </button>

            </div>

        </div>


        <input type="hidden" name="placements" id="placementsInput">


        <div class="mt-6 flex gap-3">

            <a href="{{ route('hod.semesterPlacement') }}">

                <button type="button" class="bg-gray-600 text-white px-4 py-2 rounded">
                    Back
                </button>

            </a>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Save Placements
            </button>

        </div>

    </form>


    @vite(['resources/js/semester-placement.js'])
@endsection
