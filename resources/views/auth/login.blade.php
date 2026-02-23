<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md bg-white shadow-lg rounded-lg p-6">
    
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label class="block mb-1 font-medium">Email / Username</label>
            <input 
                type="text" 
                name="login" 
                value="{{ old('login') }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200 @error('login') border-red-500 @enderror"
                required 
                autofocus
            >
            @error('login')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium">Password</label>
            <input 
                type="password" 
                name="password" 
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200 @error('password') border-red-500 @enderror"
                required
            >
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4 flex items-center">
            <input type="checkbox" name="remember" id="remember" class="mr-2">
            <label for="remember">Ingat saya</label>
        </div>

        <button 
            type="submit" 
            class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition"
        >
            Masuk
        </button>

        <p class="mt-4 text-center">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline">
                Daftar di sini
            </a>
        </p>
    </form>

</div>

</body>
</html>