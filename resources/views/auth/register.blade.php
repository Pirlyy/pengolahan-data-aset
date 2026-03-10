<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-2xl shadow-2xl w-96">
    <h2 class="text-2xl font-bold text-center mb-6 text-gray-700">Register</h2>

    <div id="error-msg" class="hidden mb-4 bg-red-100 text-red-600 text-sm px-4 py-2 rounded-lg"></div>
    <div id="success-msg" class="hidden mb-4 bg-green-100 text-green-600 text-sm px-4 py-2 rounded-lg"></div>

    <form id="registerForm">
        <div class="mb-4">
            <input type="text" id="name" placeholder="Nama Lengkap"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-400 outline-none">
            <p id="name-error" class="hidden text-red-500 text-xs mt-1"></p>
        </div>

        <div class="mb-4">
            <input type="email" id="email" placeholder="Email"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-400 outline-none">
            <p id="email-error" class="hidden text-red-500 text-xs mt-1"></p>
        </div>

        <div class="mb-4">
            <input type="password" id="password" placeholder="Password"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-400 outline-none">
            <p id="password-error" class="hidden text-red-500 text-xs mt-1"></p>
        </div>

        <div class="mb-4">
            <input type="password" id="password_confirmation" placeholder="Konfirmasi Password"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-400 outline-none">
            <p id="confirm-error" class="hidden text-red-500 text-xs mt-1"></p>
        </div>

        <button type="submit" id="submit-btn"
            class="w-full bg-purple-500 hover:bg-purple-600 text-white py-2 rounded-lg transition duration-300">
            Register
        </button>
    </form>

    <p class="text-sm text-center mt-4">
        Sudah punya akun?
        <a href="/login" class="text-purple-600 font-semibold">Login</a>
    </p>
</div>

<script>
    // ✅ TIDAK ada auto-redirect di halaman register
    // Sama seperti login — mencegah looping

    document.getElementById('registerForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const name     = document.getElementById('name').value.trim();
        const email    = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const confirm  = document.getElementById('password_confirmation').value;
        const btn      = document.getElementById('submit-btn');
        const errorMsg   = document.getElementById('error-msg');
        const successMsg = document.getElementById('success-msg');

        // Reset semua pesan
        errorMsg.classList.add('hidden');
        successMsg.classList.add('hidden');
        ['name-error', 'email-error', 'password-error', 'confirm-error'].forEach(id => {
            document.getElementById(id).classList.add('hidden');
        });

        // Validasi frontend
        if (!name || !email || !password || !confirm) {
            errorMsg.textContent = 'Semua field wajib diisi.';
            errorMsg.classList.remove('hidden');
            return;
        }

        if (password.length < 6) {
            const el = document.getElementById('password-error');
            el.textContent = 'Password minimal 6 karakter.';
            el.classList.remove('hidden');
            return;
        }

        if (password !== confirm) {
            const el = document.getElementById('confirm-error');
            el.textContent = 'Konfirmasi password tidak cocok.';
            el.classList.remove('hidden');
            return;
        }

        btn.disabled    = true;
        btn.textContent = 'Memproses...';

        try {
            const response = await fetch('/api/auth/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, email, password, password_confirmation: confirm })
            });

            const data = await response.json();

            if (response.status === 201 && data.success) {
                // ✅ Bersihkan token lama sebelum simpan yang baru
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                localStorage.setItem('token', data.token);
                localStorage.setItem('user', JSON.stringify(data.user));

                successMsg.textContent = 'Register berhasil! Mengalihkan ke dashboard...';
                successMsg.classList.remove('hidden');

                // ✅ Pakai replace() agar tombol back tidak kembali ke register
                setTimeout(() => window.location.replace('/dashboard'), 800);

            } else if (response.status === 422) {
                const errors = data.errors || {};
                if (errors.name) {
                    const el = document.getElementById('name-error');
                    el.textContent = errors.name[0];
                    el.classList.remove('hidden');
                }
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

            } else {
                errorMsg.textContent = data.message || 'Terjadi kesalahan server, coba lagi.';
                errorMsg.classList.remove('hidden');
            }

        } catch (err) {
            errorMsg.textContent = 'Tidak dapat terhubung ke server.';
            errorMsg.classList.remove('hidden');

        } finally {
            btn.disabled    = false;
            btn.textContent = 'Register';
        }
    });
</script>

</body>
</html>
