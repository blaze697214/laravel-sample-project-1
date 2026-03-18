@extends('layouts.hod')

@section('content')

    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Class Award Configuration
    </h1>


    @if ($levels->isEmpty())
        <div class="bg-white p-6 rounded-xl shadow">

            <p class="text-gray-600">
                No configured levels found. Please complete course configuration before creating class award configuration.
            </p>

        </div>
    @else
        <form method="POST" action="{{ route('hod.classAward.store') }}">

            @csrf

            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">



            {{-- Total Courses --}}
            <div class="bg-white p-6 rounded-xl shadow mb-8">

                <label class="block text-sm font-medium text-gray-600 mb-2">
                    Total Courses for Class Award
                </label>

                <input type="number" name="total_class_award_courses"
                    value="{{ old('total_class_award_courses', $config->total_class_award_courses ?? '') }}" required
                    class="border border-gray-300 rounded px-3 py-2 w-64">

            </div>



            {{-- Compulsory Courses --}}
            <div class="bg-white p-6 rounded-xl shadow mb-8">

                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    Compulsory Courses
                </h2>

                @foreach ($levels as $level)
                    <details @if($errors->any()) open @endif class="mb-4 border border-gray-200 rounded-lg">

                        <summary class="cursor-pointer bg-gray-100 px-4 py-2 font-medium text-gray-700">

                            Level {{ $level->order_no }} : {{ $level->name }}

                        </summary>


                        <div class="p-4">

                            <div class="overflow-x-auto">

                                <table class="w-full text-left border border-gray-200">

                                    <thead class="bg-gray-100">

                                        <tr>

                                            <th class="px-4 py-2 text-sm font-semibold text-gray-600">
                                                Select
                                            </th>

                                            <th class="px-4 py-2 text-sm font-semibold text-gray-600">
                                                Course Title
                                            </th>

                                            <th class="px-4 py-2 text-sm font-semibold text-gray-600">
                                                Abbreviation
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody class="divide-y">

                                        @foreach ($compulsoryCourses[$level->id] ?? [] as $dc)
                                            <tr class="hover:bg-gray-50 border-gray-200">

                                                <td class="px-4 py-2">

                                                    <input type="checkbox" name="compulsory_courses[]"
                                                        value="{{ $dc->course->id }}"
                                                        @if (in_array($dc->course->id, old('compulsory_courses', [])) ||
                                                                (isset($config) && $config->compulsoryCourses->contains($dc->course->id))) checked @endif>

                                                </td>

                                                <td class="px-4 py-2">
                                                    {{ $dc->course->title }}
                                                </td>

                                                <td class="px-4 py-2">
                                                    {{ $dc->course->abbrevation }}
                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </details>
                @endforeach

            </div>



            {{-- Elective Groups --}}
            <div class="bg-white p-6 rounded-xl shadow mb-8">

                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    Elective Groups
                </h2>

                <div class="overflow-x-auto">

                    <table class="w-full text-left border border-gray-200">

                        <thead class="bg-gray-100">

                            <tr>

                                <th class="px-4 py-2 text-sm font-semibold text-gray-600">
                                    Select
                                </th>

                                <th class="px-4 py-2 text-sm font-semibold text-gray-600">
                                    Group Name
                                </th>

                                <th class="px-4 py-2 text-sm font-semibold text-gray-600">
                                    Group Courses
                                </th>

                                <th class="px-4 py-2 text-sm font-semibold text-gray-600">
                                    Min Select
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y">

                            @foreach ($electiveGroups as $group)
                                <tr class="hover:bg-gray-50 border-gray-200">

                                    <td class="px-4 py-2">

                                        <input type="checkbox" name="elective_groups[]" value="{{ $group->id }}"
                                            data-min="{{ $group->min_select_count }}"
                                            @if (in_array($group->id, old('elective_groups', [])) ||
                                                    (isset($config) && $config->electiveGroups->contains($group->id))) checked @endif>

                                    </td>

                                    <td class="px-4 py-2">
                                        {{ $group->name }}
                                    </td>

                                    <td class="px-4 py-2 text-sm">

                                        @foreach ($group->courses as $course)
                                            <span class="inline-flex items-center bg-gray-200 text-gray-700 text-xs font-medium px-2 py-1 rounded mr-1 mb-1">   
                                                {{ $course->abbrevation }}
                                            </span>
                                        @endforeach

                                    </td>

                                    <td class="px-4 py-2">
                                        {{ $group->min_select_count }}
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>



            {{-- Selected Count --}}
            <div class="bg-white p-6 rounded-xl shadow mb-8">

                <p class="text-gray-700">
                    Selected Course Count :
                    <strong id="selectedCount">0</strong>
                </p>

            </div>



            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-medium">

                Save & Preview

            </button>

        </form>



        <script>
            function updateCount() {

                let compulsory =
                    document.querySelectorAll(
                        'input[name="compulsory_courses[]"]:checked'
                    ).length

                let elective = 0

                document.querySelectorAll(
                    'input[name="elective_groups[]"]:checked'
                ).forEach(function(e) {

                    elective += Number(e.dataset.min)

                })

                document.getElementById('selectedCount').innerText =
                    compulsory + elective

            }

            document.querySelectorAll('input[type="checkbox"]').forEach(function(cb) {

                cb.addEventListener('change', updateCount)

            })

            updateCount()
        </script>
    @endif

@endsection
