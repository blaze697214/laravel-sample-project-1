@extends('layouts.hod')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Elective Groups
    </h1>


    {{-- Elective Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <p class="text-gray-500 text-sm">Total Elective Courses</p>
            <h2 class="text-3xl font-bold text-blue-600 mt-2">
                {{ $totalElectives }}
            </h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <p class="text-gray-500 text-sm">Grouped Courses</p>
            <h2 class="text-3xl font-bold text-green-600 mt-2">
                {{ $groupedElectives }}
            </h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <p class="text-gray-500 text-sm">Remaining Courses</p>
            <h2 class="text-3xl font-bold text-red-600 mt-2">
                {{ $remainingElectives }}
            </h2>
        </div>

    </div>



    {{-- Level Selector --}}
    <div class="bg-white p-6 rounded-xl shadow mb-8">

        <form method="GET" action="{{ route('hod.electives') }}">

            <label class="block text-sm font-medium text-gray-600 mb-2">
                Select Level
            </label>

            <select name="level" onchange="this.form.submit()" class="border border-gray-300 rounded px-3 py-2">
                <option value="">Select Level</option>

                @foreach ($levels as $level)
                    <option value="{{ $level->id }}" {{ request('level') == $level->id ? 'selected' : '' }}>

                        {{ $level->name }}

                    </option>
                @endforeach

            </select>

        </form>

    </div>



    {{-- Create Elective Group --}}
    <div class="bg-white p-6 rounded-xl shadow mb-10">

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Create Elective Group
        </h2>

        <form id="course-selection" data-max="{{ old('max_select_count') }}" method="POST"
            action="{{ route('hod.electives.store') }}">

            @csrf

            <input type="hidden" name="scheme_id" value="{{ $activeScheme->id }}">
            <input type="hidden" name="level_id" value="{{ request('level') }}">


            <div class="grid grid-cols-3 gap-6 mb-6">

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Group Name</label>

                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded px-3 py-2">
                </div>


                <div>
                    <label class="block text-sm text-gray-600 mb-1">Minimum Selection</label>

                    <input type="number" name="min_select_count" value="{{ old('min_select_count') }}" required
                        class="w-full border border-gray-300 rounded px-3 py-2">
                </div>


                <div>
                    <label class="block text-sm text-gray-600 mb-1">Maximum Selection</label>

                    <input type="number" name="max_select_count" value="{{ old('max_select_count') }}" required
                        class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

            </div>



            <div class="mb-6">

                <h4 class="font-semibold text-gray-700 mb-3">
                    Select Courses
                </h4>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">

                    @forelse ($availableCourses as $course)
                        <label class="flex items-center space-x-2 text-sm">

                            <input type="checkbox" name="courses[]" value="{{ $course->id }}">

                            <span>{{ $course->title }}</span>

                        </label>
                    @empty
                        <div class="text-center w-full text-gray-500 py-3">
                            No electives available
                        </div>
                    @endforelse

                </div>

            </div>


            <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
                Create Group
            </button>

        </form>

    </div>



    {{-- Existing Elective Groups --}}
    <div class="bg-white p-6 rounded-xl shadow">

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Elective Groups
        </h2>


        <div class="overflow-x-auto">

            <table class="w-full text-left border border-gray-200">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Group Name</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Level</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Min Select</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Max Select</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Courses</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Actions</th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @foreach ($groups as $group)
                        <tr class="hover:bg-gray-50 border-gray-200">

                            <td class="px-4 py-3">
                                {{ $group->name }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $group->level->name }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $group->min_select_count }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $group->max_select_count }}
                            </td>

                            <td class="px-4 py-3 text-sm whitespace-normal w-150">

                                @foreach ($group->courses as $course)
                                    <span class="inline-block bg-gray-200 px-2 py-1 rounded text-xs mr-1 mb-1">
                                        {{ $course->title }}
                                    </span>
                                @endforeach

                            </td>


                            <td class="px-4 py-3">

                                <form method="POST" action="{{ route('hod.electives.destroy', $group->id) }}"
                                    onsubmit="return confirm('Delete this elective group?')">
                                    <input type="hidden" name="scheme_id" value="{{ $activeScheme->id }}">

                                    @csrf
                                    @method('DELETE')

                                    <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                        Delete
                                    </button>

                                </form>

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>
    <script>
        const maxInput = document.querySelector('[name="max_select_count"]');
        const checkboxes = document.querySelectorAll('input[name="courses[]"]');

        function updateCheckboxLimit() {

            const max = parseInt(maxInput.value);
            const checked = document.querySelectorAll('input[name="courses[]"]:checked').length;

            checkboxes.forEach(cb => {

                if (!cb.checked && checked >= max) {
                    cb.disabled = true;
                } else {
                    cb.disabled = false;
                }

            });

        }

        checkboxes.forEach(cb => cb.addEventListener('change', updateCheckboxLimit));
        maxInput.addEventListener('input', updateCheckboxLimit);
    </script>
@endsection
