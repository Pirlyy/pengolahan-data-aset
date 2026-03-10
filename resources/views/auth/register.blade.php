<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-2xl shadow-2xl w-96">
    <h2 class="text-2xl font-bold text-center mb-6 text-gray-700">Register</h2>

    <form method="POST" action="/register">
        @csrf

        <div class="mb-4">
            <input type="text" name="name" placeholder="Nama Lengkap"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-400 outline-none">
        </div>

        <div class="mb-4">
            <input type="email" name="email" placeholder="Email"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-400 outline-none">
        </div>

        <div class="mb-4">
            <input type="password" name="password" placeholder="Password"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-400 outline-none">
        </div>

        <div class="mb-4">
            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-400 outline-none">
        </div>

        <button type="submit"
            class="w-full bg-purple-500 hover:bg-purple-600 text-white py-2 rounded-lg transition duration-300">
            Register
        </button>
    </form>

    <p class="text-sm text-center mt-4">
        Sudah punya akun?
        <a href="/login" class="text-purple-600 font-semibold">Login</a>
    </p>
</div>

</body>
</html>
