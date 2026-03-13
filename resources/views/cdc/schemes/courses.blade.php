@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-2">
        Configure Courses
    </h1>

    <p class="text-gray-600 mb-6">
        Scheme: {{ $scheme->year_start }} - {{ $scheme->year_end }}
    </p>


    @if (session('success'))
        <div id="successMessage" class="bg-green-100 border border-green-300 text-green-700 px-4 py-2 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div id="successMessage" class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded">
            <ul class="list-disc pl-5">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>
        </div>
    @endif



    <!-- Add Course -->

    <div class="bg-white p-6 rounded-xl shadow mb-8">

        <h3 class="text-lg font-semibold text-gray-700 mb-6">
            Add Course
        </h3>

        <form method="POST" action="{{ route('cdc.schemes.courses.store', $scheme->id) }}" class="space-y-5">

            @csrf


            <!-- Title -->
            <div class="flex w-full gap-10">
                <div class="basis-2/3">

                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Title
                    </label>

                    <input type="text" name="title" required value="{{ old('title') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">

                </div>


                <!-- Abbreviation -->
                <div class="basis-1/3">

                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Abbreviation
                    </label>

                    <input type="text" name="abbrevation" required value="{{ old('abbrevation') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">

                </div>
            </div>

            <!-- Level -->
            <div>

                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Level
                </label>

                <select name="level_id" class="border border-gray-300 rounded-lg px-3 py-2">

                    @foreach ($levels as $level)
                        <option value="{{ $level->id }}">
                            {{ $level->name }}
                        </option>
                    @endforeach

                </select>

            </div>



            <!-- Department Mapping -->

            <h4 class="text-md font-semibold text-gray-700 mt-6">
                Department Mapping
            </h4>


            <div class="overflow-x-auto">

                <table class="w-full max-w-md text-left border border-gray-200">

                    <thead class="bg-gray-100">

                        <tr>
                            <th class="px-4 py-2 text-sm font-semibold text-gray-600">
                                Department
                            </th>

                            <th class="px-4 py-2 text-sm font-semibold text-gray-600 text-center">
                                Offered
                            </th>

                            <th class="px-4 py-2 text-sm font-semibold text-gray-600 text-center">
                                Elective
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @foreach ($programmeDepartments as $department)
                            <tr class="hover:bg-gray-50 border-gray-200">

                                <td class="px-4 py-2">
                                    {{ $department->name }}
                                </td>


                                <td class="px-4 py-2 text-center">

                                    <input type="checkbox" name="offered[]" value="{{ $department->id }}"
                                        data-department="{{ $department->id }}"data-role="offered"
                                        data-name="{{ $department->name }}"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded">

                                </td>


                                <td class="px-4 py-2 text-center">

                                    <input type="checkbox" name="elective[{{ $department->id }}]"
                                        data-department="{{ $department->id }}" data-role="elective" disabled
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded">

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

                <!-- Owner Department -->

                <div class="mt-6">

                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Owner Department
                    </label>

                    <select name="owner_department_id" id="owner_department"
                        class="border border-gray-300 rounded-lg px-3 py-2 w-full">

                        <option value="">
                            -- Select Owner Department --
                        </option>

                        {{-- Service departments always appear --}}
                        @foreach ($serviceDepartments as $department)
                            <option value="{{ $department->id }}" data-type="service">

                                {{ $department->name }}

                            </option>
                        @endforeach

                    </select>

                </div>

            </div>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium transition">
                Add Course
            </button>

        </form>

    </div>



    <!-- Courses List -->

    @foreach ($levels as $level)
        <div class="bg-white p-6 rounded-xl shadow mb-6">

            <h3 class="text-lg font-semibold text-gray-700 mb-4">
                {{ $level->name }}
            </h3>


            <div class="overflow-x-auto">

                <table class="w-full text-left border border-gray-200">

                    <thead class="bg-gray-100">

                        <tr>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600 w-100">Title</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600 w-60">Abbrev</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600 w-60">Departments</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600 text-center">Actions</th>
                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @if (isset($courses[$level->id]))
                            @foreach ($courses[$level->id] as $course)
                                <tr class="hover:bg-gray-50 border-gray-200">

                                    <form method="POST" action="{{ route('cdc.schemes.courses.update', $course->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <td class="px-4 py-3">
                                            <input type="text" name="title" value="{{ $course->title }}"
                                                class="border border-gray-300 rounded px-2 py-1 w-60">
                                        </td>


                                        <td class="px-4 py-3">
                                            <input type="text" name="abbrevation" value="{{ $course->abbrevation }}"
                                                class="border border-gray-300 rounded px-2 py-1 w-30">
                                        </td>


                                        <td class="px-4 py-3 white-space:normal">

                                            @foreach ($course->departments as $dept)
                                                <span
                                                    class="inline-flex items-center bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded mr-1 mb-1">

                                                    {{ $dept->abbrevation }}

                                                    @if ($dept->pivot->is_elective)
                                                        <span class="ml-1 text-purple-600 font-medium">
                                                            (Elective)
                                                        </span>
                                                    @endif

                                                </span>
                                            @endforeach

                                        </td>


                                        <td class="px-4 py-3 space-x-2 flex justify-center">

                                            <button type="submit"
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm w-30">
                                                Edit
                                            </button>
                                    </form>

                                    <form method="POST" action="{{ route('cdc.schemes.courses.destroy', $course->id) }}">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm w-30    ">
                                            Delete
                                        </button>
                                    </form>

                                    </td>

                                </tr>
                            @endforeach
                        @endif

                    </tbody>

                </table>

            </div>

        </div>
    @endforeach


    <div class="mt-6 flex justify-between">

        <!-- Back & Edit (go back to courses configuration page) -->
        <a href="{{ route('cdc.schemes.levels.create', $scheme->id) }}">

            <button class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                Back & Edit Courses
            </button>

        </a>



        <a href="{{ route('cdc.schemes.courses.next', $scheme->id) }}">

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                Next → Department Level Configuration
            </button>

        </a>
    </div>

    <script>
        document.querySelectorAll('input[data-role="offered"]').forEach(function(offered) {

            offered.addEventListener('change', function() {

                let departmentId = this.dataset.department;

                let elective = document.querySelector(
                    'input[data-role="elective"][data-department="' + departmentId + '"]'
                );

                if (this.checked) {

                    elective.disabled = false;

                } else {

                    elective.checked = false;
                    elective.disabled = true;

                }

            });

        });
        setTimeout(function() {

            const msg = document.getElementById('successMessage');

            if (msg) {
                msg.style.display = 'none';
            }

        }, 2000);

        document.querySelectorAll('[data-role="offered"]').forEach(function(checkbox) {

            checkbox.addEventListener('change', updateOwnerDropdown);

        });

        function updateOwnerDropdown() {

            const dropdown = document.getElementById('owner_department');

            const serviceOptions = dropdown.querySelectorAll('option[data-type="service"]');

            // remove previous programme options
            dropdown.querySelectorAll('option[data-type="programme"]').forEach(o => o.remove());

            document.querySelectorAll('[data-role="offered"]:checked').forEach(function(cb) {

                const option = document.createElement('option');

                option.value = cb.value;

                option.text = cb.dataset.name;

                option.setAttribute('data-type', 'programme');

                dropdown.appendChild(option);

            });

        }
    </script>
@endsection
