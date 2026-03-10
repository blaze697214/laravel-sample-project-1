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

                    <input type="text" name="title" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">

                </div>


                <!-- Abbreviation -->
                <div class="basis-1/3">

                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Abbreviation
                    </label>

                    <input type="text" name="abbrevation" required
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



            <!-- Programme Mapping -->

            <h4 class="text-md font-semibold text-gray-700 mt-6">
                Programme Mapping
            </h4>


            <div class="overflow-x-auto">

                <table class="w-full max-w-md text-left border border-gray-200">

                    <thead class="bg-gray-100">

                        <tr>
                            <th class="px-4 py-2 text-sm font-semibold text-gray-600">
                                Programme
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

                        @foreach ($programmes as $programme)
                            <tr class="hover:bg-gray-50 border-gray-200">

                                <td class="px-4 py-2">
                                    {{ $programme->name }}
                                </td>


                                <td class="px-4 py-2 text-center">

                                    <input type="checkbox" name="offered[]" value="{{ $programme->id }}"
                                        data-programme="{{ $programme->id }}"data-role="offered"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded">

                                </td>


                                <td class="px-4 py-2 text-center">

                                    <input type="checkbox" name="elective[{{ $programme->id }}]"
                                        data-programme="{{ $programme->id }}" data-role="elective" disabled
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded">

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

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
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600 w-60">Programmes</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600 text-center">Actions</th>
                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @if (isset($courses[$level->id]))
                            @foreach ($courses[$level->id] as $course)
                                <tr class="hover:bg-gray-50">

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

                                            @foreach ($course->programmes as $prog)
                                                <span
                                                    class="inline-flex items-center bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded mr-1 mb-1">

                                                    {{ $prog->abbrevation }}

                                                    @if ($prog->pivot->is_elective)
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



    <div class="mt-6">

        <a href="{{ route('cdc.schemes.programmeLevels.index', $scheme->id) }}">

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                Next → Programme Level Configuration
            </button>

        </a>

    </div>

    <script>
        document.querySelectorAll('input[data-role="offered"]').forEach(function(offered) {

            offered.addEventListener('change', function() {

                let programmeId = this.dataset.programme;

                let elective = document.querySelector(
                    'input[data-role="elective"][data-programme="' + programmeId + '"]'
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
    </script>
@endsection
