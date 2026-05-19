<!DOCTYPE html>
<html>
<head>
    <title>Group Chat</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/js/app.js'])
</head>

<body class="bg-gray-100">

<div class="max-w-4xl mx-auto mt-10 bg-white rounded-xl shadow p-6">

    <h1 class="text-3xl font-bold mb-5">
        Group: {{ $group->name }}
    </h1>

    <!-- CHAT BOX -->
    <div id="chat-box"
         class="h-[400px] overflow-y-auto border rounded-lg p-4 bg-gray-50 mb-4">

        @foreach($messages as $msg)

            <div class="mb-3">

                <div class="bg-green-500 text-white p-3 rounded-lg inline-block">

                    <strong>{{ $msg->user->name }}</strong><br>

                    {{ $msg->message }}

                </div>

            </div>

        @endforeach

    </div>

    <!-- FORM -->
    <form id="chat-form">

        @csrf

        <div class="flex gap-2">

            <input
                type="text"
                id="message"
                placeholder="Ketik pesan..."
                class="flex-1 border rounded-lg px-4 py-2"
            >

            <button
                type="submit"
                class="bg-green-500 text-white px-5 rounded-lg"
            >
                Kirim
            </button>

        </div>

    </form>

</div>

<script>

const chatBox = document.getElementById('chat-box');
const form = document.getElementById('chat-form');
const messageInput = document.getElementById('message');

form.addEventListener('submit', async (e) => {

    e.preventDefault();

    let text = messageInput.value;

    if(text.trim() === '') return;

    let response = await fetch('/group/send', {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content')
        },

        body: JSON.stringify({
            message: text
        })

    });

    messageInput.value = '';

});

window.Echo.channel('group-chat')
    .listen('.group.message', (e) => {

        chatBox.innerHTML += `

            <div class="mb-3">

                <div class="bg-green-500 text-white p-3 rounded-lg inline-block">

                    <strong>${e.message.user.name}</strong><br>

                    ${e.message.message}

                </div>

            </div>

        `;

        chatBox.scrollTop = chatBox.scrollHeight;

    });

</script>

</body>
</html>