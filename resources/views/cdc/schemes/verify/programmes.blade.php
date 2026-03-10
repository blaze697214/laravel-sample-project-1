@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-2">
        Verify Scheme
    </h1>

    <p class="text-gray-600 mb-6">
        Select Programme to Verify
    </p>

    <div class="mb-4 mt-10 ml-5">

        <a href="{{ route('cdc.schemes.verify') }}">

            <button class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm">
                ← Back to Schemes
            </button>

        </a>

    </div>



    <div class="bg-white p-6 rounded-xl shadow">

        <div class="overflow-x-auto">

            <table class="w-full text-left border border-gray-200">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Programme
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

                    @foreach ($programmes as $programme)
                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-3 font-medium text-gray-800">

                                {{ $programme->name }}

                                @if ($programme->abbreviation)
                                    <span class="text-gray-500 text-sm ml-1">
                                        ({{ $programme->abbreviation }})
                                    </span>
                                @endif

                            </td>



                            <td class="px-4 py-3">

                                @if ($status[$programme->id])
                                    <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded">
                                        Ready
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded">
                                        Incomplete
                                    </span>
                                @endif

                            </td>



                            <td class="px-4 py-3">

                                <a href="{{ route('cdc.schemes.verify.summary', [$schemeId, $programme->id]) }}">

                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-sm">
                                    View
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
