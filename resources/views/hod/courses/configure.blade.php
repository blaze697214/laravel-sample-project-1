@extends('layouts.hod')

@section('content')

<h2>
LEVEL - {{ $level->order_no }}
</h2>

<h3>
{{ strtoupper($level->name) }}
</h3>


<form method="POST" action="">
{{-- {{ route('hod.courses.preview', $level->id) }} --}}
@csrf


<table cellpadding="6">

<thead>
<tr>

<th>Sr</th>
<th>Course Code</th>
<th>Course Title</th>
<th>Abbr</th>

<th>TH</th>
<th>TU</th>
<th>PR</th>

<th>Total Hours</th>

<th>Credits</th>

<th>TH Marks</th>
<th>Test</th>
<th>PR</th>
<th>OR</th>
<th>TW</th>

</tr>

</thead>


<tbody>

@foreach($courses as $index => $dc)

@php
$course = $dc->course;
$details = $dc->courseDetails ?? null;
@endphp

<tr>

<td>
{{ $index + 1 }}
</td>


<td>

<input type="text"
name="course_code[{{ $dc->id }}]"
value="{{ old('course_code.'.$dc->id, $details->course_code ?? '') }}">

</td>


<td>

{{ $course->title }}

</td>


<td>

{{ $course->abbrevation }}

</td>


<td>

<input class="th_hrs"
type="number"
name="th_hrs[{{ $dc->id }}]"
value="{{ old('th_hrs.'.$dc->id, $details->th_hrs ?? '') }}">

</td>


<td>

<input class="tu_hrs"
type="number"
name="tu_hrs[{{ $dc->id }}]"
value="{{ old('tu_hrs.'.$dc->id, $details->tu_hrs ?? '') }}">

</td>


<td>

<input class="pr_hrs"
type="number"
name="pr_hrs[{{ $dc->id }}]"
value="{{ old('pr_hrs.'.$dc->id, $details->pr_hrs ?? '') }}">

</td>


<td>

<input class="credits"
type="number"
name="credits[{{ $dc->id }}]"
value="{{ old('credits.'.$dc->id, $details->credits ?? '') }}">

</td>


<td>

<input class="th_marks"
type="number"
name="th_marks[{{ $dc->id }}]"
value="{{ old('th_marks.'.$dc->id, $details->th_marks ?? '') }}">

</td>


<td>

<input class="test_marks"
type="number"
name="test_marks[{{ $dc->id }}]"
value="{{ old('test_marks.'.$dc->id, $details->test_marks ?? '') }}">

</td>


<td>

<input class="pr_marks"
type="number"
name="pr_marks[{{ $dc->id }}]"
value="{{ old('pr_marks.'.$dc->id, $details->pr_marks ?? '') }}">

</td>


<td>

<input class="or_marks"
type="number"
name="or_marks[{{ $dc->id }}]"
value="{{ old('or_marks.'.$dc->id, $details->or_marks ?? '') }}">

</td>


<td>

<input class="tw_marks"
type="number"
name="tw_marks[{{ $dc->id }}]"
value="{{ old('tw_marks.'.$dc->id, $details->tw_marks ?? '') }}">

</td>

</tr>

@endforeach

    



<tr>

<td colspan="4">
TOTAL
</td>

<td id="total_th">0</td>
<td id="total_tu">0</td>
<td id="total_pr">0</td>

<td id="total_credits">0</td>

<td id="total_th_marks">0</td>
<td id="total_test_marks">0</td>
<td id="total_pr_marks">0</td>
<td id="total_or_marks">0</td>
<td id="total_tw_marks">0</td>

</tr>


</tbody>

</table>


<br>

<button type="submit">
Preview
</button>


</form>



<script>

function updateTotals() {

let th = 0
let tu = 0
let pr = 0
let credits = 0

let th_marks = 0
let test = 0
let pr_marks = 0
let or_marks = 0
let tw_marks = 0


document.querySelectorAll('.th_hrs').forEach(e => th += Number(e.value || 0))
document.querySelectorAll('.tu_hrs').forEach(e => tu += Number(e.value || 0))
document.querySelectorAll('.pr_hrs').forEach(e => pr += Number(e.value || 0))

document.querySelectorAll('.credits').forEach(e => credits += Number(e.value || 0))

document.querySelectorAll('.th_marks').forEach(e => th_marks += Number(e.value || 0))
document.querySelectorAll('.test_marks').forEach(e => test += Number(e.value || 0))
document.querySelectorAll('.pr_marks').forEach(e => pr_marks += Number(e.value || 0))
document.querySelectorAll('.or_marks').forEach(e => or_marks += Number(e.value || 0))
document.querySelectorAll('.tw_marks').forEach(e => tw_marks += Number(e.value || 0))


document.getElementById('total_th').innerText = th
document.getElementById('total_tu').innerText = tu
document.getElementById('total_pr').innerText = pr

document.getElementById('total_credits').innerText = credits

document.getElementById('total_th_marks').innerText = th_marks
document.getElementById('total_test_marks').innerText = test
document.getElementById('total_pr_marks').innerText = pr_marks
document.getElementById('total_or_marks').innerText = or_marks
document.getElementById('total_tw_marks').innerText = tw_marks

}

document.querySelectorAll('input').forEach(i => {
i.addEventListener('input', updateTotals)
})

updateTotals()

</script>


@endsection
