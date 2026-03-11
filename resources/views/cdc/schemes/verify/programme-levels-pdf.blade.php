{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>
<body class="flex justify-center items-center">
    <div class="bg-white p-6 rounded-xl shadow">


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


        <div class="bg-gray-100 p-4 border border-gray-400 overflow-x-auto">

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

                        <td class="border-0 border-gray-600 p-2 text-left flex justify-center">
                            {{ $totals['compulsory'] . ' Compulsory' }}<br>{{ ' + ' . $totals['elective'] . ' Electives' }}<br>{{ '--------' }}<br>{{ $totals['completed'] }}
                        </td>

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

    </div>

</body>
</html> --}}

<!DOCTYPE html>
<html>

<head>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2,
        h3 {
            text-align: center;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 6px;
            text-align: center;
        }

        thead {
            background: #f2f2f2;
        }

        .total {
            font-weight: bold;
            background: #eaeaea;
        }
    </style>

</head>

<body>

    <h2>PROGRAMME - {{ strtoupper($programme->name) }}</h2>

    <h3>PROGRAMME STRUCTURE</h3>

    <h3>SCHEME AT A GLANCE</h3>


    <table>

        <thead>

            <tr>

                <th>Level</th>
                <th>Name of Level</th>
                <th>Total Courses</th>
                <th>Courses to Complete</th>
                <th>TH</th>
                <th>TU</th>
                <th>PR</th>
                <th>Total Hours</th>
                <th>Total Credits</th>
                <th>Marks</th>

            </tr>

        </thead>


        <tbody>

            @foreach ($rows as $row)
                <tr>

                    <td>Level-{{ $row->level->order_no }}</td>

                    <td>{{ $row->level->name }}</td>

                    <td>{{ $row->course_offered }}</td>

                    <td>
                        {{ $row->compulsory_to_complete + $row->elective_to_complete }}
                        ({{ $row->compulsory_to_complete }} Compulsory +
                        {{ $row->elective_to_complete }} Electives)
                    </td>

                    <td>{{ $row->th_hrs }}</td>

                    <td>{{ $row->tu_hrs }}</td>

                    <td>{{ $row->pr_hrs }}</td>

                    <td>{{ $row->th_hrs + $row->tu_hrs + $row->pr_hrs }}</td>

                    <td>{{ $row->credits }}</td>

                    <td>{{ $row->marks }}</td>

                </tr>
            @endforeach


            <tr class="total">

                <td colspan="2">TOTAL</td>

                <td>{{ $totals['courses'] }}</td>

                <td>{{ $totals['completed'] }}</td>

                <td>{{ $totals['th'] }}</td>

                <td>{{ $totals['tu'] }}</td>

                <td>{{ $totals['pr'] }}</td>

                <td>{{ $totals['hours'] }}</td>

                <td>{{ $totals['credits'] }}</td>

                <td>{{ $totals['marks'] }}</td>

            </tr>


            <tr class="total">

                <td colspan="2">Grand Total</td>

                <td>{{ $grand['courses'] }}</td>

                <td>{{ $grand['completed'] }}</td>

                <td>{{ $grand['th'] }}</td>

                <td>{{ $grand['tu'] }}</td>

                <td>{{ $grand['pr'] }}</td>

                <td>{{ $grand['hours'] }}</td>

                <td>{{ $grand['credits'] }}</td>

                <td>{{ $grand['marks'] }}</td>

            </tr>

        </tbody>

    </table>

</body>

</html>
