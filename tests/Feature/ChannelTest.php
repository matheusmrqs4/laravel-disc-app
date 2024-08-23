<?php

namespace Tests\Feature;

use App\Models\Channel;
use App\Models\Guild;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
