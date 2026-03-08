@extends('layout.admin')

@section('content')

    <h1 class="text-2xl font-bold text-gray-800 mb-6">
    Admin Dashboard
    </h1>


    <!-- Welcome Section -->
    <div class="mb-8 bg-white p-6 rounded-xl shadow">

        <h2 class="text-xl font-semibold text-gray-800">
            Welcome, {{ auth()->user()->name }}
        </h2>

        <p class="text-gray-600 mt-1">
            Role: {{ auth()->user()->roles->first()->name }}
        </p>

    </div>



    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <p class="text-gray-500 text-sm">Total Users</p>
            <h2 class="text-3xl font-bold text-blue-600 mt-2">
                {{ $totalUsers }}
            </h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <p class="text-gray-500 text-sm">Programmes</p>
            <h2 class="text-3xl font-bold text-indigo-600 mt-2">
                {{ $totalProgrammes }}
            </h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <p class="text-gray-500 text-sm">Courses</p>
            <h2 class="text-3xl font-bold text-green-600 mt-2">
                {{ $totalCourses }}
            </h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <p class="text-gray-500 text-sm">Total Faculty</p>
            <h2 class="text-3xl font-bold text-purple-600 mt-2">
                {{ $totalFaculty }}
            </h2>
        </div>

    </div>



    <!-- Programme Distribution -->
    <div class="bg-white p-6 rounded-xl shadow mb-10">

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Faculty per Programme
        </h2>

        <div class="overflow-x-auto">

            <table class="w-full text-left border border-gray-200">

                <thead class="bg-gray-100">

                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Programme
                        </th>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Faculty Count
                        </th>
                    </tr>

                </thead>

                <tbody class="divide-y">

                @foreach($programmeFaculty as $programme)

                    <tr class="hover:bg-gray-50">

                        <td class="px-4 py-3">
                            {{ $programme->name }}
                        </td>

                        <td class="px-4 py-3 font-medium">
                            {{ $programme->faculty_count }}
                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

    </div>



    <!-- Recent Users -->
    <div class="bg-white p-6 rounded-xl shadow">

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Recent Users
        </h2>

        <div class="overflow-x-auto">

            <table class="w-full text-left border border-gray-200">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Name
                        </th>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Role
                        </th>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Created At
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y">

                @foreach($recentUsers as $user)

                    <tr class="hover:bg-gray-50">

                        <td class="px-4 py-3">
                            {{ $user->name }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $user->roles->first()->name}}
                        </td>

                        <td class="px-4 py-3 text-gray-500">
                            {{ $user->created_at->format('d M Y') }}
                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

    </div>

@endsection
