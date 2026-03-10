@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Verify Schemes
    </h1>


    <div class="bg-white p-6 rounded-xl shadow">

        <div class="overflow-x-auto">

            <table class="w-full text-left border border-gray-200">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Scheme
                        </th>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Active
                        </th>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Locked
                        </th>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Verify
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @foreach ($schemes as $scheme)
                        <tr class="hover:bg-gray-50 border-gray-200">

                            <td class="px-4 py-3 font-medium text-gray-800">

                                {{ $scheme->year_start }} - {{ $scheme->year_end }}

                            </td>


                            <td class="px-4 py-3">

                                @if ($scheme->is_active)
                                    <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold bg-gray-200 text-gray-600 rounded">
                                        Inactive
                                    </span>
                                @endif

                            </td>


                            <td class="px-4 py-3">

                                @if ($scheme->is_locked)
                                    <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded">
                                        Locked
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold bg-gray-200 text-gray-600 rounded">
                                        Unlocked
                                    </span>
                                @endif

                            </td>


                            <td class="px-4 py-3">

                                <a href="{{ route('cdc.schemes.verify.programmes', $scheme->id) }}">

                                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-sm">
                                        Verify
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
