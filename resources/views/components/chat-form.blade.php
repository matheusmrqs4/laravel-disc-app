<form id="messageForm" class="p-3">
    @csrf
    <div class="input-group">
        <input id="messageContent" type="text" class="form-control" placeholder="Send a Message" aria-label="Message" aria-describedby="button-send">
        <button class="btn btn-primary" type="submit" id="button-send">Send</button>
    </div>
</form>
