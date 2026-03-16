@extends('layouts.hod')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-2">
        LEVEL - {{ $level->order_no }}
    </h1>

    <h2 class="text-lg font-semibold text-gray-700 mb-6">
        {{ strtoupper($level->name) }}
    </h2>

    @php
        $year = substr($activeScheme->year_start, -2);
    @endphp


    <form method="POST" action="{{ route('hod.courses.store', $level->id) }}">

        @csrf
        <input type="hidden" name="scheme_id" value="{{ $level->curriculum_year_id }}">

        <div class="bg-white p-6 rounded-xl shadow">

            <div class="overflow-x-auto">

                <table class="w-full text-sm border border-gray-200 ">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="px-3 py-2 border border-gray-300">Sr</th>
                            <th class="px-3 py-2 border border-gray-300">Course Code</th>
                            <th class="px-3 py-2 border border-gray-300">Course Title</th>
                            <th class="px-3 py-2 border border-gray-300">Abbr</th>

                            <th class="px-3 py-2 border border-gray-300">TH</th>
                            <th class="px-3 py-2 border border-gray-300">TU</th>
                            <th class="px-3 py-2 border border-gray-300">PR</th>

                            <th class="px-3 py-2 border border-gray-300">Total Hours</th>

                            <th class="px-3 py-2 border border-gray-300">Credits</th>

                            <th class="px-3 py-2 border border-gray-300">Paper Hrs</th>


                            <th class="px-3 py-2 border border-gray-300">TH Marks</th>
                            <th class="px-3 py-2 border border-gray-300">Test</th>
                            <th class="px-3 py-2 border border-gray-300">PR</th>
                            <th class="px-3 py-2 border border-gray-300">OR</th>
                            <th class="px-3 py-2 border border-gray-300">TW</th>
                            <th class="px-3 py-2 border border-gray-300">Total</th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @php
                            $sr = 0;
                        @endphp


                        @foreach ($compulsoryCourses as $index => $dc)
                            @php
                                $course = $dc->course;
                                $details = $dc->courseDetails ?? null;
                            @endphp

                            <tr class="hover:bg-gray-50">

                                <td class="px-2 py-2 border border-gray-200 border-gray-200 text-center">
                                    {{  str_pad($index + 1, 2, '0', STR_PAD_LEFT)}}
                                </td>


                                <td class="border border-gray-200 px-2 py-2">

                                    <div class="flex items-center gap-2">
                                        <label>{{ $year . $level->order_no }}</label>

                                        <input type="text" name="course_code[{{ $dc->id }}]"
                                            value="{{ substr(old('course_code.' . $dc->id, $details->course_code ?? ''),-2) }}"
                                            class="w-10 border border-gray-200 border-gray-300 rounded px-2 py-1">
                                    </div>

                                </td>


                                <td class="border border-gray-200 px-2 py-2">
                                    {{ $course->title }}
                                </td>

                                <td class="border border-gray-200 px-2 py-2">
                                    {{ $course->abbrevation }}
                                </td>


                                <td class="border border-gray-200 px-2 py-2">

                                    <input class="th_hrs w-20 border border-gray-200 border-gray-300 rounded px-2 py-1"
                                        type="number" name="th_hrs[{{ $dc->id }}]"
                                        value="{{ old('th_hrs.' . $dc->id, $details->th_hrs ?? '') }}">

                                </td>


                                <td class="border border-gray-200 px-2 py-2">

                                    <input class="tu_hrs w-20 border border-gray-200 border-gray-300 rounded px-2 py-1"
                                        type="number" name="tu_hrs[{{ $dc->id }}]"
                                        value="{{ old('tu_hrs.' . $dc->id, $details->tu_hrs ?? '') }}">

                                </td>


                                <td class="border border-gray-200 px-2 py-2">

                                    <input class="pr_hrs w-20 border border-gray-200 border-gray-300 rounded px-2 py-1"
                                        type="number" name="pr_hrs[{{ $dc->id }}]"
                                        value="{{ old('pr_hrs.' . $dc->id, $details->pr_hrs ?? '') }}">

                                </td>


                                <td class="border border-gray-200 px-2 py-2 total_hours text-center font-medium">
                                    0
                                </td>


                                <td class="border border-gray-200 px-2 py-2">

                                    <input class="credits w-20 border border-gray-200 border-gray-300 rounded px-2 py-1"
                                        type="number" name="credits[{{ $dc->id }}]"
                                        value="{{ old('credits.' . $dc->id, $details->credits ?? '') }}">

                                </td>

                                <td class="border border-gray-200 px-2 py-2">

                                    <input
                                        class="th_paper_hrs w-20 border border-gray-200 border-gray-300 rounded px-2 py-1"
                                        type="number" name="th_paper_hrs[{{ $dc->id }}]"
                                        value="{{ old('th_paper_hrs.' . $dc->id, $details->th_paper_hrs ?? '') }}">

                                </td>


                                <td class="border border-gray-200 px-2 py-2">

                                    <input class="th_marks w-20 border border-gray-200 border-gray-300 rounded px-2 py-1"
                                        type="number" name="th_marks[{{ $dc->id }}]"
                                        value="{{ old('th_marks.' . $dc->id, $details->th_marks ?? '') }}">

                                </td>


                                <td class="border border-gray-200 px-2 py-2">

                                    <input class="test_marks w-20 border border-gray-200 border-gray-300 rounded px-2 py-1"
                                        type="number" name="test_marks[{{ $dc->id }}]"
                                        value="{{ old('test_marks.' . $dc->id, $details->test_marks ?? '') }}">

                                </td>


                                <td class="border border-gray-200 px-2 py-2">

                                    <input class="pr_marks w-20 border border-gray-200 border-gray-300 rounded px-2 py-1"
                                        type="number" name="pr_marks[{{ $dc->id }}]"
                                        value="{{ old('pr_marks.' . $dc->id, $details->pr_marks ?? '') }}">

                                </td>


                                <td class="border border-gray-200 px-2 py-2">

                                    <input class="or_marks w-20 border border-gray-200 border-gray-300 rounded px-2 py-1"
                                        type="number" name="or_marks[{{ $dc->id }}]"
                                        value="{{ old('or_marks.' . $dc->id, $details->or_marks ?? '') }}">

                                </td>


                                <td class="border border-gray-200 px-2 py-2">

                                    <input class="tw_marks w-20 border border-gray-200 border-gray-300 rounded px-2 py-1"
                                        type="number" name="tw_marks[{{ $dc->id }}]"
                                        value="{{ old('tw_marks.' . $dc->id, $details->tw_marks ?? '') }}">

                                </td>


                                <td class="border border-gray-200 px-2 py-2 row_total_marks text-center font-medium">
                                    0
                                </td>

                            </tr>
                        @endforeach

                        @php
                            $sr = count($compulsoryCourses)+1;
                        @endphp

                        @foreach ($electiveGroups as $group)
                            <tr class="bg-gray-100">

                                <td colspan="15" class="px-3 py-2 font-semibold text-gray-700">

                                    {{ $group->name }} : ANY {{ $group->min_select_count }} OF THE FOLLOWING

                                </td>

                            </tr>

                            @php
                                $courses = $group->courses;
                                $rowCount = $courses->count();
                                $serialNumbers = [];
                                for ($i = 0; $i < $group->min_select_count; $i++) {
                                    $serialNumbers[] = '0'.($sr + $i);
                                }
                                $sr += $group->min_select_count;
                                $serialText = implode(' & ', $serialNumbers);
                            @endphp

                            @foreach ($courses as $index => $course)
                                @php
                                    $dc = $course->departmentCourses->first();
                                    $details = $dc->courseDetails ?? null;
                                @endphp


                                <tr class="hover:bg-gray-50 ">

                                    @if ($index == 0)
                                        <td class="border border-gray-200 px-2 py-2 text-center whitespace-nowrap   "
                                            rowspan="{{ $rowCount }}">
                                            {{ str_pad($serialText, 2, '0', STR_PAD_LEFT) }}</td>
                                    @endif


                                    <td class="border border-gray-200 px-2 py-2">

                                        <div class="flex items-center gap-2">
                                            <label>{{ $year . $level->order_no }}</label>

                                            <input type="text" name="course_code[{{ $dc->id }}]"
                                                value="{{ substr(old('course_code.' . $dc->id, $details->course_code ?? ''),-2) }}"
                                                class="w-10 border border-gray-200 border-gray-300 rounded px-2 py-1">
                                        </div>

                                    </td>


                                    <td class="border border-gray-200 px-2 py-2">
                                        {{ $course->title }}
                                    </td>

                                    <td class="border border-gray-200 px-2 py-2">
                                        {{ $course->abbrevation }}
                                    </td>


                                    <td class="border border-gray-200 px-2 py-2">

                                        <input class="th_hrs w-20 border border-gray-200 border-gray-300 rounded px-2 py-1"
                                            type="number" name="th_hrs[{{ $dc->id }}]"
                                            value="{{ old('th_hrs.' . $dc->id, $details->th_hrs ?? '') }}">

                                    </td>


                                    <td class="border border-gray-200 px-2 py-2">

                                        <input class="tu_hrs w-20 border border-gray-200 border-gray-300 rounded px-2 py-1"
                                            type="number" name="tu_hrs[{{ $dc->id }}]"
                                            value="{{ old('tu_hrs.' . $dc->id, $details->tu_hrs ?? '') }}">

                                    </td>


                                    <td class="border border-gray-200 px-2 py-2">

                                        <input class="pr_hrs w-20 border border-gray-200 border-gray-300 rounded px-2 py-1"
                                            type="number" name="pr_hrs[{{ $dc->id }}]"
                                            value="{{ old('pr_hrs.' . $dc->id, $details->pr_hrs ?? '') }}">

                                    </td>


                                    <td class="border border-gray-200 px-2 py-2 total_hours text-center font-medium">
                                        0
                                    </td>


                                    <td class="border border-gray-200 px-2 py-2">

                                        <input
                                            class="credits w-20 border border-gray-200 border-gray-300 rounded px-2 py-1"
                                            type="number" name="credits[{{ $dc->id }}]"
                                            value="{{ old('credits.' . $dc->id, $details->credits ?? '') }}">

                                    </td>

                                    <td class="border border-gray-200 px-2 py-2">

                                        <input
                                            class="th_paper_hrs w-20 border border-gray-200 border-gray-300 rounded px-2 py-1"
                                            type="number" name="th_paper_hrs[{{ $dc->id }}]"
                                            value="{{ old('th_paper_hrs.' . $dc->id, $details->th_paper_hrs ?? '') }}">

                                    </td>


                                    <td class="border border-gray-200 px-2 py-2">

                                        <input
                                            class="th_marks w-20 border border-gray-200 border-gray-300 rounded px-2 py-1"
                                            type="number" name="th_marks[{{ $dc->id }}]"
                                            value="{{ old('th_marks.' . $dc->id, $details->th_marks ?? '') }}">

                                    </td>


                                    <td class="border border-gray-200 px-2 py-2">

                                        <input
                                            class="test_marks w-20 border border-gray-200 border-gray-300 rounded px-2 py-1"
                                            type="number" name="test_marks[{{ $dc->id }}]"
                                            value="{{ old('test_marks.' . $dc->id, $details->test_marks ?? '') }}">

                                    </td>


                                    <td class="border border-gray-200 px-2 py-2">

                                        <input
                                            class="pr_marks w-20 border border-gray-200 border-gray-300 rounded px-2 py-1"
                                            type="number" name="pr_marks[{{ $dc->id }}]"
                                            value="{{ old('pr_marks.' . $dc->id, $details->pr_marks ?? '') }}">

                                    </td>


                                    <td class="border border-gray-200 px-2 py-2">

                                        <input
                                            class="or_marks w-20 border border-gray-200 border-gray-300 rounded px-2 py-1"
                                            type="number" name="or_marks[{{ $dc->id }}]"
                                            value="{{ old('or_marks.' . $dc->id, $details->or_marks ?? '') }}">

                                    </td>


                                    <td class="border border-gray-200 px-2 py-2">

                                        <input
                                            class="tw_marks w-20 border border-gray-200 border-gray-300 rounded px-2 py-1"
                                            type="number" name="tw_marks[{{ $dc->id }}]"
                                            value="{{ old('tw_marks.' . $dc->id, $details->tw_marks ?? '') }}">

                                    </td>


                                    <td class="border border-gray-200 px-2 py-2 row_total_marks text-center font-medium">
                                        0
                                    </td>

                                </tr>
                            @endforeach
                        @endforeach



                        <tr class="bg-gray-100 font-semibold">

                            <td colspan="4" class="px-3 py-2">
                                TOTAL
                            </td>

                            <td id="total_th" class="px-3 py-2 text-center">0</td>
                            <td id="total_tu" class="px-3 py-2 text-center">0</td>
                            <td id="total_pr" class="px-3 py-2 text-center">0</td>

                            <td id="total_hours" class="px-3 py-2 text-center"></td>

                            <td id="total_credits" class="px-3 py-2 text-center">0</td>
                            <td> </td>

                            <td id="total_th_marks" class="px-3 py-2 text-center">0</td>
                            <td id="total_test_marks" class="px-3 py-2 text-center">0</td>
                            <td id="total_pr_marks" class="px-3 py-2 text-center">0</td>
                            <td id="total_or_marks" class="px-3 py-2 text-center">0</td>
                            <td id="total_tw_marks" class="px-3 py-2 text-center">0</td>

                            <td id="total_marks" class="px-3 py-2 text-center"></td>

                        </tr>


                    </tbody>

                </table>

            </div>

            <div class="mt-6 flex justify-between">

                {{-- <a href="{{ route('hod.courses') }}">

                    <button class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                        Back & Configure Levels
                    </button>

                </a> --}}

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">

                    Save & Preview

                </button>

            </div>

        </div>

    </form>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {

        function updateTotals() {
            let th = 0,
                tu = 0,
                pr = 0,
                totalHours = 0
            let credits = 0
            let thMarks = 0,
                test = 0,
                prMarks = 0,
                orMarks = 0,
                twMarks = 0
            let totalMarks = 0

            document.querySelectorAll('tbody tr').forEach(row => {

                let thInput = row.querySelector('.th_hrs')
                if (!thInput) return // skip header/footer rows

                let tuInput = row.querySelector('.tu_hrs')
                let prInput = row.querySelector('.pr_hrs')

                let thv = Number(thInput.value || 0)
                let tuv = Number(tuInput?.value || 0)
                let prv = Number(prInput?.value || 0)

                let rowHours = thv + tuv + prv

                row.querySelector('.total_hours').innerText = rowHours

                th += thv
                tu += tuv
                pr += prv
                totalHours += rowHours

                let creditInput = row.querySelector('.credits')
                credits += Number(creditInput?.value || 0)

                let thm = Number(row.querySelector('.th_marks')?.value || 0)
                let tm = Number(row.querySelector('.test_marks')?.value || 0)
                let prm = Number(row.querySelector('.pr_marks')?.value || 0)
                let orm = Number(row.querySelector('.or_marks')?.value || 0)
                let twm = Number(row.querySelector('.tw_marks')?.value || 0)

                let rowTotalMarks = thm + tm + prm + orm + twm
                row.querySelector('.row_total_marks').innerText = rowTotalMarks

                thMarks += thm
                test += tm
                prMarks += prm
                orMarks += orm
                twMarks += twm

                totalMarks += rowTotalMarks

            })

            document.getElementById('total_th').innerText = th
            document.getElementById('total_tu').innerText = tu
            document.getElementById('total_pr').innerText = pr
            document.getElementById('total_hours').innerText = totalHours
            document.getElementById('total_credits').innerText = credits
            document.getElementById('total_th_marks').innerText = thMarks
            document.getElementById('total_test_marks').innerText = test
            document.getElementById('total_pr_marks').innerText = prMarks
            document.getElementById('total_or_marks').innerText = orMarks
            document.getElementById('total_tw_marks').innerText = twMarks
            document.getElementById('total_marks').innerText = totalMarks
        }

        // Attach input listener to all inputs in tbody
        document.querySelectorAll('tbody input').forEach(i => {
            i.addEventListener('input', updateTotals)
        })

        // Initial calculation
        updateTotals()

    })
</script>
