@extends('layouts.hod')

@section('content')

<h2>Class Award Configuration</h2>

@if($levels->isEmpty())

<p>
No configured levels found. Please complete course configuration before creating class award configuration.
</p>

@else

<form method="POST" action="{{ route('hod.classAward.store') }}">

@csrf
<input type="hidden" name="scheme_id" value="{{ $scheme->id }}">


<div>
    <label>Total Courses for Class Award</label>
    <input
        type="number"
        name="total_class_award_courses"
        value="{{ old('total_class_award_courses', $config->total_class_award_courses ?? '') }}"
        required
    >
</div>

<hr>

<h3>Compulsory Courses</h3>

@foreach($levels as $level)

<details open>

<summary>
<strong>
Level {{ $level->order_no }} : {{ $level->name }}
</strong>
</summary>

<table border="1" cellpadding="6" style="margin-top:10px">

<thead>
<tr>
<th>Select</th>
<th>Course Title</th>
<th>Abbreviation</th>
</tr>
</thead>

<tbody>

@foreach($compulsoryCourses[$level->id] ?? [] as $dc)

<tr>

<td>
<input
type="checkbox"
name="compulsory_courses[]"
value="{{ $dc->course->id }}"
@if(isset($config) && $config->compulsoryCourses->contains($dc->course->id)) checked @endif
>
</td>

<td>
{{ $dc->course->title }}
</td>

<td>
{{ $dc->course->abbrevation }}
</td>

</tr>

@endforeach

</tbody>

</table>

</details>

@endforeach


<hr>

<h3>Elective Groups</h3>

<table border="1" cellpadding="6">

<thead>
<tr>
<th>Select</th>
<th>Group Name</th>
<th>Group Courses</th>
<th>Min Select</th>
</tr>
</thead>

<tbody>

@foreach($electiveGroups as $group)

<tr>

<td>
<input
type="checkbox"
name="elective_groups[]"
value="{{ $group->id }}"
data-min="{{ $group->min_select_count }}"
@if(isset($config) && $config->electiveGroups->contains($group->id)) checked @endif
>
</td>

<td>
{{ $group->name }}
</td>

<td>
@foreach($group->courses as $course)
{{ $course->abbrevation }}@if(!$loop->last), @endif
@endforeach
</td>

<td>
{{ $group->min_select_count }}
</td>

</tr>

@endforeach

</tbody>

</table>


<hr>

<p>
Selected Course Count :
<strong id="selectedCount">0</strong>
</p>

<button type="submit">
Save Configuration
</button>

</form>


<script>

function updateCount(){

let compulsory = document.querySelectorAll(
'input[name="compulsory_courses[]"]:checked'
).length

let elective = 0

document.querySelectorAll(
'input[name="elective_groups[]"]:checked'
).forEach(function(e){

elective += Number(e.dataset.min)

})

document.getElementById('selectedCount').innerText =
compulsory + elective

}

document.querySelectorAll('input[type="checkbox"]').forEach(function(cb){

cb.addEventListener('change',updateCount)

})

updateCount()

</script>

@endif

@endsection