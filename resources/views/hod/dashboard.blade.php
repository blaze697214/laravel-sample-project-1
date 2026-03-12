@extends('layouts.hod')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        HOD Dashboard
    </h1>


    {{-- Department Context --}}
    <div class="mb-8 bg-white p-6 rounded-xl shadow">

        <p class="text-gray-700">
            Department:
            <span class="font-semibold">{{ $department->name }}</span>
        </p>

        <p class="text-gray-600 mt-1">
            Active Scheme:
            <span class="font-medium">
                {{ $activeScheme->year_start }} – {{ $activeScheme->year_end }}
            </span>
        </p>

    </div>



    {{-- Cards Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <p class="text-gray-500 text-sm">Courses in Scheme</p>
            <h2 class="text-3xl font-bold text-blue-600 mt-2">
                {{ $coursesInScheme }}
            </h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <p class="text-gray-500 text-sm">Configured Courses</p>
            <h2 class="text-3xl font-bold text-green-600 mt-2">
                {{ $configuredCourses }}
            </h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <p class="text-gray-500 text-sm">Remaining Configuration</p>
            <h2 class="text-3xl font-bold text-orange-600 mt-2">
                {{ $remainingCourses }}
            </h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <p class="text-gray-500 text-sm">Faculty Members</p>
            <h2 class="text-3xl font-bold text-purple-600 mt-2">
                {{ $facultyCount }}
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

                    @forelse($recentFaculty as $faculty)
                        <tr class="hover:bg-gray-50 border-gray-200">

                            <td class="px-4 py-3">
                                {{ $faculty->name }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $faculty->email }}
                            </td>

                            <td class="px-4 py-3 text-gray-500">
                                {{ $faculty->created_at->diffForHumans() }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="3" class="text-center py-4 text-gray-500">
                                No faculty users found
                            </td>
                        </tr>
                    @endforelse

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

                    @forelse($schemeProgress as $level)
                        <tr class="hover:bg-gray-50 border-gray-200">

                            <td class="px-4 py-3">
                                {{ $level['name'] }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $level['offered'] }}
                            </td>

                            <td class="px-4 py-3 text-green-600 font-medium">
                                {{ $level['configured'] }}
                            </td>

                            <td class="px-4 py-3 text-red-600 font-medium">
                                {{ $level['remaining'] }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">
                                No levels found
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>
@endsection
