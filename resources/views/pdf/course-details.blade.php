<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    /* HEADINGS */

    .title-first {
        font-size: 26px;
        font-weight: bold;
        color: #1f2937;
        text-align: center;
        margin-bottom: 8px;
    }

    .title-main {
        font-size: 23px;
        font-weight: bold;
        color: #1f2937;
        text-align: center;
        margin-bottom: 8px;
    }

    .title-level {
        font-size: 18px;
        font-weight: 600;
        text-align: center;
        color: #374151;
    }

    .title-name {
        font-size: 18px;
        font-weight: 600;
        text-align: center;
        color: #374151;
        margin-bottom: 24px;
    }

    /* CARD */

    .card {
        background: #ffffff;
        padding: 24px;
        border-radius: 12px;
        font-size: 10px;
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 32px;
    }

    /* TABLE WRAPPER */

    .table-wrapper {
        overflow-x: auto;
    }

    /* TABLE */

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        text-align: center;
        border: 1px solid #e5e7eb;
    }

    thead {
        background: #f3f4f6;
    }

    th,
    td {
        border: 1px solid #e5e7eb;
        padding: 8px 12px;
    }

    /* ROWS */

    .row-hover:hover {
        background: #f9fafb;
    }

    .row-gray {
        background: #f3f4f6;
        font-weight: 600;
    }

    .row-total {
        background: #f3f4f6;
        font-weight: 600;
    }

    /* TEXT */

    .text-left {
        text-align: left;
    }

    .font-medium {
        font-weight: 500;
    }

    .font-semibold {
        font-weight: 600;
    }

    .mb-2 {
        margin-bottom: 8px;
    }

    .text-gray {
        color: #374151;
    }

    .nowrap {
        white-space: nowrap;
    }

    @page {
        size: A4;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        font-size: 9px;
    }

    th,
    td {
        border: 1px solid black;
        padding: 2px 3px;
        text-align: center;
        line-height: 1.1;
        border-right: black;
    }

    td.text-left {
        text-align: left;
        white-space: normal;
        word-wrap: break-word;
    }

    thead {
        display: table-header-group;
    }

    tr {
        page-break-inside: avoid;
    }

    .level-container {
        page-break-inside: avoid;
    }
</style>

