@extends('layouts.hod')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 text-center mb-2">
        PROGRAMME STRUCTURE
    </h1>

    @if (!$level->is_audit)
        <h2 class="text-lg font-semibold text-center text-gray-700">
            LEVEL - {{ $level->order_no }}
        </h2>
    @endif

    <h2 class="text-lg font-semibold text-center text-gray-700 mb-6">
        {{ strtoupper($level->name) }}
    </h2>


    @if (!$level->is_audit)
        <div class="bg-white p-6 rounded-xl shadow mb-8">

            <div class="overflow-x-auto">

                <table class="w-full text-sm border border-gray-200 text-center">

                    <thead class="bg-gray-100">

                        <tr>

                            <th rowspan="3" class="px-3 py-2 border">Sr.</th>
                            <th rowspan="3" class="px-3 py-2 border">Course Code</th>
                            <th rowspan="3" class="px-3 py-2 border">Course Title</th>
                            <th rowspan="3" class="px-3 py-2 border">Course Abbr.</th>

                            <th colspan="5" class="px-3 py-2 border">TEACHING SCHEME</th>

                            <th colspan="7" class="px-3 py-2 border">EXAMINATION SCHEME</th>

                        </tr>

                        <tr>

                            <th colspan="4" class="px-3 py-2 border">Hours per Week</th>
                            <th rowspan="2" class="px-3 py-2 border">Total Credits</th>

                            <th colspan="2" class="px-3 py-2 border">Theory Paper</th>

                            <th rowspan="2" class="px-3 py-2 border">Test</th>
                            <th rowspan="2" class="px-3 py-2 border">PR</th>
                            <th rowspan="2" class="px-3 py-2 border">OR</th>
                            <th rowspan="2" class="px-3 py-2 border">TW</th>
                            <th rowspan="2" class="px-3 py-2 border">Total</th>

                        </tr>

                        <tr>

                            <th class="px-3 py-2 border">TH</th>
                            <th class="px-3 py-2 border">TU</th>
                            <th class="px-3 py-2 border">PR</th>
                            <th class="px-3 py-2 border">Total Hours</th>

                            <th class="px-3 py-2 border">Hrs</th>
                            <th class="px-3 py-2 border">Mark</th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

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


                            <tr class="hover:bg-gray-50">

                                <td class="px-3 py-2 border">
                                    {{ str_pad($sr++, 2, '0', STR_PAD_LEFT) }}
                                </td>

                                <td class="px-3 py-2 border">
                                    {{ $details->course_code }}
                                </td>

                                <td class="px-3 py-2 border text-left">
                                    {{ $dc->course->title }}
                                </td>

                                <td class="px-3 py-2 border">
                                    {{ $dc->course->abbrevation }}
                                </td>

                                <td class="px-3 py-2 border">{{ $details->th_hrs ?: '--' }}</td>
                                <td class="px-3 py-2 border">{{ $details->tu_hrs ?: '--' }}</td>
                                <td class="px-3 py-2 border">{{ $details->pr_hrs ?: '--' }}</td>

                                <td class="px-3 py-2 border">{{ $totalH }}</td>

                                <td class="px-3 py-2 border">{{ $details->credits }}</td>

                                <td class="px-3 py-2 border">{{ $details->th_paper_hrs ?: '--' }}</td>
                                <td class="px-3 py-2 border">{{ $details->th_marks ?: '--' }}</td>

                                <td class="px-3 py-2 border">{{ $details->test_marks ?: '--' }}</td>
                                <td class="px-3 py-2 border">{{ $details->pr_marks ?: '--' }}</td>
                                <td class="px-3 py-2 border">{{ $details->or_marks ?: '--' }}</td>
                                <td class="px-3 py-2 border">{{ $details->tw_marks ?: '--' }}</td>

                                <td class="px-3 py-2 border font-medium">
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
                            $sr = count($compulsoryCourses)+1;
                        @endphp

                        @foreach ($electiveGroups as $group)
                            <tr class="bg-gray-100">

                                <td colspan="17" class="px-3 py-2 text-left font-semibold">

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
                                @endphp


                                <tr class="hover:bg-gray-50">

                                    @if ($index == 0)
                                        <td class="px-3 py-2 border whitespace-nowrap" rowspan="{{ $rowCount }}">
                                            {{ str_pad($serialText, 2, '0', STR_PAD_LEFT) }}
                                        </td>
                                    @endif

                                    <td class="px-3 py-2 border">
                                        {{ $details->course_code }}
                                    </td>

                                    <td class="px-3 py-2 border text-left">
                                        {{ $course->title }}
                                    </td>

                                    <td class="px-3 py-2 border">
                                        {{ $course->abbrevation }}
                                    </td>

                                    <td class="px-3 py-2 border">{{ $details->th_hrs ?: '--' }}</td>
                                    <td class="px-3 py-2 border">{{ $details->tu_hrs ?: '--' }}</td>
                                    <td class="px-3 py-2 border">{{ $details->pr_hrs ?: '--' }}</td>

                                    <td class="px-3 py-2 border">{{ $totalH }}</td>

                                    <td class="px-3 py-2 border">{{ $details->credits }}</td>

                                    <td class="px-3 py-2 border">{{ $details->th_paper_hrs ?: '--' }}</td>
                                    <td class="px-3 py-2 border">{{ $details->th_marks ?: '--' }}</td>
                                    <td class="px-3 py-2 border">{{ $details->test_marks ?: '--' }}</td>
                                    <td class="px-3 py-2 border">{{ $details->pr_marks ?: '--' }}</td>
                                    <td class="px-3 py-2 border">{{ $details->or_marks ?: '--' }}</td>
                                    <td class="px-3 py-2 border">{{ $details->tw_marks ?: '--' }}</td>

                                    <td class="px-3 py-2 border font-medium">
                                        {{ $rowMarks }}
                                    </td>

                                </tr>
                            @endforeach
                        @endforeach



                        <tr class="bg-gray-100 font-semibold">

                            <td colspan="4" class="px-3 py-2 border">
                                TOTAL
                            </td>

                            <td class="border">{{ $totalTH }}</td>
                            <td class="border">{{ $totalTU }}</td>
                            <td class="border">{{ $totalPR }}</td>

                            <td class="border">{{ $totalHours }}</td>

                            <td class="border">{{ $totalCredits }}</td>

                            <td class="border">--</td>

                            <td class="border">{{ $totalTHMarks }}</td>
                            <td class="border">{{ $totalTest }}</td>
                            <td class="border">{{ $totalPRMarks }}</td>
                            <td class="border">{{ $totalOR }}</td>
                            <td class="border">{{ $totalTW }}</td>

                            <td class="border">{{ $totalMarks }}</td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>
        <div class="bg-white p-6 rounded-xl shadow mb-8">

            <p class="mb-2">
                Level : <strong>{{ $level->order_no }}</strong>
            </p>

            <p class="mb-2">
                Total Courses : <strong>{{ $sr - 1 }}</strong>
            </p>

            <p class="mb-2">
                Total Credits : <strong>{{ $totalCredits }}</strong>
            </p>

            <p>
                Total Marks : <strong>{{ $totalMarks }}</strong>
            </p>

        </div>
    @else
        <div class="bg-white p-6 rounded-xl shadow mb-8">

            <div class="overflow-x-auto">

                <table class="w-full text-sm border border-gray-200 text-center">

                    <thead class="bg-gray-100">

                        <tr>
                            <th rowspan="3" class="px-3 py-2 border">Sr.</th>
                            <th rowspan="3" class="px-3 py-2 border">Course Code</th>
                            <th rowspan="3" class="px-3 py-2 border">Course Title</th>
                            <th rowspan="3" class="px-3 py-2 border">Course Abbr.</th>

                            <th colspan="5" class="px-3 py-2 border">TEACHING SCHEME</th>
                        </tr>

                        <tr>
                            <th colspan="4" class="px-3 py-2 border">Hours per Week</th>
                            <th rowspan="2" class="px-3 py-2 border">Total Credits</th>
                        </tr>

                        <tr>
                            <th class="px-3 py-2 border">TH</th>
                            <th class="px-3 py-2 border">TU</th>
                            <th class="px-3 py-2 border">PR</th>
                            <th class="px-3 py-2 border">Total Hours</th>
                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        <tr class="bg-gray-100 border-1">
                            <td colspan="9" class="px-3 py-2 text-left font-semibold">
                                Any of the following
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

                            <tr class="hover:bg-gray-50">

                                <td class="px-3 py-2 border">
                                    {{ str_pad($sr++, 2, '0', STR_PAD_LEFT) }}
                                </td>

                                <td class="px-3 py-2 border">
                                    {{ $details->course_code }}
                                </td>

                                <td class="px-3 py-2 border text-left">
                                    {{ $dc->course->title }}
                                </td>

                                <td class="px-3 py-2 border">
                                    {{ $dc->course->abbrevation }}
                                </td>

                                <td class="px-3 py-2 border">
                                    {{ $details->th_hrs ?: '--' }}
                                </td>

                                <td class="px-3 py-2 border">
                                    {{ $details->tu_hrs ?: '--' }}
                                </td>

                                <td class="px-3 py-2 border">
                                    {{ $details->pr_hrs ?: '--' }}
                                </td>

                                <td class="px-3 py-2 border">
                                    {{ $totalH }}
                                </td>

                                <td class="px-3 py-2 border">
                                    --
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>



        <div class="bg-white p-6 rounded-xl shadow">

            <p class="mb-2 font-semibold">
                Audit Courses :
            </p>

            <p class="text-gray-700">
                Total Courses :
                <strong>04</strong>
            </p>

            <p class="text-gray-700">
                Total Credits :
                <strong>Nil</strong>
            </p>

            <p class="text-gray-700">
                Total Marks :
                <strong>--</strong>
            </p>

        </div>
    @endif







        <div class="flex gap-4 mt-9">

            <a href="{{ route('hod.courses.configure', $level->id) }}">

                <button class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded">
                    Back & Edit
                </button>

            </a>


            <form method="POST" action="">
                {{-- {{ route('hod.courses.save',$level->id) }} --}}
                @csrf

                <input type="hidden" name="scheme_id" value="{{ $level->curriculum_year_id }}">

                <button class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">
                    Save Level Configuration
                </button>

            </form>

        </div>
    @endsection
