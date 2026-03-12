@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-16">
        Create Scheme
    </h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-2 rounded mb-6">
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('success'))
        <div class="mb-4 p-4 rounded bg-green-100 text-green-800 border border-green-300" id="success-box">

            {{ session('success') }}

        </div>

        <script>
            setTimeout(function() {

                let box = document.getElementById('success-box');

                if (box) {
                    box.style.display = 'none';
                }

            },2000);
        </script>
    @endif


    <form method="POST" class="flex justify-center" action="{{ route('cdc.schemes.store') }}">
        @csrf


        <div class="bg-white p-8 rounded-xl shadow w-full max-w-sm ">

            <h3 class="text-lg font-semibold text-gray-700 mb-6">
                Scheme Information
            </h3>


            <!-- Year Start -->
            <div class="mb-5 flex flex-col justify-center item">

                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Year Start
                </label>

                <select name="year_start" id="yearStart" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                    <option value="">Select Year</option>

                    @for ($y = date('Y'); $y <= date('Y') + 5; $y++)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor

                </select>

            </div>



            <!-- Year End -->
            <div class="mb-5">

                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Year End
                </label>

                <input type="text" id="yearEnd" readonly
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100">

            </div>



            <!-- Total Credits -->
            <div class="mb-5">

                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Total Credits
                </label>

                <input type="number" name="total_credits" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">

            </div>



            <!-- Total Marks -->
            <div class="mb-6">

                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Total Marks
                </label>

                <input type="number" name="total_marks" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">

            </div>



            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition w-full">
                Next → Configure Levels
            </button>

        </div>

    </form>



    <script>
        const yearStart = document.getElementById('yearStart');
        const yearEnd = document.getElementById('yearEnd');

        yearStart.addEventListener('change', function() {

            let start = parseInt(this.value);

            if (start) {
                yearEnd.value = start + 3;
            }

        });
    </script>
@endsection
