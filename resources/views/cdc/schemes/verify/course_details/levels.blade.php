@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-2">
        Course Details Verification
    </h1>


    <p class="text-gray-600 mb-6">
        Select Level to Verify
    </p>



    <div class="bg-white p-6 rounded-xl shadow mb-8">

        <div class="overflow-x-auto">

            <table class="w-full text-left border border-gray-200">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Level
                        </th>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Name
                        </th>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Status
                        </th>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Action
                        </th>

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

                            <td class="px-4 py-3">

                                @if ($level->status === 'configured')
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

                                @if ($level->status === 'configured')
                                    <a
                                        href="{{ route('cdc.schemes.verify.courseDetails.show', [$scheme->id, $department->id, $level->id]) }}">

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
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>



    <a href="{{ route('cdc.schemes.verify.summary', [$scheme->id, $department->id]) }}">

        <button class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded">
            Back
        </button>

    </a>
@endsection
