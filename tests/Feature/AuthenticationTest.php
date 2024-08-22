<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use WithFaker, RefreshDatabase;

   public function test_user_can_register()
   {
       $userData = [
           'name' => $this->faker->name(),
           'email' => $this->faker->unique()->safeEmail(),
           'password' => 'password123',
       ];

       $response = $this->post(route('register'), $userData);

       $response->assertStatus(302);
       $response->assertRedirect('/guilds');
   }

   public function test_user_can_login()
   {
       $user = User::factory()->create([
           'password' => Hash::make('password123')
       ]);

       $credentials = [
           'email' => $user->email,
           'password' => 'password123'
       ];

       $response = $this->post(route('login'), $credentials);

       $response->assertStatus(302);
       $response->assertRedirect('/guilds');

       $this->assertAuthenticatedAs($user);
   }

   public function test_user_can_logout()
   {
       $user = User::factory()->create([
           'password' => Hash::make('password123')
       ]);

       $this->actingAs($user);

       $response = $this->post(route('logout'));

       $response->assertStatus(302);
       $response->assertRedirect('/login');

       $this->assertGuest();
   }
}
