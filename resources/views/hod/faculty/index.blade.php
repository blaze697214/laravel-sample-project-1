@extends('layouts.hod')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Faculty Users
    </h1>


    {{-- ADD FACULTY --}}
    <div class="bg-white p-6 rounded-xl shadow mb-8">

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Add Faculty
        </h2>

        <form method="POST" action="{{ route('hod.faculty.store') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">


            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <label class="block text-sm text-gray-600 mb-1">
                        Name
                    </label>

                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>


                <div>
                    <label class="block text-sm text-gray-600 mb-1">
                        Email
                    </label>

                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>


                <div>
                    <label class="block text-sm text-gray-600 mb-1">
                        Password
                    </label>

                    <input type="password" name="password"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

            </div>


            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                Create Faculty
            </button>

        </form>

    </div>



    {{-- FACULTY LIST --}}
    <div class="bg-white p-6 rounded-xl shadow">

        <div class="overflow-x-auto">

            <table class="w-full text-left border border-gray-200">

                <thead class="bg-gray-100">

                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Name</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Email</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Actions</th>
                    </tr>

                </thead>

                <tbody class="divide-y">

                    @foreach ($faculty as $user)
                        <tr class="hover:bg-gray-50">

                            <form method="POST" action="{{ route('hod.faculty.update', $user->id) }}">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

                                <td class="px-4 py-3">
                                    <input type="text" name="name" value="{{ $user->name }}"
                                        class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </td>

                                <td class="px-4 py-3">
                                    <input type="email" name="email" value="{{ $user->email }}"
                                        class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </td>

                                <td class="px-4 py-3 flex gap-2">

                                    <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">
                                        Update
                                    </button>

                            </form>


                            <form method="POST" action="{{ route('hod.faculty.delete', $user->id) }}">
                                @csrf
                                @method('DELETE')

                                <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

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
@endsection
