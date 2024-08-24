<?php

namespace Tests\Feature;

use App\Events\DeleteMessageEvent;
use App\Events\SendMessageEvent;
use App\Models\Channel;
use App\Models\Guild;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_send_message()
    {
        Event::fake();

        $user = User::factory()->create();

        $guild = Guild::factory()->create(['user_id' => $user->id]);
        $guild->members()->attach($user->id, ['role' => 'Member']);

        $channel = Channel::factory()->create(['guild_id' => $guild->id]);

        $message = ['content' => 'functiona buceta'];

        $response = $this->actingAs($user)->post(route('messages.store', [
                'guild' => $channel->guild,
                'channel' => $channel
            ]), $message);

        $response->assertStatus(Response::HTTP_OK);

        Event::assertDispatched(SendMessageEvent::class, function (SendMessageEvent $event) use ($message) {
            return $event->getMessage()->content === $message['content'];
        });
    }

    public function test_user_can_delete_his_messages()
    {
        Event::fake();

        $user = User::factory()->create();

        $guild = Guild::factory()->create(['user_id' => $user->id]);
        $guild->members()->attach($user->id, ['role' => 'Member']);

        $channel = Channel::factory()->create(['guild_id' => $guild->id]);

        $payload = ['content' => 'xdxd'];

        $response = $this->actingAs($user)->post(route('messages.store', [
                'guild' => $channel->guild,
                'channel' => $channel
            ]), $payload);

        $response->assertStatus(Response::HTTP_OK);

        $message = Message::where('channel_id', $channel->id)
            ->where('content', $payload['content'])
            ->first();

        $response = $this->actingAs($user)->delete(route('messages.delete', [
            'guild' => $guild->id,
            'channel' => $channel->id,
            'message' => $message->id
        ]));

        $response->assertStatus(Response::HTTP_OK);

        Event::assertDispatched(DeleteMessageEvent::class, function (DeleteMessageEvent $event) use ($message) {
            return $event->broadcastWith() === [
                    'id' => $message->id,
                ];
        });
    }

    public function test_user_with_admin_role_can_delete_another_users_messages()
    {
        Event::fake();

        $admin = User::factory()->create();

        $member = User::factory()->create();

        $guild = Guild::factory()->create(['user_id' => $admin->id]);

        $guild->members()->attach($admin->id, ['role' => 'Admin']);
        $guild->members()->attach($member->id, ['role' => 'Member']);

        $channel = Channel::factory()->create(['guild_id' => $guild->id]);

        $payload = ['content' => 'Lakers'];
        $this->actingAs($member)->post(route('messages.store', [
            'guild' => $channel->guild,
            'channel' => $channel
        ]), $payload);

        $message = Message::where('channel_id', $channel->id)
            ->where('content', $payload['content'])
            ->first();

        $response = $this->actingAs($admin)->delete(route('messages.delete', [
            'guild' => $guild->id,
            'channel' => $channel->id,
            'message' => $message->id
        ]));

        $response->assertStatus(Response::HTTP_OK);

        Event::assertDispatched(DeleteMessageEvent::class, function (DeleteMessageEvent $event) use ($message) {
            return $event->broadcastWith() === [
                    'id' => $message->id,
                ];
        });
    }

    public function test_user_without_admin_role_cannot_delete_another_users_messages()
    {
        $admin = User::factory()->create();

        $member = User::factory()->create();

        $guild = Guild::factory()->create(['user_id' => $admin->id]);

        $guild->members()->attach($admin->id, ['role' => 'Admin']);
        $guild->members()->attach($member->id, ['role' => 'Member']);

        $channel = Channel::factory()->create(['guild_id' => $guild->id]);

        $payload = ['content' => 'Lakers'];
        $this->actingAs($admin)->post(route('messages.store', [
            'guild' => $channel->guild,
            'channel' => $channel
        ]), $payload);

        $message = Message::where('channel_id', $channel->id)
            ->where('content', $payload['content'])
            ->first();

        $response = $this->actingAs($member)->delete(route('messages.delete', [
            'guild' => $guild->id,
            'channel' => $channel->id,
            'message' => $message->id
        ]));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
