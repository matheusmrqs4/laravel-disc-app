<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Models\Channel;
use App\Models\Guild;
use App\Services\ChannelService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ChannelController extends Controller
{
    public function __construct(
        private readonly ChannelService $channelService,
    ) {
    }

    /**
     * Show the form for creating a new resource.
     * @param Guild $guild
     * @return View
     */
    public function create(Guild $guild): View
    {
        return view('guilds.channels.create-channel-form', [
            'guild' => $guild
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param CreateChannelRequest $request
     * @param Guild $guild
     * @return RedirectResponse
     * @throws \App\Exceptions\ChannelException
     */
    public function store(CreateChannelRequest $request, Guild $guild): RedirectResponse
    {
        $data = $request->validated();

        $channel = $this->channelService->createChannel($data, $guild);

        return redirect()->route('channels.show', [
            'guild' => $guild->id,
            'channel' => $channel->id,
        ])->with('success', 'Channel created successfully!');
    }

    /**
     * Display the specified resource.
     * @param Guild $guild
     * @param Channel $channel
     * @return View
     */
    public function show(Guild $guild, Channel $channel): View
    {
        $user = auth()->user();

        //TODO: dispatch UserJoinedChannelEvent here

        return view('guilds.channels.show-channel', [
            'channel' => $channel,
            'guild' => $guild,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param Guild $guild
     * @param Channel $channel
     * @return View
     */
    public function edit(Guild $guild, Channel $channel): View
    {
        return view('guilds.channels.update-channel', [
            'guild' => $guild,
            'channel' => $channel,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateChannelRequest $request
     * @param Guild $guild
     * @param Channel $channel
     * @return View
     * @throws \App\Exceptions\ChannelException
     */
    public function update(UpdateChannelRequest $request, Guild $guild, Channel $channel): View
    {
        $data = $request->validated();

        $channelData = $this->channelService->updateChannel($data, $channel);

        return view('guilds.channels.show-channel', [
            'channel' => $channelData,
            'guild' => $guild,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param Guild $guild
     * @param Channel $channel
     * @return RedirectResponse
     * @throws \App\Exceptions\ChannelException
     */
    public function destroy(Guild $guild, Channel $channel): RedirectResponse
    {
        $this->channelService->deleteChannel($channel);

        return redirect()->route('guilds.show', $guild->id);
    }
}
