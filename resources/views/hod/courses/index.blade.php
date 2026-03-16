@extends('layouts.hod')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Configure Courses
    </h1>


    {{-- Department & Scheme Info --}}
    <div class="bg-white p-6 rounded-xl shadow mb-8">

        <p class="text-gray-700">
            Department:
            <span class="font-semibold">
                {{ $department->name }}
            </span>
        </p>

        <p class="text-gray-600 mt-1">
            Active Scheme:
            <span class="font-medium">
                {{ $activeScheme->year_start }} - {{ $activeScheme->year_end }}
            </span>
        </p>

    </div>



    {{-- Levels Table --}}
    <div class="bg-white p-6 rounded-xl shadow">

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Level Course Configuration
        </h2>

        <div class="overflow-x-auto">

            <table class="w-full text-left border border-gray-200">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Level</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600 w-90">Name</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600 text-center">Courses Offered</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600 text-center">Configured</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600 text-center">Remaining</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600 text-center">Status</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600 text-center">Action</th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @foreach ($levels as $level)
                        <tr class="hover:bg-gray-50 border-gray-200">

                            <td class="px-4 py-3">
                                Level {{ $level->order_no }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $level->name }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                {{ $level->courses_offered }}
                            </td>

                            <td class="px-4 py-3 text-center text-green-600 font-medium">
                                {{ $level->configured }}
                            </td>

                            <td class="px-4 py-3 text-center text-red-600 font-medium">
                                {{ $level->courses_offered - $level->configured }}
                            </td>

                            <td class="px-4 py-3 text-center">

                                @if ($level->is_configured)
                                    <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded">
                                        Configured
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded">
                                        Not Configured
                                    </span>
                                @endif

                            </td>

                            <td class="px-4 py-3 text-center">

                                <a href="{{ route('hod.courses.configure', $level->id) }}">

                                    <button
                                        class="px-4 py-1 rounded text-white text-sm cursor-pointer
{{ $level->is_configured ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-blue-600 hover:bg-blue-700' }} ">

                                        @if ($level->is_configured)
                                            Edit
                                        @else
                                            Configure
                                        @endif

                                    </button>

                                </a>

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>
@endsection
