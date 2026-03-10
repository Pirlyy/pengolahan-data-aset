<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-2xl shadow-2xl w-96">
    <h2 class="text-2xl font-bold text-center mb-6 text-gray-700">Register</h2>

    <form id="registerForm">

        <div class="mb-4">
            <input type="text" id="name" placeholder="Nama Lengkap"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-400 outline-none">
        </div>

        <div class="mb-4">
            <input type="email" id="email" placeholder="Email"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-400 outline-none">
        </div>

        <div class="mb-4">
            <input type="password" id="password" placeholder="Password"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-400 outline-none">
        </div>

        <div class="mb-4">
            <input type="password" id="password_confirmation" placeholder="Konfirmasi Password"
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

<script>

document.getElementById("registerForm").addEventListener("submit", async function(e){

    e.preventDefault();

    const response = await fetch("/api/register",{

        method:"POST",
        headers:{
            "Content-Type":"application/json"
        },

        body:JSON.stringify({
            name:document.getElementById("name").value,
            email:document.getElementById("email").value,
            password:document.getElementById("password").value,
            password_confirmation:document.getElementById("password_confirmation").value
        })

    });

    const data = await response.json();

    if(response.ok){

        alert("Register berhasil, silakan login");
        window.location.href="/login";

    }else{

        alert("Register gagal");

    }

});

</script>

</body>
</html>