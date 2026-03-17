<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>HOD Panel</title>

    @vite('resources/css/app.css')

</head>

<body class="flex bg-slate-100 font-sans">
    @php
        $departmentType = auth()->user()->department->type ?? 'programme';
    @endphp

    <!-- Sidebar -->
    <div class="w-64 h-screen bg-slate-900 text-slate-200 flex flex-col justify-between fixed">

        <div>

            <!-- Profile -->
            <div class="p-6 border-b border-slate-700">

                <h3 class="text-lg font-semibold">
                    {{ auth()->user()->name }}
                </h3>

                <p class="text-sm text-slate-400 mt-1">
                    HOD
                </p>

                <p class="text-xs font-medium text-slate-500">
                    {{ auth()->user()->department->name ?? '' }}
                </p>

            </div>


            <!-- Navigation -->
            <nav class="mt-4 space-y-1">

                <a href="{{ route('hod.dashboard') }}" class="block px-6 py-3 hover:bg-slate-800 transition">
                    Dashboard
                </a>


                @if ($departmentType === 'programme')
                    {{-- Scheme Section --}}
                    <h4 class="px-6 py-2 text-xs uppercase text-slate-400 mt-4">
                        Scheme
                    </h4>

                    <a href="{{ route('hod.scheme') }}"
                        class="block px-6 py-3 hover:bg-slate-800 transition
           {{ request()->routeIs('hod.scheme') ? 'bg-slate-800' : '' }}">
                        View Scheme Structure
                    </a>

                    <a href="{{ route('hod.electives') }}"
                        class="block px-6 py-3 hover:bg-slate-800 transition
           {{ request()->routeIs('hod.electives') ? 'bg-slate-800' : '' }}">
                        Elective Groups
                    </a>
                    
                    <a href="{{ route('hod.courses') }}"
                        class="block px-6 py-3 hover:bg-slate-800 transition
           {{ request()->routeIs('hod.courses') ? 'bg-slate-800' : '' }}">
                        Configure Courses
                    </a>

                    <a href="{{ route('hod.classAward') }}"
                        class="block px-6 py-3 hover:bg-slate-800 transition
           {{ request()->routeIs('hod.courses') ? 'bg-slate-800' : '' }}">
                        Class Award Courses
                    </a>

                @else
                    {{-- Service Department Courses --}}
                    <h4 class="px-6 py-2 text-xs uppercase text-slate-400 mt-4">
                        Courses
                    </h4>
{{-- {{ route('hod.owned-courses') }} --}}
                    <a href=""
                        class="block px-6 py-3 hover:bg-slate-800 transition
           {{ request()->routeIs('hod.owned-courses') ? 'bg-slate-800' : '' }}">
                        Owned Courses
                    </a>
{{-- {{ route('hod.syllabus') }} --}}
                    <a href=""
                        class="block px-6 py-3 hover:bg-slate-800 transition
           {{ request()->routeIs('hod.syllabus') ? 'bg-slate-800' : '' }}">
                        Syllabus
                    </a>
                @endif


                {{-- Faculty Section --}}
                <h4 class="px-6 py-2 text-xs uppercase text-slate-400 mt-4">
                    Faculty
                </h4>
{{-- {{ route('hod.faculty') }} --}}
                <a href=""
                    class="block px-6 py-3 hover:bg-slate-800 transition
       {{ request()->routeIs('hod.faculty') ? 'bg-slate-800' : '' }}">
                    Faculty Users
                </a>
{{-- {{ route('hod.assign-courses') }} --}}
                <a href=""
                    class="block px-6 py-3 hover:bg-slate-800 transition
       {{ request()->routeIs('hod.assign-courses') ? 'bg-slate-800' : '' }}">
                    Assign Courses
                </a>


                {{-- Reports --}}
                <h4 class="px-6 py-2 text-xs uppercase text-slate-400 mt-4">
                    Reports
                </h4>
{{-- {{ route('hod.reports') }} --}}
                <a href=""
                    class="block px-6 py-3 hover:bg-slate-800 transition
       {{ request()->routeIs('hod.reports') ? 'bg-slate-800' : '' }}">
                    Department Summary
                </a>

            </nav>

        </div>


        <!-- Logout -->
        <div class="p-6 border-t border-slate-700">

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg font-medium transition">
                    Logout
                </button>

            </form>

        </div>

    </div>



    <!-- Main Content -->

    <div class="ml-64 flex-1 p-8 min-h-screen">

        <div class="bg-white shadow rounded-xl p-6 h-full overflow-auto">

            {{-- Alerts --}}
            @if (session('success'))
                <div id="msg" class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div id="msg" class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </div>

    </div>

</body>

<script>
    setTimeout(function() {

            const msg = document.getElementById('msg');

            if (msg) {
                msg.style.display = 'none';
            }

        }, 2000);
</script>

</html>