<div class="level-container">
    <h1 class="title-first">
        PROGRAMME - DIPLOMA IN {{ strtoupper($department->name) }}
    </h1>

    <h1 class="title-main">
        PROGRAMME STRUCTURE
    </h1>

    @if (!$level->is_audit)
        <h2 class="title-level">
            LEVEL - {{ $level->order_no }}
        </h2>
    @endif


    <h2 class="title-name">
        {{ strtoupper($level->name) }}
    </h2>



    @if (!$level->is_audit)

        <div class="card">

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th rowspan="3">Sr.</th>
                            <th rowspan="3">Course Code</th>
                            <th rowspan="3">Course Title</th>
                            <th rowspan="3">Course Abbr.</th>

                            <th colspan="5">TEACHING SCHEME</th>

                            <th colspan="7">EXAMINATION SCHEME</th>

                        </tr>

                        <tr>

                            <th colspan="4">Hours per Week</th>
                            <th rowspan="2">Total Credits</th>

                            <th colspan="2">Theory Paper</th>

                            <th rowspan="2">Test</th>
                            <th rowspan="2">PR</th>
                            <th rowspan="2">OR</th>
                            <th rowspan="2">TW</th>
                            <th rowspan="2">Total</th>

                        </tr>

                        <tr>

                            <th>TH</th>
                            <th>TU</th>
                            <th>PR</th>
                            <th>Total Hours</th>

                            <th>Hrs</th>
                            <th>Mark</th>

                        </tr>

                    </thead>

                    <tbody>

                        @php
                            $sr = 1;

                            $totalTH = 0;
                            $totalTU = 0;
                            $totalPR = 0;
                            $totalHours = 0;
                            $totalCredits = 0;

                            $totalTHMarks = 0;
                            $totalTest = 0;
                            $totalPRMarks = 0;
                            $totalOR = 0;
                            $totalTW = 0;
                            $totalMarks = 0;
                        @endphp


                        @foreach ($compulsoryCourses as $dc)
                            @php

                                $details = $dc->courseDetails;

                                $totalH = $details->th_hrs + $details->tu_hrs + $details->pr_hrs;

                                $rowMarks =
                                    $details->th_marks +
                                    $details->test_marks +
                                    $details->pr_marks +
                                    $details->or_marks +
                                    $details->tw_marks;

                            @endphp


                            <tr class="row-hover">

                                <td>
                                    {{ str_pad($sr++, 2, '0', STR_PAD_LEFT) }}
                                </td>

                                <td>
                                    {{ $details->course_code }}
                                </td>

                                <td class="text-left">
                                    {{ $dc->course->title }}
                                </td>

                                <td>
                                    {{ $dc->course->abbrevation }}
                                </td>

                                <td>{{ $details->th_hrs ?: '--' }}</td>
                                <td>{{ $details->tu_hrs ?: '--' }}</td>
                                <td>{{ $details->pr_hrs ?: '--' }}</td>

                                <td>{{ $totalH }}</td>

                                <td>{{ $details->credits }}</td>

                                <td>{{ $details->th_paper_hrs ?: '--' }}</td>
                                <td>{{ $details->th_marks ?: '--' }}</td>

                                <td>{{ $details->test_marks ?: '--' }}</td>
                                <td>{{ $details->pr_marks ?: '--' }}</td>
                                <td>{{ $details->or_marks ?: '--' }}</td>
                                <td>{{ $details->tw_marks ?: '--' }}</td>

                                <td class="font-medium">
                                    {{ $rowMarks }}
                                </td>

                            </tr>

                            @php

                                $totalTH += $details->th_hrs;
                                $totalTU += $details->tu_hrs;
                                $totalPR += $details->pr_hrs;

                                $totalHours += $totalH;
                                $totalCredits += $details->credits;

                                $totalTHMarks += $details->th_marks;
                                $totalTest += $details->test_marks;
                                $totalPRMarks += $details->pr_marks;
                                $totalOR += $details->or_marks;
                                $totalTW += $details->tw_marks;

                                $totalMarks += $rowMarks;

                            @endphp
                        @endforeach
                        @php
                            $sr = count($compulsoryCourses) + 1;
                        @endphp

                        @foreach ($electiveGroups as $group)
                            <tr class="row-gray">

                                <td colspan="17" class="text-left font-semibold">

                                    {{ $group->name }} :
                                    ANY {{ $group->min_select_count }} OF THE FOLLOWING

                                </td>

                            </tr>


                            @php

                                $courses = $group->courses;
                                $rowCount = $courses->count();

                                $serialNumbers = [];

                                for ($i = 0; $i < $group->min_select_count; $i++) {
                                    $serialNumbers[] = '0' . ($sr + $i);
                                }

                                $sr += $group->min_select_count;

                                $serialText = implode(' & ', $serialNumbers);

                            @endphp


                            @foreach ($group->courses as $index => $course)
                                @php

                                    $dc = $course->departmentCourses->first();

                                    $details = $dc->courseDetails;

                                    $totalH = $details->th_hrs + $details->tu_hrs + $details->pr_hrs;

                                    $rowMarks =
                                        $details->th_marks +
                                        $details->test_marks +
                                        $details->pr_marks +
                                        $details->or_marks +
                                        $details->tw_marks;

                                    /*
|--------------------------------------------------------------------------
Add elective totals (only once per group)
|--------------------------------------------------------------------------
*/

                                    if ($index === 0) {
                                        $multiplier = $group->min_select_count;

                                        $totalTH += $details->th_hrs * $multiplier;
                                        $totalTU += $details->tu_hrs * $multiplier;
                                        $totalPR += $details->pr_hrs * $multiplier;

                                        $totalHours += $totalH * $multiplier;

                                        $totalCredits += $details->credits * $multiplier;

                                        $totalTHMarks += $details->th_marks * $multiplier;
                                        $totalTest += $details->test_marks * $multiplier;
                                        $totalPRMarks += $details->pr_marks * $multiplier;
                                        $totalOR += $details->or_marks * $multiplier;
                                        $totalTW += $details->tw_marks * $multiplier;

                                        $totalMarks += $rowMarks * $multiplier;
                                    }

                                @endphp


                                <tr class="row-hover">

                                    @if ($index == 0)
                                        <td class="nowrap" rowspan="{{ $rowCount }}">

                                            {{ str_pad($serialText, 2, '0', STR_PAD_LEFT) }}

                                        </td>
                                    @endif


                                    <td>

                                        {{ $details->course_code }}

                                    </td>


                                    <td class="text-left">

                                        {{ $course->title }}

                                    </td>


                                    <td>

                                        {{ $course->abbrevation }}

                                    </td>


                                    <td>{{ $details->th_hrs ?: '--' }}</td>
                                    <td>{{ $details->tu_hrs ?: '--' }}</td>
                                    <td>{{ $details->pr_hrs ?: '--' }}</td>

                                    <td>{{ $totalH }}</td>

                                    <td>{{ $details->credits }}</td>


                                    <td>{{ $details->th_paper_hrs ?: '--' }}</td>
                                    <td>{{ $details->th_marks ?: '--' }}</td>

                                    <td>{{ $details->test_marks ?: '--' }}</td>
                                    <td>{{ $details->pr_marks ?: '--' }}</td>
                                    <td>{{ $details->or_marks ?: '--' }}</td>
                                    <td>{{ $details->tw_marks ?: '--' }}</td>


                                    <td class="font-medium">

                                        {{ $rowMarks }}

                                    </td>

                                </tr>
                            @endforeach
                        @endforeach



                        <tr class="row-total">

                            <td colspan="4">

                                TOTAL

                            </td>


                            <td>{{ $totalTH }}</td>
                            <td>{{ $totalTU }}</td>
                            <td>{{ $totalPR }}</td>

                            <td>{{ $totalHours }}</td>

                            <td>{{ $totalCredits }}</td>

                            <td>--</td>

                            <td>{{ $totalTHMarks }}</td>
                            <td>{{ $totalTest }}</td>
                            <td>{{ $totalPRMarks }}</td>
                            <td>{{ $totalOR }}</td>
                            <td>{{ $totalTW }}</td>

                            <td>{{ $totalMarks }}</td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>



        <div class="card">

            <p class="mb-2">

                Level :
                <strong>{{ $level->order_no }}</strong>

            </p>


            <p class="mb-2">

                Total Courses :
                <strong>{{ $sr - 1 }}</strong>

            </p>


            <p class="mb-2">

                Total Credits :
                <strong>{{ $totalCredits }}</strong>

            </p>


            <p>

                Total Marks :
                <strong>{{ $totalMarks }}</strong>

            </p>

        </div>
    @else
        <div class="card">

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th rowspan="3">Sr.</th>
                            <th rowspan="3">Course Code</th>
                            <th rowspan="3">Course Title</th>
                            <th rowspan="3">Course Abbr.</th>

                            <th colspan="5">

                                TEACHING SCHEME

                            </th>

                        </tr>


                        <tr>

                            <th colspan="4">Hours per Week</th>
                            <th rowspan="2">Total Credits</th>

                        </tr>


                        <tr>

                            <th>TH</th>
                            <th>TU</th>
                            <th>PR</th>
                            <th>Total Hours</th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr class="row-gray">

                            <td colspan="9" class="text-left font-semibold">

                                Any of {{ $auditDetails->compulsory_to_complete }} the following

                            </td>

                        </tr>


                        @php
                            $sr = 1;
                        @endphp



                        @foreach ($compulsoryCourses as $dc)
                            @php

                                $details = $dc->courseDetails;

                                $totalH = $details->th_hrs + $details->tu_hrs + $details->pr_hrs;

                            @endphp


                            <tr class="row-hover">

                                <td>

                                    {{ str_pad($sr++, 2, '0', STR_PAD_LEFT) }}

                                </td>


                                <td>

                                    {{ $details->course_code }}

                                </td>


                                <td class="text-left">

                                    {{ $dc->course->title }}

                                </td>


                                <td>

                                    {{ $dc->course->abbrevation }}

                                </td>


                                <td>

                                    {{ $details->th_hrs ?: '--' }}

                                </td>


                                <td>

                                    {{ $details->tu_hrs ?: '--' }}

                                </td>


                                <td>

                                    {{ $details->pr_hrs ?: '--' }}

                                </td>


                                <td>

                                    {{ $totalH }}

                                </td>


                                <td>

                                    --

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>



        <div class="card">

            <p class="font-semibold mb-2">

                Audit Courses :

            </p>


            <p class="text-gray mb-2">

                Total Courses :
                <strong>{{ $auditDetails->compulsory_to_complete }}</strong>

            </p>


            <p class="text-gray mb-2">

                Total Credits :
                <strong>Nil</strong>

            </p>


            <p class="text-gray">

                Total Marks :
                <strong>--</strong>

            </p>

        </div>

    @endif

</div>
