@extends('layouts.hod')

@section('content')
    @php
        $departmentType = auth()->user()->department->type;
    @endphp

    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Department: {{ $department->name }}
    </h1>


    @if ($departmentType === 'programme')
        <div class="mb-8 bg-white p-6 rounded-xl shadow">

            <p class="text-gray-600">
                Active Scheme:
                <span class="font-medium">
                    {{ $activeScheme->year_start }} - {{ $activeScheme->year_end }}
                </span>
            </p>

        </div>


        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

            <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
                <p class="text-gray-500 text-sm">Courses in Scheme</p>
                <h2 class="text-3xl font-bold text-blue-600 mt-2">
                    {{ $cards['coursesInScheme'] }}
                </h2>
            </div>

            <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
                <p class="text-gray-500 text-sm">Configured Courses</p>
                <h2 class="text-3xl font-bold text-green-600 mt-2">
                    {{ $cards['configuredCourses'] }}
                </h2>
            </div>

            <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
                <p class="text-gray-500 text-sm">Remaining Configuration</p>
                <h2 class="text-3xl font-bold text-orange-600 mt-2">
                    {{ $cards['remaining'] }}
                </h2>
            </div>

            <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
                <p class="text-gray-500 text-sm">Faculty Members</p>
                <h2 class="text-3xl font-bold text-purple-600 mt-2">
                    {{ $cards['facultyCount'] }}
                </h2>
            </div>

        </div>



        {{-- Recent Faculty Users --}}
        <div class="bg-white p-6 rounded-xl shadow mb-10">

            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                Recent Faculty Users
            </h2>

            <div class="overflow-x-auto">

                <table class="w-full text-left border border-gray-200">

                    <thead class="bg-gray-100">

                        <tr>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Name</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Email</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Created</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y">

                        @foreach ($recentFaculty as $faculty)
                            <tr class="hover:bg-gray-50 border-gray-200">

                                <td class="px-4 py-3">
                                    {{ $faculty->name }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $faculty->email }}
                                </td>

                                <td class="px-4 py-3 text-gray-500">
                                    {{ $faculty->created_at }}
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>



        {{-- Scheme Progress --}}
        <div class="bg-white p-6 rounded-xl shadow">

            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                Scheme Progress
            </h2>

            <div class="overflow-x-auto">

                <table class="w-full text-left border border-gray-200">

                    <thead class="bg-gray-100">

                        <tr>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Level</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Courses Offered</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Configured</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Remaining</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y">

                        @foreach ($schemeProgress as $row)
                            <tr class="hover:bg-gray-50 border-gray-200">

                                <td class="px-4 py-3">
                                    {{ $row['level'] }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $row['offered'] }}
                                </td>

                                <td class="px-4 py-3 text-green-600 font-medium">
                                    {{ $row['configured'] }}
                                </td>

                                <td class="px-4 py-3 text-red-600 font-medium">
                                    {{ $row['remaining'] }}
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>
    @else
        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

            <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
                <p class="text-gray-500 text-sm">Owned Courses</p>
                <h2 class="text-3xl font-bold text-blue-600 mt-2">
                    {{ $cards['ownedCourses'] }}
                </h2>
            </div>

            <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
                <p class="text-gray-500 text-sm">Courses Used In Departments</p>
                <h2 class="text-3xl font-bold text-indigo-600 mt-2">
                    {{ $cards['coursesUsed'] }}
                </h2>
            </div>

            <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
                <p class="text-gray-500 text-sm">Syllabus Completed</p>
                <h2 class="text-3xl font-bold text-green-600 mt-2">
                    {{ $cards['syllabusCompleted'] }}
                </h2>
            </div>

            <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
                <p class="text-gray-500 text-sm">Faculty Members</p>
                <h2 class="text-3xl font-bold text-purple-600 mt-2">
                    {{ $cards['facultyCount'] }}
                </h2>
            </div>

        </div>



        {{-- Owned Courses --}}
        <div class="bg-white p-6 rounded-xl shadow mb-10">

            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                Owned Courses
            </h2>

            <div class="overflow-x-auto">

                <table class="w-full text-left border border-gray-200">

                    <thead class="bg-gray-100">

                        <tr>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Course</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Abbreviation</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y">

                        @foreach ($ownedCoursesTable as $course)
                            <tr class="hover:bg-gray-50 border-gray-200">

                                <td class="px-4 py-3">
                                    {{ $course->title }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $course->abbrevation }}
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>



        {{-- Recent Syllabus Updates --}}
        <div class="bg-white p-6 rounded-xl shadow">

            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                Recent Syllabus Updates
            </h2>

            <div class="overflow-x-auto">

                <table class="w-full text-left border border-gray-200">

                    <thead class="bg-gray-100">

                        <tr>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Syllabus ID</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Updated</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y">

                        @foreach ($recentUpdates as $update)
                            <tr class="hover:bg-gray-50 border-gray-200">

                                <td class="px-4 py-3">
                                    {{ $update->id }}
                                </td>

                                <td class="px-4 py-3 text-gray-500">
                                    {{ $update->updated_at }}
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>
    @endif
@endsection
