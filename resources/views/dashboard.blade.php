<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Chat Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<div class="flex">

    <!-- SIDEBAR -->
    <div class="w-64 bg-white shadow-xl min-h-screen p-6">

        <h1 class="text-3xl font-bold text-indigo-600 mb-10">
            Chat-App
        </h1>

        <div class="space-y-4">

            <a href="/chat"
               class="block bg-indigo-500 text-white px-4 py-3 rounded-xl hover:bg-indigo-600 transition">
                💬 Realtime Chat
            </a>

            

<a href="/group"
   class="block bg-gray-100 px-4 py-3 rounded-xl hover:bg-gray-200 transition">
    👥 Group-Chat
    
</a>
</a>

            <form method="POST" action="/logout">
                @csrf

                <button
                    class="w-full bg-red-500 text-white px-4 py-3 rounded-xl hover:bg-red-600 transition">
                    Logout
                </button>
            </form>

        </div>

    </div>

    <!-- CONTENT -->
    <div class="flex-1 p-10">

        <!-- HEADER -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-500
                    rounded-3xl p-10 text-white shadow-xl">

            <h1 class="text-5xl font-bold mb-4">
                Halo, {{ auth()->user()->name }} 👋
            </h1>

            <p class="text-lg opacity-90">
                Selamat datang di aplikasi realtime chat
            </p>

        </div>

        <!-- CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">

            <!-- CARD 1 -->
            <div class="bg-white p-8 rounded-3xl shadow-lg hover:scale-105 transition">

                <div class="text-5xl mb-4">
                    💬
                </div>

                <h2 class="text-2xl font-bold mb-2">
                    Mulai Chat
                </h2>

                <p class="text-gray-500 mb-6">
                    Masuk ke halaman chat
                </p>

                <a href="/chat"
                   class="bg-indigo-500 text-white px-5 py-3 rounded-xl hover:bg-indigo-600 transition">
                    Buka Chat
                </a>

            </div>

        
          