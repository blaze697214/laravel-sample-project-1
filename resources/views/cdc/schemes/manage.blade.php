@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Manage Schemes
    </h1>


    @if (session('success'))
        <div id="successMessage" class="mb-6 px-4 py-3 bg-green-100 border border-green-300 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->has('scheme'))
        <div id="successMessage" class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded">

            {{ $errors->first('scheme') }}

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

        <div class="overflow-x-auto">

            <table class="w-full text-left border border-gray-200">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Scheme
                        </th>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Active
                        </th>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Locked
                        </th>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @foreach ($schemes as $scheme)
                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ $scheme->year_start }} - {{ $scheme->year_end }}
                            </td>


                            <td class="px-4 py-3">

                                <form method="POST" action="{{ route('cdc.schemes.toggleActive', $scheme->id) }}">

                                    @csrf

                                    <button
                                        class="px-3 py-1 text-sm rounded
{{ $scheme->is_active
    ? 'bg-green-600 hover:bg-green-700 text-white'
    : 'bg-gray-300 hover:bg-gray-400 text-gray-700' }}">

                                        {{ $scheme->is_active ? 'Active' : 'Inactive' }}

                                    </button>

                                </form>

                            </td>



                            <td class="px-4 py-3">

                                <form method="POST" action="{{ route('cdc.schemes.toggleLock', $scheme->id) }}">

                                    @csrf

                                    <button
                                        class="px-3 py-1 text-sm rounded
{{ $scheme->is_locked
    ? 'bg-red-600 hover:bg-red-700 text-white'
    : 'bg-gray-300 hover:bg-gray-400 text-gray-700' }}">

                                        {{ $scheme->is_locked ? 'Locked' : 'Unlocked' }}

                                    </button>

                                </form>

                            </td>



                            <td class="px-4 py-3">

                                <form method="POST" action="{{ route('cdc.schemes.destroy', $scheme->id) }}"
                                    onsubmit="return confirm('Delete this scheme?')">

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

    </div>
@endsection
