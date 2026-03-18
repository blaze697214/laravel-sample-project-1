@extends('layouts.hod')

@section('content')

    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Semester Placement
    </h1>


    {{-- Context Card --}}
    <div class="bg-white p-6 rounded-xl shadow mb-8">

        <p class="text-gray-600">
            Department: <span class="font-medium">{{ $department->name }}</span>
        </p>

        <p class="text-gray-600 mt-1">
            Scheme: <span class="font-medium">{{ $scheme->year_start }} - {{ $scheme->year_end }}</span>
        </p>

    </div>



    @if (empty($years))
        <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 p-4 rounded">
            No courses configured yet. Please complete course configuration first.
        </div>
    @else
        <div class="bg-white p-6 rounded-xl shadow">

            <div class="overflow-x-auto">

                <table class="w-full text-left border border-gray-200">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                                Academic Year
                            </th>

                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                                Odd Semester
                            </th>

                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                                Even Semester
                            </th>

                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                                Total Courses
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @foreach ($years as $year)
                            <tr class="hover:bg-gray-50 border-gray-200">

                                <td class="px-4 py-3 font-semibold">
                                    {{ $year }} Year 
                                </td>

                                <td class="px-4 py-3">
                                    {{ $placementCounts[$year]['odd'] ?? 0 }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $placementCounts[$year]['even'] ?? 0 }}
                                </td>

                                <td class="px-4 py-3 font-medium">

                                    {{ ($placementCounts[$year]['odd'] ?? 0) + ($placementCounts[$year]['even'] ?? 0) }}

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>



        {{-- Actions --}}
        <div class="mt-8 flex gap-4 justify-between">

            <a href="{{ route('hod.semesterPlacement.configure') }}">

                <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                    Configure Placement
                </button>

            </a>


            @if ($isComplete)
                <a href="{{ route('hod.semesterPlacement.preview') }}">

                    <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition">
                        Preview Sample Path
                    </button>

                </a>
            @else
                <button class="bg-gray-400 text-white px-6 py-2 rounded-lg cursor-not-allowed">
                    Preview Sample Path
                </button>
            @endif

        </div>
    @endif

@endsection
