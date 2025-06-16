<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mini ChatBot - Laravel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: #f4f4f4;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }

        .chat-container {
            max-width: 700px;
            margin: 50px auto;
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .messages {
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 20px;
            padding-right: 10px;
        }

        .message {
            padding: 10px 15px;
            border-radius: 20px;
            margin: 10px 0;
            width: fit-content;
            max-width: 80%;
            line-height: 1.5;
        }

        .user {
            background-color: #d1e7dd;
            align-self: flex-end;
            margin-left: auto;
        }

        .bot {
            background-color: #e2e3e5;
            align-self: flex-start;
            margin-right: auto;
        }

        .input-group {
            display: flex;
        }

        input[type="text"] {
            flex: 1;
            padding: 12px;
            font-size: 16px;
            border-radius: 30px;
            border: 1px solid #ccc;
            margin-right: 10px;
        }

        button {
            padding: 12px 25px;
            font-size: 16px;
            background-color: #4CAF50;
            border: none;
            color: white;
            border-radius: 30px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .loading {
            font-style: italic;
            color: gray;
        }
    </style>
</head>
<body>

<div class="chat-container">
    <h2>Mini ChatBot</h2>
    <div class="messages" id="messages"></div>

    <form id="chat-form" class="input-group">
        <input type="text" id="message" placeholder="Type a message..." required autocomplete="off">
        <button type="submit">Send</button>
    </form>
</div>

<script>
    const form = document.getElementById('chat-form');
    const messageInput = document.getElementById('message');
    const messagesDiv = document.getElementById('messages');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const userMessage = messageInput.value.trim();
        if (!userMessage) return;

        addMessage(userMessage, 'user');
        messageInput.value = '';

        addMessage('Typing...', 'bot', true);

        try {
            const response = await fetch('{{ route("chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: userMessage })
            });

            const data = await response.json();
            removeLastBotMessage(); // Remove "Typing..." placeholder
            addMessage(data.reply, 'bot');
        } catch (err) {
            removeLastBotMessage();
            addMessage('AI: Server error. Please try again.', 'bot');
        }
    });

    function addMessage(text, type, isLoading = false) {
        const msg = document.createElement('div');
        msg.classList.add('message', type);
        if (isLoading) msg.classList.add('loading');
        msg.textContent = text;
        messagesDiv.appendChild(msg);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    function removeLastBotMessage() {
        const last = messagesDiv.querySelector('.bot.loading');
        if (last) last.remove();
    }
</script>

</body>
</html>
