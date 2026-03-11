@extends('layouts.cdc')

@section('content')
    <div class="text-center mb-6">

        <h2 class="font-bold text-lg">
            PROGRAMME - {{ strtoupper($programme->name) }}
        </h2>

        <h3 class="font-semibold">
            PROGRAMME STRUCTURE
        </h3>

        <h4 class="font-semibold mt-2">
            SCHEME AT A GLANCE
        </h4>

    </div>


    <div class="bg-gray-100 p-4 border border-gray-400">

        <table class="w-full border border-gray-600 text-center text-sm">

            <thead class="bg-gray-200 font-semibold">

                <tr>

                    <th class="border border-gray-600 p-2">Level</th>
                    <th class="border border-gray-600 p-2">Name of Level</th>
                    <th class="border border-gray-600 p-2">Total Number of Courses offered</th>
                    <th class="border border-gray-600 p-2">Number of Courses to be completed</th>
                    <th class="border border-gray-600 p-2">TH</th>
                    <th class="border border-gray-600 p-2">TU</th>
                    <th class="border border-gray-600 p-2">PR</th>
                    <th class="border border-gray-600 p-2">Total Hours</th>
                    <th class="border border-gray-600 p-2">Total Credits</th>
                    <th class="border border-gray-600 p-2">Marks</th>

                </tr>

            </thead>


            <tbody class="bg-white">

                @foreach ($rows as $row)
                    @if (!$row->level->is_audit)
                        <tr>

                            <td class="border border-gray-600 p-2">
                                Level-{{ $row->level->order_no }}
                            </td>

                            <td class="border border-gray-600 p-2 text-left">
                                {{ $row->level->name }}
                            </td>

                            <td class="border border-gray-600 p-2">
                                {{ str_pad($row->courses_offered, 2, '0', STR_PAD_LEFT) }}
                            </td>

                            <td class="border border-gray-600 p-2">

                                {{ $row->compulsory_to_complete + $row->elective_to_complete }}

                                <br>

                                <span class="text-xs">

                                    ({{ $row->compulsory_to_complete }} Compulsory
                                    @if ($row->elective_to_complete)
                                        +{{ $row->elective_to_complete }} Electives
                                    @endif)
                                </span>

                            </td>

                            <td class="border border-gray-600 p-2">{{ $row->th_hrs }}</td>

                            <td class="border border-gray-600 p-2">
                                {{ $row->tu_hrs ?: '--' }}
                            </td>

                            <td class="border border-gray-600 p-2">{{ $row->pr_hrs }}</td>

                            <td class="border border-gray-600 p-2">{{ $row->total_hours }}</td>

                            <td class="border border-gray-600 p-2">{{ $row->credits }}</td>

                            <td class="border border-gray-600 p-2">{{ $row->marks }}</td>

                        </tr>
                    @endif
                @endforeach



                <tr class="bg-gray-200 font-semibold">

                    <td colspan="2" class="border border-gray-600 p-2">
                        TOTAL
                    </td>

                    <td class="border border-gray-600 p-2">{{ $totals['courses'] }}</td>

                    <td class="border-0 border-gray-600 p-2 text-left flex justify-center">{{ $totals['compulsory'].' Compulsory'}}<br>{{ ' + '. $totals['elective'].' Electives'}}<br>{{ '--------'}}<br>{{ $totals['completed'] }}</td>

                    <td class="border border-gray-600 p-2">{{ $totals['th'] }}</td>

                    <td class="border border-gray-600 p-2">{{ $totals['tu'] }}</td>

                    <td class="border border-gray-600 p-2">{{ $totals['pr'] }}</td>

                    <td class="border border-gray-600 p-2">{{ $totals['hours'] }}</td>

                    <td class="border border-gray-600 p-2">{{ $totals['credits'] }}</td>

                    <td class="border border-gray-600 p-2">{{ $totals['marks'] }}</td>

                </tr>



                @if ($auditRow)
                    <tr>

                        <td colspan="2" class="border border-gray-600 p-2 font-semibold">
                            Audit Courses
                        </td>

                        <td class="border border-gray-600 p-2">{{ $auditRow->courses_offered }}</td>

                        <td class="border border-gray-600 p-2">{{ $auditRow->compulsory_to_complete }}</td>

                        <td class="border border-gray-600 p-2">{{ $auditRow->th_hrs }}</td>

                        <td class="border border-gray-600 p-2">{{ $auditRow->tu_hrs }}</td>

                        <td class="border border-gray-600 p-2">{{ $auditRow->pr_hrs }}</td>

                        <td class="border border-gray-600 p-2">{{ $auditRow->total_hours }}</td>

                        <td class="border border-gray-600 p-2">--</td>

                        <td class="border border-gray-600 p-2">--</td>

                    </tr>
                @endif



                <tr class="bg-gray-200 font-semibold">

                    <td colspan="2" class="border border-gray-600 p-2">
                        Grand Total
                    </td>

                    <td class="border border-gray-600 p-2">{{ $grand['courses'] }}</td>

                    <td class="border border-gray-600 p-2">{{ $grand['completed'] }}</td>

                    <td class="border border-gray-600 p-2">{{ $grand['th'] }}</td>

                    <td class="border border-gray-600 p-2">{{ $grand['tu'] }}</td>

                    <td class="border border-gray-600 p-2">{{ $grand['pr'] }}</td>

                    <td class="border border-gray-600 p-2">{{ $grand['hours'] }}</td>

                    <td class="border border-gray-600 p-2">{{ $grand['credits'] }}</td>

                    <td class="border border-gray-600 p-2">{{ $grand['marks'] }}</td>

                </tr>

            </tbody>

        </table>

    </div>



    <div class="mt-6 flex justify-between">

        <a href="{{ route('cdc.schemes.programmeLevels.create', [$schemeId, $programme->id]) }}">

            <button class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                Back & Edit
            </button>

        </a>


        <form method="POST" action="{{ route('cdc.schemes.programmeLevels.finalize', [$schemeId, $programme->id]) }}">

            @csrf

            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Save & Return to Programme List
            </button>

        </form>

    </div>
@endsection
