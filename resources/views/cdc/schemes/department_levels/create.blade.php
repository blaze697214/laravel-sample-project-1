@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-2">
        Configure Department Levels
    </h1>

    <p class="mb-6 text-gray-600">
        Department: {{ $department->name }}
    </p>

    @if ($errors->any())
        <div id="successMessage" class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded">

            <ul class="list-disc pl-5">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>
    @endif

    <script>
        setTimeout(function() {

            const msg = document.getElementById('successMessage');

            if (msg) {
                msg.style.display = 'none';
            }

        }, 2000);
    </script>


    <div class="bg-white p-6 rounded-xl shadow">

        <form method="POST" action="{{ route('cdc.schemes.departmentLevels.store', [$scheme->id, $department->id]) }}">

            @csrf
            {{-- {{ dd(old()) }} --}}

            <div class="overflow-x-auto">

                <table class="w-full text-left border border-gray-200">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">Level</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">Courses Offered</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">Compulsory</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">Elective</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">TH</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">TU</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">PR</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">Credits</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">Marks</th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @foreach ($levels as $level)
                            @php
                                $row = $existing[$level->id] ?? null;
                            @endphp

                            <tr class="hover:bg-gray-50 border-gray-200">

                                <td class="px-3 py-2 font-medium text-gray-800">

                                    {{ $level->name }}

                                    <input type="hidden" name="levels[]" value="{{ $level->id }}" required>

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="courses_offered[{{ $level->id }}]"
                                        value="{{ old('courses_offered.' . $level->id, $row->courses_offered ?? '') }}"
                                        required class="courses_offered w-20 border border-gray-300 rounded px-2 py-1">

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="compulsory[{{ $level->id }}]"
                                        value="{{ old('compulsory.' . $level->id, $row->compulsory_to_complete ?? '') }}"
                                        required class="compulsory w-20 border border-gray-300 rounded px-2 py-1">

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="elective[{{ $level->id }}]"
                                        value="{{ old('elective.' . $level->id, $row->elective_to_complete ?? '') }}"
                                        class="elective w-20 border border-gray-300 rounded px-2 py-1">

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="th[{{ $level->id }}]"
                                        value="{{ old('th.' . $level->id, $row->th_hrs ?? '') }}"
                                        class="th w-16 border border-gray-300 rounded px-2 py-1">

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="tu[{{ $level->id }}]"
                                        value="{{ old('tu.' . $level->id, $row->tu_hrs ?? '') }}"
                                        class="tu w-16 border border-gray-300 rounded px-2 py-1">

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="pr[{{ $level->id }}]"
                                        value="{{ old('pr.' . $level->id, $row->pr_hrs ?? '') }}"
                                        class="pr w-16 border border-gray-300 rounded px-2 py-1">

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="credits[{{ $level->id }}]"
                                        value="{{ old('credits.' . $level->id, $row->credits ?? '') }}" required
                                        class="credits w-16 border border-gray-300 disabled:bg-gray-200 rounded px-2 py-1"
                                        @if ($level->is_audit) disabled @endif>

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="marks[{{ $level->id }}]"
                                        value="{{ old('marks.' . $level->id, $row->marks ?? '') }}"
                                        class="marks w-16 border border-gray-300 rounded disabled:bg-gray-200 px-2 py-1"
                                        @if ($level->is_audit) disabled @endif>

                                </td>

                            </tr>
                        @endforeach
                        <tr class="bg-gray-100 font-semibold">
                            <td class="px-3 py-2">Total</td>

                            <td class="px-3 py-2" id="total_offered"></td>
                            <td class="px-3 py-2" id="total_compulsory"></td>
                            <td class="px-3 py-2" id="total_elective"></td>
                            <td class="px-3 py-2" id="total_th"></td>
                            <td class="px-3 py-2" id="total_tu"></td>
                            <td class="px-3 py-2" id="total_pr"></td>
                            <td class="px-3 py-2" id="total_credits"></td>
                            <td class="px-3 py-2" id="total_marks"></td>
                        </tr>

                    </tbody>

                </table>

            </div>


            <div class="mt-6">

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">

                    Save & Preview

                </button>

            </div>


        </form>

    </div>

    <script>
        function calculateTotals() {

            function sum(cls) {
                let total = 0;

                document.querySelectorAll("." + cls).forEach(el => {
                    total += parseFloat(el.value) || 0;
                });

                return total;
            }

            document.getElementById('total_offered').innerText = sum('courses_offered');
            document.getElementById('total_compulsory').innerText = sum('compulsory');
            document.getElementById('total_elective').innerText = sum('elective');
            document.getElementById('total_th').innerText = sum('th');
            document.getElementById('total_tu').innerText = sum('tu');
            document.getElementById('total_pr').innerText = sum('pr');
            document.getElementById('total_credits').innerText = sum('credits');
            document.getElementById('total_marks').innerText = sum('marks');
        }

        document.addEventListener("input", function(e) {

            if (
                e.target.classList.contains('courses_offered') ||
                e.target.classList.contains('compulsory') ||
                e.target.classList.contains('elective') ||
                e.target.classList.contains('th') ||
                e.target.classList.contains('tu') ||
                e.target.classList.contains('pr') ||
                e.target.classList.contains('credits') ||
                e.target.classList.contains('marks')
            ) {
                calculateTotals();
            }

        });

        window.onload = calculateTotals;
    </script>
@endsection
