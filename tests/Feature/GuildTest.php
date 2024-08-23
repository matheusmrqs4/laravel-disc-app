<?php

namespace Tests\Feature;

use App\Models\Guild;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class GuildTest extends TestCase
{
   use WithFaker, RefreshDatabase;

   public function test_user_can_create_a_guild()
   {
       $user = User::factory()->create();
       $this->actingAs($user);

       $data = [
           'name' => $this->faker->word,
           'description' => $this->faker->sentence,
       ];

       $response = $this->post(route('guilds.store'), $data);

       $response->assertStatus(Response::HTTP_FOUND);

       $this->assertDatabaseHas('guilds', [
           'name' => $data['name'],
           'description' => $data['description'],
           'user_id' => $user->id,
       ]);
   }

   public function test_unauthenticated_user_cannot_create_a_guild()
   {
       $data = [
           'name' => $this->faker->word,
           'description' => $this->faker->sentence,
       ];

       $response = $this->post(route('guilds.store'), $data);

       $response->assertRedirect(route('login'));
   }

    public function test_user_with_admin_role_can_update_guild()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $guild = Guild::factory()->create(['user_id' => $user->id]);

        $guild->members()->attach($user->id, ['role' => 'Admin']);

        $guildData = [
            'name' => 'Guild Name Update',
            'description' => 'Guild Description Update',
        ];

        $response = $this->put(route('guilds.update', $guild->id), $guildData);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('guilds', [
            'id' => $guild->id,
            'name' => 'Guild Name Update',
            'description' => 'Guild Description Update',
        ]);
    }

    public function test_user_without_admin_role_cannot_update_guild()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $guild = Guild::factory()->create(['user_id' => $user->id]);

        $guild->members()->attach($user->id, ['role' => 'Member']);

        $guildData = [
            'name' => 'Guild Name Update',
            'description' => 'Guild Description Update',
        ];

        $response = $this->put(route('guilds.update', $guild->id), $guildData);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function test_user_with_admin_role_can_delete_guild()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $guild = Guild::factory()->create(['user_id' => $user->id]);

        $guild->members()->attach($user->id, ['role' => 'Admin']);

        $response = $this->delete(route('guilds.destroy', $guild->id));

        $response->assertRedirect(route('guilds.index'));
    }

    public function test_user_without_admin_role_cannot_delete_guild()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $guild = Guild::factory()->create(['user_id' => $user->id]);

        $guild->members()->attach($user->id, ['role' => 'Member']);

        $response = $this->delete(route('guilds.destroy', $guild->id));

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
