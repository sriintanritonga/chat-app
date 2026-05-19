<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Realtime Chat</title>

    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/js/app.js'])
</head>

<body class="bg-gray-100">

<div class="max-w-6xl mx-auto mt-10 bg-white rounded-2xl shadow-lg overflow-hidden flex h-[700px]">

    <!-- SIDEBAR -->
    <div class="w-1/3 border-r bg-white">

        <!-- HEADER -->
        <div class="bg-green-500 text-white p-5">

            <h1 class="text-2xl font-bold">
                Realtime Chat
            </h1>

            @php
                $me = \App\Models\User::find(auth()->id());
            @endphp

            @if($me && $me->is_online)

                <small class="font-bold">
                    ● Online
                </small>

            @else

                <small class="text-gray-200">
                    ● Offline
                </small>

            @endif

        </div>
        
<!-- USER LIST -->
<div class="overflow-y-auto h-full">

    @foreach(\App\Models\User::where('id', '!=', auth()->id())->get() as $u)

        <a href="/chat?user={{ $u->id }}">

            <div class="p-4 border-b hover:bg-gray-100 transition cursor-pointer">

                <div class="font-bold text-gray-800">
                    {{ $u->name }}
                </div>

                @if($u->is_online)

                    <small class="text-green-500 font-semibold">
                        ● Online
                    </small>

                @else

                    <small class="text-gray-400">
                        ● Offline
                    </small>

                @endif

            </div>

        </a>

    @endforeach

</div>

    </div>

    <!-- CHAT AREA -->
<div class="w-2/3 flex flex-col">

    <!-- CHAT HEADER -->
    <div class="p-4 border-b bg-white">

        <div class="font-bold text-lg">

            @if($selectedUser)
                Chat dengan {{ $selectedUser->name }}
            @else
                Pilih User
            @endif

        </div>

    </div>

        <!-- CHAT BOX -->
        <div id="chat-box"
             class="flex-1 overflow-y-auto p-4 bg-gray-50">

            @foreach($messages as $msg)

                <div class="flex {{ $msg->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }} mb-3">

                    <div class="px-4 py-2 rounded-2xl max-w-xs shadow
                        {{ $msg->sender_id == auth()->id()
                            ? 'bg-green-500 text-white'
                            : 'bg-white text-black'
                        }}">

                        <div class="font-bold text-sm mb-1">
                            {{ $msg->user->name }}
                        </div>

                        <div>
                            {{ $msg->message }}
                        </div>

                    </div>

                </div>

            @endforeach

        </div>

        <!-- FORM -->   
        <form id="chat-form"
              class="flex gap-2 p-4 border-t bg-white">

            @csrf

            <input
                type="text"
                id="message"
                placeholder="Ketik pesan..."
                class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
            >

            <button
                type="submit"
                class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg"
            >
                Kirim
            </button>

        </form>

    </div>

</div>

<script>

document.addEventListener('DOMContentLoaded', () => {

    const userId = {{ auth()->id() }};

    const chatBox = document.getElementById('chat-box');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message');

    

    chatForm.addEventListener('submit', async (e) => {

        e.preventDefault();

        if(messageInput.value.trim() === '') return;

        let text = messageInput.value;

        let response = await fetch('/chat', {

            method: 'POST',

            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content')
            },

            body: JSON.stringify({
                message: text,
                receiver_id: {{ $selectedUser?->id ?? 'null' }}
            })

        });

        let data = await response.json();

        // tampil pesan sendiri
        chatBox.innerHTML += `
            <div class="flex justify-end mb-3">

                <div class="bg-green-500 text-white px-4 py-2 rounded-2xl max-w-xs shadow">

                    <div class="font-bold text-sm mb-1">
                        ${data.message.user.name}
                    </div>

                    <div>
                        ${data.message.message}
                    </div>

                </div>

            </div>
        `;

        messageInput.value = '';

        chatBox.scrollTop = chatBox.scrollHeight;

    });



    window.Echo.channel('online')
        .listen('.message.sent', (e) => {

            if (e.message.sender_id == userId) return;

            chatBox.innerHTML += `
                <div class="flex justify-start mb-3">

                    <div class="bg-white text-black px-4 py-2 rounded-2xl max-w-xs shadow">

                        <div class="font-bold text-sm mb-1">
                            ${e.message.user.name}
                        </div>

                        <div>
                            ${e.message.message}
                        </div>

                    </div>

                </div>
            `;

            chatBox.scrollTop = chatBox.scrollHeight;

        });



    window.Echo.join('online')

        .here((users) => {
            console.log('Online users:', users);
        })

        .joining((user) => {
            console.log(user.name + ' joined');
        })

        .leaving((user) => {
            console.log(user.name + ' left');
        });

});


</script>

</body>
</html>
