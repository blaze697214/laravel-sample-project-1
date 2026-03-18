<style>
    
    body{
    font-family: Arial, Helvetica, sans-serif;
}

/* PAGE */

.page-container{
    page-break-inside: avoid;
}

/* HEADINGS */

.title-main{
    font-size:20px;
    font-weight:bold;
    text-align:center;
    margin-bottom:10px;
}

.title-sub{
    font-size:16px;
    font-weight:bold;
    text-align:center;
    margin-bottom:10px;
}

/* CARD */

.card{
    background:#ffffff;
    padding:10px;
    border:1px solid #ccc;
    margin-bottom:10px;
}

/* TABLE */

table{
    width:100%;
    border-collapse:collapse;
    table-layout:fixed;
    font-size:9px;
}

/* HEADER */

thead{
    background:#f0f0f0;
}

th{
    border:1px solid #000;
    padding:2px;
    font-weight:600;
    line-height:1.1;
}

/* CELLS */

td{
    border:1px solid #000;
    padding:2px;
    text-align:center;
}

/* COURSE TITLE COLUMN */

.text-left{
    text-align:left;
    white-space:normal;
    word-wrap:break-word;
}

/* ROW COLORS */

.row-gray{
    background:#f0f0f0;
}

/* HOVER (not used in PDF but safe) */

.row-hover:hover{
    background:#fafafa;
}

/* TOTAL ROW */

.total-row{
    font-weight:bold;
}

table th:nth-child(1){width:3%;}
table th:nth-child(2){width:6%;}
table th:nth-child(3){width:18%;}
table th:nth-child(4){width:6%;}

table th:nth-child(5),
table th:nth-child(6),
table th:nth-child(7){width:4%;}

table th:nth-child(8){width:5%;}
table th:nth-child(9){width:5%;}

table th:nth-child(10),
table th:nth-child(11){width:5%;}

table th:nth-child(12),
table th:nth-child(13),
table th:nth-child(14),
table th:nth-child(15),
table th:nth-child(16){width:4%;}

table th:nth-child(17){width:6%;}

/* SUMMARY */

.summary p{
    font-size:11px;
    margin-bottom:4px;
}

/* PREVENT ROW BREAKS */

tr{
    page-break-inside: avoid;
}

/* REPEAT TABLE HEADER */

thead{
    display:table-header-group;
}
</style>



<h1 class="title-main">
    PROGRAMME - DIPLOMA IN {{ strtoupper($department->name) }}
</h1>

<h1 class="title-sub">
    Courses for Award of Class
</h1>


<div class="page-container">
    <div class="card">

        

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
                                $serialNumbers[] = sprintf('%02d', $sr + $i);
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
                                    <td rowspan="{{ $rowCount }}">
                                        {{ $serialText }}
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



                    <tr class="row-gray font-semibold">

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



    <div class="card summary">

        <p>
            Total Courses : <strong>{{ $sr - 1 }}</strong>
        </p>

        <p>
            Total Credits : <strong>{{ $totalCredits }}</strong>
        </p>

        <p>
            Total Marks : <strong>{{ $totalMarks }}</strong>
        </p>

    </div>

</div>
