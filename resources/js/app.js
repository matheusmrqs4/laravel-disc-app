import './bootstrap';
import './echo';

$(document).ready(function () {
    const channelId = $("#channelId").val();
    const channelMessagesEl = $("#channelMessages");

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
});
