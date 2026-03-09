<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CDC Panel</title>

    @vite('resources/css/app.css')

</head>

<body class="flex bg-slate-100 font-sans">

    <!-- Sidebar -->
    <div class="w-64 h-screen bg-slate-900 text-slate-200 flex flex-col justify-between fixed">

        <div>

            <!-- Profile -->
            <div class="p-6 border-b border-slate-700">

                <h3 class="text-lg font-semibold">
                    {{ auth()->user()->name }}
                </h3>

                <p class="text-sm text-slate-400 mt-1">
                    {{ auth()->user()->roles->first()->name }}
                </p>

            </div>


            <!-- Navigation -->
            <nav class="mt-4 space-y-1">

                <a href="/cdc/dashboard" class="block px-6 py-3 hover:bg-slate-800 transition">
                    Dashboard
                </a>

                <h4 class="px-6 py-2 text-xs uppercase text-slate-400 mt-4">
                    Schemes
                </h4>

                <a href="/cdc/schemes/create" class="block px-6 py-3 hover:bg-slate-800 transition">
                    Create Scheme
                </a>

                {{-- <a href="/cdc/schemes" class="block px-6 py-3 hover:bg-slate-800 transition">
                    Manage Schemes
                </a>

                <a href="/cdc/schemes/verify" class="block px-6 py-3 hover:bg-slate-800 transition">
                    Verify Scheme
                </a>


                <h4 class="px-6 py-2 text-xs uppercase text-slate-400 mt-4">
                    Users
                </h4>

                <a href="/cdc/users/hod" class="block px-6 py-3 hover:bg-slate-800 transition">
                    HOD Users
                </a>

                <a href="/cdc/users/faculty" class="block px-6 py-3 hover:bg-slate-800 transition">
                    Faculty Users
                </a> --}}

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

        <div class="bg-white shadow rounded-xl p-6 h-full">

            @yield('content')

        </div>

    </div>

</body>

</html>
