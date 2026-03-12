@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Programme Level Configuration
    </h1>

        @if ($errors->has('scheme'))
        <div id="errorMessage" class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded">

            {{ $errors->first('scheme') }}

        </div>
    @endif

    <script>
        setTimeout(function(){
            let box = document.getElementById('errorMessage');

            if(box){
                box.style.display = 'none';
            }
        },2000);
    </script>



    <div class="bg-white p-6 rounded-xl shadow">

        <div class="overflow-x-auto">

            <table class="w-full text-left border border-gray-200">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Programme
                        </th>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Status
                        </th>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @foreach ($programmes as $programme)
                        <tr class="hover:bg-gray-50 border-gray-200">

                            <td class="px-4 py-3">

                                <span class="font-medium text-gray-800">
                                    {{ $programme->name }}
                                </span>

                                @if ($programme->abbreviation)
                                    <span class="text-gray-500 text-sm ml-1">
                                        ({{ $programme->abbreviation }})
                                    </span>
                                @endif

                            </td>


                            <td class="px-4 py-3">

                                @if (in_array($programme->id, $configured))
                                    <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded">
                                        Configured
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded">
                                        Not Configured
                                    </span>
                                @endif

                            </td>


                            <td class="px-4 py-3">

                                <a href="{{ route('cdc.schemes.programmeLevels.create', [$schemeId, $programme->id]) }}">

                                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-sm">
                                        Configure
                                    </button>

                                </a>

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>
        <div class="mt-6 flex justify-between">

            <!-- Back & Edit (go back to courses configuration page) -->
            <a href="{{ route('cdc.schemes.courses.create', $schemeId) }}">

                <button class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                    Back & Edit Courses
                </button>

            </a>


            <!-- Save Scheme -->
            <form method="POST" action="{{ route('cdc.schemes.finalize', $schemeId) }}">

                @csrf

                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Save Scheme
                </button>

            </form>

        </div>

    </div>
@endsection
