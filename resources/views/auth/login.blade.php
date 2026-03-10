<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-2xl shadow-2xl w-96 relative">

    <div class="flex justify-center">
        <div class="bg-blue-500 p-4 rounded-full shadow-lg -mt-16">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-10 w-10 text-white"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5.121 17.804A9 9 0 1118.879 17.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
    </div>

    <div class="flex justify-center mt-4">
        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-8 w-8 text-gray-500"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 11c1.657 0 3 1.343 3 3v1H9v-1c0-1.657 1.343-3 3-3zm6 4v-1a6 6 0 10-12 0v1H4v5h16v-5h-2z"/>
        </svg>
    </div>

    <h2 class="text-2xl font-bold text-center mt-4 mb-6 text-gray-700">Login</h2>

    <div id="error-msg" class="hidden mb-4 bg-red-100 text-red-600 text-sm px-4 py-2 rounded-lg"></div>
    <div id="success-msg" class="hidden mb-4 bg-green-100 text-green-600 text-sm px-4 py-2 rounded-lg"></div>

    <form id="loginForm">
        <div class="mb-4">
            <input type="email" id="email" placeholder="Email"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none">
            <p id="email-error" class="hidden text-red-500 text-xs mt-1"></p>
        </div>

        <div class="mb-4">
            <input type="password" id="password" placeholder="Password"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none">
            <p id="password-error" class="hidden text-red-500 text-xs mt-1"></p>
        </div>

        <button type="submit" id="submit-btn"
            class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg transition duration-300">
            Login
        </button>
    </form>

    <p class="text-sm text-center mt-4">
        Belum punya akun?
        <a href="/register" class="text-blue-600 font-semibold">Register</a>
    </p>
</div>

<script>
    // ✅ TIDAK ada auto-redirect di halaman login
    // Token lama tidak otomatis redirect — biarkan user login ulang
    // Ini mencegah looping ketika token expired

    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const email      = document.getElementById('email').value.trim();
        const password   = document.getElementById('password').value;
        const btn        = document.getElementById('submit-btn');
        const errorMsg   = document.getElementById('error-msg');
        const successMsg = document.getElementById('success-msg');

        // Reset pesan
        errorMsg.classList.add('hidden');
        successMsg.classList.add('hidden');
        document.getElementById('email-error').classList.add('hidden');
        document.getElementById('password-error').classList.add('hidden');

        if (!email || !password) {
            errorMsg.textContent = 'Email dan password wajib diisi.';
            errorMsg.classList.remove('hidden');
            return;
        }

        btn.disabled    = true;
        btn.textContent = 'Memproses...';

        try {
            const response = await fetch('http://127.0.0.1:8000/api/auth/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            if (response.status === 200 && data.success) {
                // ✅ Bersihkan token lama sebelum simpan yang baru
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                localStorage.setItem('token', data.access_token);
                localStorage.setItem('user', JSON.stringify(data.user));

                successMsg.textContent = 'Login berhasil! Mengalihkan...';
                successMsg.classList.remove('hidden');

                // ✅ Pakai replace() agar tombol back tidak kembali ke login
                setTimeout(() => window.location.replace('/dashboard'), 800);

            } else if (response.status === 422) {
                const errors = data.errors || {};
                if (errors.email) {
                    const el = document.getElementById('email-error');
                    el.textContent = errors.email[0];
                    el.classList.remove('hidden');
                }
                if (errors.password) {
                    const el = document.getElementById('password-error');
                    el.textContent = errors.password[0];
                    el.classList.remove('hidden');
                }

            } else if (response.status === 401) {
                errorMsg.textContent = data.message || 'Email atau password salah.';
                errorMsg.classList.remove('hidden');

            } else {
                errorMsg.textContent = 'Terjadi kesalahan server, coba lagi.';
                errorMsg.classList.remove('hidden');
            }

        } catch (err) {
            errorMsg.textContent = 'Tidak dapat terhubung ke server.';
            errorMsg.classList.remove('hidden');

        } finally {
            btn.disabled    = false;
            btn.textContent = 'Login';
        }
    });
</script>

</body>
</html>
