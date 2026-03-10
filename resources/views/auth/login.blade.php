<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-2xl shadow-2xl w-96 relative">

    <!-- Avatar Circle -->
    <div class="flex justify-center">
        <div class="bg-blue-500 p-4 rounded-full shadow-lg -mt-16">
            <!-- Profile Icon -->
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-10 w-10 text-white"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5.121 17.804A9 9 0 1118.879 17.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
    </div>

    <!-- Lock Icon -->
    <div class="flex justify-center mt-4">
        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-8 w-8 text-gray-500"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 11c1.657 0 3 1.343 3 3v1H9v-1c0-1.657 1.343-3 3-3zm6 4v-1a6 6 0 10-12 0v1H4v5h16v-5h-2z" />
        </svg>
    </div>

    <h2 class="text-2xl font-bold text-center mt-4 mb-6 text-gray-700">
        Login
    </h2>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-2 mb-3 rounded text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 mb-3 rounded text-sm">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <input type="email" name="email" placeholder="Email"
                value="{{ old('email') }}"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none">
        </div>

        <div class="mb-4">
            <input type="password" name="password" placeholder="Password"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none">
        </div>

        <button type="submit"
            class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg transition duration-300">
            Login
        </button>
    </form>

    <p class="text-sm text-center mt-4">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-blue-600 font-semibold">
            Register
        </a>
    </p>
</div>

</body>
</html>
