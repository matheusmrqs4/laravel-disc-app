<?php

namespace Tests\Feature;

use App\Events\UserJoinedChannelEvent;
use App\Models\Channel;
use App\Models\Guild;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_user_with_admin_role_can_create_a_channel()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $guild = Guild::factory()->create(['user_id' => $user->id]);

        $guild->members()->attach($user->id, ['role' => 'Admin']);

        $channelData = [
            'guild_id' => $guild->id,
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
        ];

        $response = $this->post(route('channels.store', ['guild' => $guild->id]), $channelData);

        $response->assertRedirect(route('channels.show', [
            'guild' => $guild->id,
            'channel' => Channel::where('guild_id', $guild->id)->first()->id,
        ]));
    }

    public function test_user_without_admin_role_cannot_create_a_channel()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $guild = Guild::factory()->create(['user_id' => $user->id]);

        $guild->members()->attach($user->id, ['role' => 'Member']);

        $channelData = [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
        ];

        $response = $this->post(route('channels.store', ['guild' => $guild->id]), $channelData);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_with_admin_role_can_update_a_channel()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $guild = Guild::factory()->create(['user_id' => $user->id]);

        $guild->members()->attach($user->id, ['role' => 'Admin']);

        $channel = Channel::factory()->create(['guild_id' => $guild->id]);

        $updateChannelData = [
            'name' => 'Channel Update',
            'description' => 'Channel Description Update'
        ];

        $response = $this->put(route('channels.update', [
            'guild' => $guild->id,
            'channel' => $channel->id,
        ]), $updateChannelData);

        $response->assertRedirect(route('channels.show', [
            'guild' => $guild->id,
            'channel' => $channel->id,
        ]));

        $this->assertDatabaseHas('channels', [
            'id' => $channel->id,
            'name' => $updateChannelData['name'],
            'description' => $updateChannelData['description'],
        ]);
    }

    public function test_user_without_admin_role_cannot_update_a_channel()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $guild = Guild::factory()->create(['user_id' => $user->id]);

        $guild->members()->attach($user->id, ['role' => 'Member']);

        $channel = Channel::factory()->create(['guild_id' => $guild->id]);

        $updateChannelData = [
            'name' => 'Channel Update',
            'description' => 'Channel Description Update'
        ];

        $response = $this->put(route('channels.update', [
            'guild' => $guild->id,
            'channel' => $channel->id,
        ]), $updateChannelData);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_with_admin_role_can_delete_a_channel()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $guild = Guild::factory()->create(['user_id' => $user->id]);

        $guild->members()->attach($user->id, ['role' => 'Admin']);

        $channel = Channel::factory()->create(['guild_id' => $guild->id]);

        $response = $this->delete(route('channels.delete', ['guild' => $guild->id, 'channel' => $channel->id]));

        $response->assertRedirect(route('guilds.show', ['guild' => $guild->id]));
    }

    public function test_user_without_admin_role_cannot_delete_a_channel()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $guild = Guild::factory()->create(['user_id' => $user->id]);

        $guild->members()->attach($user->id, ['role' => 'Member']);

        $channel = Channel::factory()->create(['guild_id' => $guild->id]);

        $response = $this->delete(route('channels.delete', ['guild' => $guild->id, 'channel' => $channel->id]));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_event_dispatched_when_user_joins_channel()
    {
        Event::fake();

        $user = User::factory()->create();
        $this->actingAs($user);

        $guild = Guild::factory()->create(['user_id' => $user->id]);
        $guild->members()->attach($user->id, ['role' => 'Member']);

        $channel = Channel::factory()->create(['guild_id' => $guild->id]);

        $response = $this->get(route('channels.show', [
            'guild' => $guild->id,
            'channel' => $channel->id,
        ]));

        Event::assertDispatched(UserJoinedChannelEvent::class, function ($event) use ($channel, $user) {
            return $event->broadcastWith() === [
                    'id' => $user->id,
                    'name' => $user->name,
                ];
        });

        $response->assertStatus(Response::HTTP_OK);
    }

}
