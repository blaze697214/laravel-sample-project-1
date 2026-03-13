@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        HOD Users
    </h1>


    {{-- Success / Error Messages --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif



    {{-- Create HOD Form --}}
    <div class="bg-white p-6 rounded-xl shadow mb-8">

        <h2 class="text-lg font-semibold mb-4">
            Create HOD
        </h2>

        <form method="POST" action="{{ route('cdc.users.hod.store') }}">

            @csrf

            <div class="grid grid-cols-2 gap-8 gap-y-4">

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Name</label>

                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded px-3 py-2">
                </div>


                <div>
                    <label class="block text-sm text-gray-600 mb-1">Email</label>

                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-gray-300 rounded px-3 py-2">
                </div>


                <div>
                    <label class="block text-sm text-gray-600 mb-1">Department</label>

                    <select name="department_id" required class="w-full border border-gray-300 rounded px-3 py-2" >

                        <option value="">Select Department</option>

                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">
                                {{ $department->name }}
                            </option>
                        @endforeach

                    </select>

                </div>


                <div>
                    <label class="block text-sm text-gray-600 mb-1">Password</label>

                    <input type="password" name="password" value="{{ old('password') }}" required class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

            </div>


            <div class="mt-8">

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">

                    Create HOD

                </button>

            </div>

        </form>

    </div>



    {{-- Existing HOD Users Table --}}
    <div class="bg-white p-6 rounded-xl shadow">

        <h2 class="text-lg font-semibold mb-4">
            Existing HOD Users
        </h2>


        <div class="overflow-x-auto">

            <table class="w-full text-left border border-gray-200">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-4 py-2 text-sm font-semibold text-gray-600">Name</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-600">Email</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-600">Department</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-600">Actions</th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @foreach ($hodUsers as $user)
                        <tr class="hover:bg-gray-50 border-gray-200">
                            <form method="POST" action="{{ route('cdc.users.hod.update', $user->id) }}">

                                @csrf
                                @method('PUT')

                            <td class="px-4 py-2">
                                <input type="text" name="name" value="{{ $user->name }}"
                                        class="border border-gray-300 rounded px-2 py-1 w-full">
                            </td>

                            <td class="px-4 py-2">
                                <input type="text" name="email" value="{{ $user->email }}"
                                        class="border border-gray-300 rounded px-2 py-1 w-full">
                            </td>

                            <td class="px-4 py-2">
                                {{ $user->department->name ?? '-' }}
                            </td>


                            <td class="px-4 py-2 flex gap-2">
                                
                                    

                                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">

                                        Update

                                    </button>

                                </form>



                                {{-- Delete --}}
                                <form method="POST" action="{{ route('cdc.users.hod.destroy', $user->id) }}"
                                    onsubmit="return confirm('Delete this HOD?')">

                                    @csrf
                                    @method('DELETE')

                                    <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">

                                        Delete

                                    </button>

                                </form>


                            </td>

                        </tr>
                    @endforeach


                    @if ($hodUsers->isEmpty())
                        <tr>

                            <td colspan="4" class="text-center py-4 text-gray-500">
                                No HOD users found
                            </td>

                        </tr>
                    @endif

                </tbody>

            </table>

        </div>

    </div>
@endsection
