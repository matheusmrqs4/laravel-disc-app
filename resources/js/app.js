import './bootstrap';
import './echo';

const newMessage = (user, message, timestamp, messageId) => {
    return `
        <div class="chatMessageDiv" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; padding: 10px 0;">
            <div style="flex: 1">
                <p>
                    <span>[${timestamp}]</span>
                    <span>${user}:</span>
                    <span>${message}</span>
                </p>
            </div>
            <button
                style="background: transparent;
                border: none;
                color: #ddd;
                cursor: pointer;"
                class="delete-message" data-message-id="${messageId}">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    `;
};

$(document).ready(function () {
    const channelId = $("#channelId").val();
    const channelMessagesEl = $("#channelMessages");
    const guildId = $("#guildId").val();
    const messageFormEl = $("#messageForm");
    const messageContentEl = $("#messageContent");
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    messageFormEl.submit(function (e) {
        e.preventDefault();

        let uri = `/guilds/${guildId}/channels/${channelId}/messages`;

        fetch(uri, {
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            method: "POST",
            body: JSON.stringify({ content: messageContentEl.val() })
        })
            .then(res => res.json())
            .then(res => {
                messageContentEl.val('');
            })
            .catch(err => {
                console.error('Erro ao enviar a mensagem', err);
            });
    });

    Echo.channel('channel.' + channelId)
        .listen('.SendMessageEvent', function (event) {
            channelMessagesEl.append(newMessage(event.user, event.message, event.sent_at, event.messageId));
        })
        .listen('.delete-message', function (event) {
            $(`.delete-message[data-message-id="${event.id}"]`).closest('.chatMessageDiv').remove();
        });

    Echo.join('channel.' + channelId)
        .joining((user) => {
            channelMessagesEl.append(`<p><span>${user.name} joined</span></p>`);
        })
        .leaving((user) => {
            channelMessagesEl.append(`<p><span>${user.name} left</span></p>`);
        })
        .error((error) => {
            console.error('Erro no Echo:', error);
        });

    channelMessagesEl.on('click', '.delete-message', function () {
        const messageId = $(this).data('message-id');

        if (messageId) {
            fetch(`/guilds/${guildId}/channels/${channelId}/messages/${messageId}`, {
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                method: "DELETE"
            })
                .then(res => res.json())
                .then(() => {
                    $(this).closest('.chatMessageDiv').remove();
                })
                .catch(err => {
                    console.error('Erro ao deletar a mensagem', err);
                });
        }
    });
});
