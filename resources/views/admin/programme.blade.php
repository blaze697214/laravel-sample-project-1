@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Programme Management
    </h1>


    <!-- Create Programme -->
    <div class="bg-white p-6 rounded-xl shadow mb-10">

        <h2 class="text-lg font-semibold mb-4 text-gray-700">
            Add Programme
        </h2>

        <form method="POST" action="{{ route('admin.programmes.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Programme Name
                </label>

                <input type="text" name="name" required
                    class="w-80 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium transition">
                Create Programme
            </button>

        </form>

    </div>



    <!-- Programme List -->
    <div class="bg-white p-6 rounded-xl shadow">

        <h2 class="text-lg font-semibold text-gray-700 mb-4">
            Existing Programmes
        </h2>

        <div class="overflow-x-auto">

            <table class="w-full text-left border border-gray-200">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            ID
                        </th>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Programme Name
                        </th>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @foreach ($programmes as $programme)
                        <tr class="hover:bg-gray-50 border-gray-200">

                            <td class="px-4 py-3">
                                {{ $programme->id }}
                            </td>

                            <td class="px-4 py-3">

                                <form method="POST" action="{{ route('admin.programmes.update', $programme->id) }}"
                                    class="flex items-center gap-3">

                                    @csrf
                                    @method('PUT')

                                    <input type="text" name="name" value="{{ $programme->name }}"
                                        class="border border-gray-300 rounded px-2 py-1 w-48">

                            </td>


                            <td class="px-4 py-3 flex gap-2">

                                <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                    Update
                                </button>

                                </form>


                                <form method="POST" action="{{ route('admin.programmes.destroy', $programme->id) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
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
