<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGuildRequest;
use App\Http\Requests\UpdateGuildRequest;
use App\Models\Guild;
use App\Services\GuildService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GuildController extends Controller
{
    public function __construct(
        private readonly GuildService $guildService
    ) {
    }

    /**
     * @return View
     */
    public function index(): View
    {
        $guilds = $this->guildService->getAllGuilds();

        return view('guilds.get-guilds', compact('guilds'));
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('guilds.create-guild-form');
    }

    /**
     * @param CreateGuildRequest $request
     * @return RedirectResponse
     */
    public function store(CreateGuildRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $guild = $this->guildService->createGuild($data);

        return redirect()->route('guilds.show', $guild->id)
            ->with('success', 'Guild created successfully!');
    }

    /**
     * @param Guild $guild
     * @return View
     */
    public function show(Guild $guild): View
    {
        $guild = $this->guildService->getGuildById($guild);

        return view('guilds.show-guild', compact('guild'));
    }

    /**
     * @return View
     */
    public function edit(): View
    {
        return view('guilds.update-guild');
    }

    /**
     * @param UpdateGuildRequest $request
     * @param Guild $guild
     * @return RedirectResponse
     * @throws \App\Exceptions\GuildException
     */
    public function update(UpdateGuildRequest $request, Guild $guild): RedirectResponse
    {
        $data = $request->validated();

        $guildData = $this->guildService->updateGuild($guild, $data);

        return redirect()->route('guilds.show', $guild->id)
            ->with('success', 'Guild updated successfully!');
    }

    /**
     * @param Guild $guild
     * @return View
     * @throws \App\Exceptions\GuildException
     */
    public function destroy(Guild $guild): View
    {
        $this->guildService->deleteGuild($guild);

        return view('guilds.get-guilds', compact('guild'));
    }
}
