<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Panel</title>

@vite('resources/css/app.css')

</head>

<body class="bg-slate-100 font-sans flex">

<!-- Sidebar -->
<div class="w-64 h-screen bg-slate-900 text-slate-200 flex flex-col justify-between fixed">

    <div>

        <!-- Profile -->
        <div>
            <div class="p-6 border-b border-slate-700">
                <h3 class="text-lg font-semibold">
                    {{ auth()->user()->name }}
                </h3>

                <p class="text-sm text-slate-400">
                    {{ auth()->user()->roles->first()->name }}  
                </p>
            </div>
        </div>


        <!-- Navigation -->
        <nav class="mt-4 space-y-1">

            <a href="/admin/dashboard"
            class="block px-6 py-3 hover:bg-slate-800 transition">
                Dashboard
            </a>

            <a href="/admin/users"
            class="block px-6 py-3 hover:bg-slate-800 transition">
                Users
            </a>

            <a href="/admin/programmes"
            class="block px-6 py-3 hover:bg-slate-800 transition">
                Programmes
            </a>

            <a href="/admin/courses"
            class="block px-6 py-3 hover:bg-slate-800 transition">
                Courses
            </a>

            <a href="/admin/electives"
            class="block px-6 py-3 hover:bg-slate-800 transition">
                Elective Groups
            </a>

            <a href="/admin/awards"
            class="block px-6 py-3 hover:bg-slate-800 transition">
                Award Configuration
            </a>

            <a href="/admin/semester"
            class="block px-6 py-3 hover:bg-slate-800 transition">
                Semester Placement
            </a>

        </nav>

    </div>


    <!-- Logout -->
    <div class="p-6 border-t border-slate-700">

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
            class="w-full bg-red-500 hover:bg-red-600 transition text-white py-2 rounded-lg font-medium">
                Logout
            </button>

        </form>

    </div>

</div>



<!-- Main Content -->
<div class="ml-64 flex-1 p-8 min-h-screen">

    <div class="bg-white shadow rounded-xl h-full p-6">

        @yield('content')

    </div>

</div>

</body>
</html>
