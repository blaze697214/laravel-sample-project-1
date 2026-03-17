@extends('layouts.hod')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Class Award Configuration
    </h1>


    <div class="bg-white p-6 rounded-xl shadow mb-8">

        <p class="text-gray-700">
            Department :
            <strong>{{ $department->name }}</strong>
        </p>

        <p class="text-gray-600 mt-1">
            Scheme :
            <strong>{{ $scheme->year_start }} - {{ $scheme->year_end }}</strong>
        </p>

    </div>



    <form method="POST" action="{{ route('hod.classAward.store') }}">

        <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

        @csrf


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
                <details class="mb-3 border rounded-lg">

                    <summary class="cursor-pointer px-4 py-2 bg-gray-100 font-medium">

                        Level {{ $level->order_no }} - {{ $level->name }}

                    </summary>

                    <div class="p-4">

                        <table class="w-full text-left border border-gray-200">

                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2">Select</th>
                                    <th class="px-4 py-2">Course Code</th>
                                    <th class="px-4 py-2">Course Title</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y">

                                @foreach ($compulsoryCourses->where('level_id', $level->id) as $course)
                                    <tr class="hover:bg-gray-50">

                                        <td class="px-4 py-2">

                                            <input type="checkbox" name="compulsory_courses[]"
                                                value="{{ $course->course->id }}"
                                                @if (isset($config) && $config->compulsoryCourses->contains($course->course->id)) checked @endif>

                                        </td>

                                        <td class="px-4 py-2">
                                            {{ $course->course->code ?? '' }}
                                        </td>

                                        <td class="px-4 py-2">
                                            {{ $course->course->title }}
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

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
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Select</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Group Name</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Min Select</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y">

                        @foreach ($electiveGroups as $group)
                            <tr class="hover:bg-gray-50 border-gray-200">

                                <td class="px-4 py-3">

                                    <input type="checkbox" name="elective_groups[]" value="{{ $group->id }}"
                                        data-min="{{ $group->min_select_count }}"
                                        @if (isset($config) && $config->electiveGroups->contains($group->id)) checked @endif>

                                </td>

                                <td class="px-4 py-3">
                                    {{ $group->name }}
                                </td>

                                <td class="px-4 py-3">
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



        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">

            Save Configuration

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
            ).forEach(e => {

                elective += Number(e.dataset.min)

            })

            document.getElementById('selectedCount').innerText =
                compulsory + elective

        }

        document.querySelectorAll(
            'input[type="checkbox"]'
        ).forEach(cb => {

            cb.addEventListener('change', updateCount)

        })

        updateCount()
    </script>
@endsection
