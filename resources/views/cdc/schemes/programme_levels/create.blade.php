@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-2">
        Configure Programme Levels
    </h1>

    <p class="mb-6 text-gray-600">
        Programme: {{ $programme->name }}
    </p>


    @if ($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-2 rounded mb-6">
            {{ $errors->first() }}
        </div>
    @endif



    <div class="bg-white p-6 rounded-xl shadow">

        <form method="POST" action="{{ route('cdc.schemes.programmeLevels.store', [$scheme->id, $programme->id]) }}">

            @csrf


            <div class="overflow-x-auto">

                <table class="w-full text-left border border-gray-200">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">Level</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">Courses Offered</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">Compulsory</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">Elective</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">TH</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">TU</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">PR</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">Credits</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">Marks</th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @foreach ($levels as $level)
                            @php
                                $row = $existing[$level->id] ?? null;
                            @endphp

                            <tr class="hover:bg-gray-50 border-gray-200">

                                <td class="px-3 py-2 font-medium text-gray-800">

                                    {{ $level->name }}

                                    <input type="hidden" name="levels[]" value="{{ $level->id }}">

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="course_offered[{{ $level->id }}]"
                                        value="{{ $row->course_offered ?? '' }}"
                                        class="w-20 border border-gray-300 rounded px-2 py-1">

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="compulsory[{{ $level->id }}]"
                                        value="{{ $row->compulsory_to_complete ?? '' }}"
                                        class="w-20 border border-gray-300 rounded px-2 py-1">

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="elective[{{ $level->id }}]"
                                        value="{{ $row->elective_to_complete ?? '' }}"
                                        class="w-20 border border-gray-300 rounded px-2 py-1">

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="th[{{ $level->id }}]" value="{{ $row->th_hrs ?? '' }}"
                                        class="w-16 border border-gray-300 rounded px-2 py-1">

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="tu[{{ $level->id }}]" value="{{ $row->tu_hrs ?? '' }}"
                                        class="w-16 border border-gray-300 rounded px-2 py-1">

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="pr[{{ $level->id }}]" value="{{ $row->pr_hrs ?? '' }}"
                                        class="w-16 border border-gray-300 rounded px-2 py-1">

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="credits[{{ $level->id }}]"
                                        value="{{ $row->credits ?? '' }}"
                                        class="w-16 border border-gray-300 disabled:bg-gray-200 rounded px-2 py-1"
                                        @if($level->is_audit) disabled @endif
                                        >

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="marks[{{ $level->id }}]"
                                        value="{{ $row->marks ?? '' }}"
                                        class="w-16 border border-gray-300 rounded disabled:bg-gray-200 px-2 py-1"
                                        @if($level->is_audit) disabled @endif>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>


            <div class="mt-6">

                <a href="{{ route('cdc.schemes.programmeLevels.preview', [$schemeId, $programme->id]) }}">

                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">

                        Save & Preview

                    </button>
                    
                </a>

            </div>


        </form>

    </div>
@endsection
