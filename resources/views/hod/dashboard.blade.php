@extends('layouts.hod')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        HOD Dashboard
    </h1>


    {{-- Statistics Cards --}}
    <div class="grid grid-cols-4 gap-6 mb-8">

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-sm text-gray-500">Courses in Scheme</p>
            <p class="text-2xl font-semibold text-gray-800">{{ $coursesInScheme }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-sm text-gray-500">Configured Courses</p>
            <p class="text-2xl font-semibold text-gray-800">{{ $configuredCourses }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-sm text-gray-500">Remaining Configuration</p>
            <p class="text-2xl font-semibold text-gray-800">{{ $remainingCourses }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-sm text-gray-500">Faculty Members</p>
            <p class="text-2xl font-semibold text-gray-800">{{ $facultyCount }}</p>
        </div>

    </div>



    {{-- Recent Faculty Table --}}
    <div class="bg-white p-6 rounded-xl shadow">

        <h2 class="text-lg font-semibold mb-4">
            Recent Faculty Users
        </h2>


        <div class="overflow-x-auto">

            <table class="w-full text-left border border-gray-200">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-4 py-2 text-sm font-semibold text-gray-600">
                            Name
                        </th>

                        <th class="px-4 py-2 text-sm font-semibold text-gray-600">
                            Email
                        </th>

                        <th class="px-4 py-2 text-sm font-semibold text-gray-600">
                            Created
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @forelse($recentFaculty as $faculty)
                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-2">
                                {{ $faculty->name }}
                            </td>

                            <td class="px-4 py-2">
                                {{ $faculty->email }}
                            </td>

                            <td class="px-4 py-2 text-gray-500">
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
@endsection
