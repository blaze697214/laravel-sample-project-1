@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        CDC Dashboard
    </h1>


    <!-- Welcome Section -->
    <div class="mb-8 bg-white p-6 rounded-xl shadow">

        <h2 class="text-xl font-semibold text-gray-800">
            Welcome, {{ auth()->user()->name }}
        </h2>

        <p class="text-gray-600 mt-1">
            Role: {{ strtoupper(auth()->user()->roles->first()->name) }}
        </p>

    </div>



    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <p class="text-gray-500 text-sm">Total Schemes</p>
            <h2 class="text-3xl font-bold text-blue-600 mt-2">
                {{ $totalSchemes }}
            </h2>
        </div>


        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <p class="text-gray-500 text-sm">Active Scheme</p>
            <h2 class="text-3xl font-bold text-indigo-600 mt-2">
                {{ $activeScheme ?? 'None' }}
            </h2>
        </div>


        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <p class="text-gray-500 text-sm">Total HODs</p>
            <h2 class="text-3xl font-bold text-green-600 mt-2">
                {{ $totalHods }}
            </h2>
        </div>


        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <p class="text-gray-500 text-sm">Total Faculty</p>
            <h2 class="text-3xl font-bold text-purple-600 mt-2">
                {{ $totalFaculty }}
            </h2>
        </div>

    </div>



    <!-- Scheme Status Table -->

    <div class="bg-white p-6 rounded-xl shadow mb-10">

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Scheme Status
        </h2>

        <div class="overflow-x-auto">

            <table class="w-full text-left border border-gray-200">

                <thead class="bg-gray-100">

                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Scheme</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Active</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Locked</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Created</th>
                    </tr>

                </thead>

                <tbody class="divide-y">

                    @foreach ($schemes as $scheme)
                        <tr class="hover:bg-gray-50 border-gray-200">

                            <td class="px-4 py-3">
                                {{ $scheme->year_start }} - {{ $scheme->year_end }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $scheme->is_active ? 'Yes' : 'No' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $scheme->is_locked ? 'Locked' : 'Editable' }}
                            </td>

                            <td class="px-4 py-3 text-gray-500">
                                {{ $scheme->created_at->format('d M Y') }}
                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>



    <!-- Recent HOD Users -->

    <div class="bg-white p-6 rounded-xl shadow">

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Recent HOD Users
        </h2>

        <div class="overflow-x-auto">

            <table class="w-full text-left border border-gray-200">

                <thead class="bg-gray-100">

                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Name</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Programme</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Created</th>
                    </tr>

                </thead>

                <tbody class="divide-y">

                    @foreach ($recentHods as $hod)
                        <tr class="hover:bg-gray-50 border-gray-200">

                            <td class="px-4 py-3">
                                {{ $hod->name }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $hod->programme->name ?? '-' }}
                            </td>

                            <td class="px-4 py-3 text-gray-500">
                                {{ $hod->created_at->format('d M Y') }}
                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>
@endsection
