@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-2">
        Configure Levels
    </h1>

    <p class="text-gray-600 mb-16">
        Scheme: {{ $scheme->year_start }} - {{ $scheme->year_end }}
    </p>


    @if (session('success'))
        <div id="successMessage" class="bg-green-100 border border-green-300 text-green-700 px-4 py-2 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->has('levels'))
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded">

            {{ $errors->first('levels') }}

        </div>
    @endif



    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">


        <!-- Add Level Form -->

        <div class="bg-white p-6 rounded-xl shadow">

            <h3 class="text-lg font-semibold text-gray-700 mb-4">
                Add Level
            </h3>

            <form method="POST" action="{{ route('cdc.schemes.levels.store', $scheme->id) }}" class="space-y-4">

                @csrf

                <div>
                    <label class="block text-sm text-gray-600 mb-1">
                        Level Name
                    </label>

                    <input type="text" name="name" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>


                <div class="flex items-center gap-2">
                    @if ($levels->where('is_audit', 1)->count())
                    @else
                        <input type="checkbox" name="is_audit" class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                        <label class="text-sm text-gray-600">
                            Audit Level
                        </label>
                    @endif
                </div>


                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                    Add Level
                </button>

            </form>

        </div>



        <!-- Existing Levels -->

        <div class="bg-white p-6 rounded-xl shadow lg:col-span-2">

            <h3 class="text-lg font-semibold text-gray-700 mb-4">
                Levels
            </h3>

            <div class="overflow-x-auto">

                <table class="w-full text-left border border-gray-200">

                    <thead class="bg-gray-100">

                        <tr>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Name</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Level</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600 text-center">Action</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y">

                        @foreach ($levels as $level)
                            <tr class="hover:bg-gray-50 border-gray-200">

                                <form method="POST" action="{{ route('cdc.schemes.levels.update', $level->id) }}">

                                    @csrf
                                    @method('PUT')

                                    <td class="px-4 py-3">
                                        <input type="text" name="name" value="{{ $level->name }}"
                                            class="border border-gray-300 rounded px-2 py-1 w-60">
                                    </td>


                                    <td class="px-4 py-3 ">
                                        {{ $level->order_no }}
                                    </td>

                                    {{-- <td class="px-4 py-3">
                                    {{ $level->is_audit ? 'Yes' : 'No' }}
                                </td> --}}

                                    <td class="px-4 py-3 flex gap-2 justify-center">
                                        <button type="submit"
                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                            Update
                                        </button>
                                </form>

                                <form method="POST" action="{{ route('cdc.schemes.levels.destroy', $level->id) }}">

                                    @csrf
                                    @method('DELETE')

                                    <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                        Delete
                                    </button>

                                </form>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>


            <div class="mt-6">

                {{-- <a href="{{ route('cdc.schemes.courses.create', $scheme->id) }}">

                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium transition">
                        Next → Courses
                    </button>

                </a> --}}
                <a href="{{ route('cdc.schemes.levels.next', $scheme->id) }}">

                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium transition">
                        Next → Courses
                    </button>

                </a>

            </div>

        </div>

    </div>



    <script>
        setTimeout(function() {

            const msg = document.getElementById('successMessage');

            if (msg) {
                msg.style.display = 'none';
            }

        }, 2000);
    </script>
@endsection
