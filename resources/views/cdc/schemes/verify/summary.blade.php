@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-2">
        Verification Summary
    </h1>

    <p class="text-gray-600 mb-6">

        Department: {{ $department->name }}

    </p>

    @if (session('error'))
        <div id="successMessage" class="mb-6 px-4 py-3 bg-red-100 border border-red-300 text-red-800 rounded">

            {{ session('error') }}

        </div>
    @endif

    <script>
        setTimeout(function() {

            const msg = document.getElementById('successMessage');

            if (msg) {
                msg.style.display = 'none';
            }

        }, 2000);
    </script>


    <div class="mb-4 mt-10 ml-5">

        <a href="{{ route('cdc.schemes.verify.departments', $schemeId) }}">

            <button class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm">
                ← Back to Departments
            </button>

        </a>

    </div>



    <div class="bg-white p-6 rounded-xl shadow">

        <table class="w-full text-left border border-gray-200">

            <thead class="bg-gray-100">

                <tr>

                    <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                        Configuration
                    </th>

                    <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                        Status
                    </th>

                    <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                        View
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y">


                <tr class="hover:bg-gray-50 border-gray-200">

                    <td class="px-4 py-3">
                        Department Level Details
                    </td>

                    <td class="px-4 py-3">

                        @if ($verification['departmentLevel'])
                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded">
                                Configured
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded">
                                Missing
                            </span>
                        @endif

                    </td>

                    <td class="px-4 py-3">

                        @if ($verification['departmentLevel'])
                            <a href="{{ route('cdc.schemes.verify.departmentLevels', [$schemeId, $department->id]) }}">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-sm">
                                View
                            </button>
                            </a>
                        @else
                            <button class="bg-gray-300 text-gray-600 px-4 py-1 rounded text-sm cursor-not-allowed">
                                Unavailable
                            </button>
                        @endif

                    </td>

                </tr>



                <tr class="hover:bg-gray-50 border-gray-200">

                    <td class="px-4 py-3">
                        Level Details
                    </td>

                    <td class="px-4 py-3">

                        @if ($verification['courseDetails'])
                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded">
                                Configured
                            </span>
                        @elseif ($verification['configuredCourses']>0)
                            <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded">
                                Not Configured
                            </span>

                        @else
                            <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded">
                                Missing
                            </span>
                        @endif

                    </td>

                    <td class="px-4 py-3">

                        @if ($verification['courseDetails'] || $verification['configuredCourses']>0)
                            <a href="{{ route('cdc.schemes.verify.courseDetails', [$schemeId, $department->id]) }}">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-sm">
                                View
                            </button>
                            </a>
                        @else
                            <button class="bg-gray-300 text-gray-600 px-4 py-1 rounded text-sm cursor-not-allowed">
                                Unavailable
                            </button>
                        @endif


                    </td>

                </tr>



                <tr class="hover:bg-gray-50 border-gray-200">

                    <td class="px-4 py-3">
                        Class Award Configuration
                    </td>

                    <td class="px-4 py-3">

                        @if ($verification['classAward'])
                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded">
                                Configured
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded">
                                Missing
                            </span>
                        @endif

                    </td>

                    <td class="px-4 py-3">
                        @if ($verification['classAward'])
                            {{-- <a href="{{ route('cdc.verify.classAward', [$schemeId, $department->id]) }}"> --}}

                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-sm">
                                View
                            </button>

                            {{-- </a> --}}
                        @else
                            <button class="bg-gray-300 text-gray-600 px-4 py-1 rounded text-sm cursor-not-allowed">
                                Unavailable
                            </button>
                        @endif

                    </td>

                </tr>



                <tr class="hover:bg-gray-50 border-gray-200">

                    <td class="px-4 py-3">
                        Semester Placement
                    </td>

                    <td class="px-4 py-3">

                        @if ($verification['semesterPlacement'])
                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded">
                                Configured
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded">
                                Missing
                            </span>
                        @endif

                    </td>

                    <td class="px-4 py-3">
                        @if ($verification['semesterPlacement'])
                            {{-- <a href="{{ route('cdc.verify.semesterPlacement', [$schemeId, $department->id]) }}"> --}}

                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-sm">
                                View
                            </button>

                            {{-- </a> --}}
                        @else
                            <button class="bg-gray-300 text-gray-600 px-4 py-1 rounded text-sm cursor-not-allowed">
                                Unavailable
                            </button>
                        @endif

                    </td>

                </tr>


            </tbody>

        </table>

    </div>
@endsection
