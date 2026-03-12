<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title></title>

    <style>
        /* BODY */
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* CARD */
        .container {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        /* HEADINGS */
        .header {
            text-align: center;
            margin-bottom: 24px;
        }

        .title {
            font-weight: 700;
            font-size: 18px;
        }

        .subtitle {
            font-weight: 600;
        }

        .subtitle2 {
            font-weight: 600;
            margin-top: 8px;
        }

        /* TABLE WRAPPER */
        .table-wrapper {
            background: #f3f4f6;
            padding: 16px;
            border: 1px solid #9ca3af;

        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #4b5563;
            text-align: center;
            font-size: 14px;
        }

        /* TABLE HEAD */
        thead {
            background: #e5e7eb;
            font-weight: 600;
        }

        /* TABLE CELLS */
        th,
        td {
            border: 1px solid #4b5563;
            padding: 8px;
        }

        /* LEFT TEXT */
        .text-left {
            text-align: left;
        }

        /* SMALL TEXT */
        .text-xs {
            font-size: 12px;
        }

        /* WHITE BG */
        .bg-white {
            background: white;
        }

        /* GRAY ROW */
        .row-gray {
            background: #e5e7eb;
            font-weight: 600;
        }


        .b-l{
            border: none;
            
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="header">

            <h2 class="title">
                DEPARTMENT - {{ strtoupper($department->name) }}
            </h2>

            <h3 class="subtitle">
                DEPARTMENT STRUCTURE
            </h3>

            <h4 class="subtitle2">
                SCHEME AT A GLANCE
            </h4>

        </div>

        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>
                        <th>Level</th>
                        <th>Name of Level</th>
                        <th>Total Number of Courses offered</th>
                        <th>Number of Courses to be completed</th>
                        <th>TH</th>
                        <th>TU</th>
                        <th>PR</th>
                        <th>Total Hours</th>
                        <th>Total Credits</th>
                        <th>Marks</th>
                    </tr>

                </thead>

                <tbody class="bg-white">

                    @foreach ($rows as $row)
                        @if (!$row->level->is_audit)
                            <tr>

                                <td>
                                    Level-{{ $row->level->order_no }}
                                </td>

                                <td class="text-left">
                                    {{ $row->level->name }}
                                </td>

                                <td>
                                    {{ str_pad($row->courses_offered, 2, '0', STR_PAD_LEFT) }}
                                </td>

                                <td>

                                    {{ $row->compulsory_to_complete + $row->elective_to_complete }}

                                    <br/>

                                    <span class="text-xs">

                                        ({{ $row->compulsory_to_complete }} Compulsory
                                        @if ($row->elective_to_complete)
                                            +{{ $row->elective_to_complete }} Electives
                                        @endif)
                                    </span>

                                </td>

                                <td>{{ $row->th_hrs }}</td>

                                <td>{{ $row->tu_hrs }}</td>

                                <td>{{ $row->pr_hrs }}</td>

                                <td>{{ $row->total_hours }}</td>

                                <td>{{ $row->credits }}</td>

                                <td>{{ $row->marks }}</td>

                            </tr>
                        @endif
                    @endforeach


                    <tr class="row-gray">

                        <td colspan="2">
                            TOTAL
                        </td>

                        <td>{{ $totals['courses'] }}</td>

                        <td class="text-left b-l">
                            {{ $totals['compulsory'] . ' Compulsory' }}<br/>
                            {{ ' + ' . $totals['elective'] . ' Electives' }}<br/>
                            --------<br/>
                            {{ $totals['completed'] }}
                        </td>

                        <td>{{ $totals['th'] }}</td>

                        <td>{{ $totals['tu'] }}</td>

                        <td>{{ $totals['pr'] }}</td>

                        <td>{{ $totals['hours'] }}</td>

                        <td>{{ $totals['credits'] }}</td>

                        <td>{{ $totals['marks'] }}</td>

                    </tr>


                    @if ($auditRow)
                        <tr>

                            <td colspan="2"><b>Audit Courses</b></td>

                            <td>{{ $auditRow->courses_offered }}</td>

                            <td>{{ $auditRow->compulsory_to_complete }}</td>

                            <td>{{ $auditRow->th_hrs }}</td>

                            <td>{{ $auditRow->tu_hrs }}</td>

                            <td>{{ $auditRow->pr_hrs }}</td>

                            <td>{{ $auditRow->total_hours }}</td>

                            <td>--</td>

                            <td>--</td>

                        </tr>
                    @endif


                    <tr class="row-gray">

                        <td colspan="2">
                            Grand Total
                        </td>

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

        </div>

    </div>

</body>

</html>
