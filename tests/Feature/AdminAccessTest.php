<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.admin.emails' => ['israrumair07@gmail.com']]);
    }

    public function test_only_verified_allowlisted_users_can_access_admin_routes(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));

        $otherUser = User::factory()->create([
            'email' => 'someone@example.com',
            'email_verified_at' => now(),
        ]);
        $this->actingAs($otherUser)->get(route('dashboard'))->assertForbidden();

        $admin = User::factory()->create([
            'email' => 'israrumair07@gmail.com',
            'email_verified_at' => now(),
        ]);
        $this->actingAs($admin)->get(route('dashboard'))->assertOk();
    }

    public function test_unverified_allowlisted_admin_is_sent_to_verification_page(): void
    {
        $admin = User::factory()->create([
            'email' => 'israrumair07@gmail.com',
            'email_verified_at' => null,
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertRedirect(route('verification.notice'));
    }

    public function test_registration_is_allowlisted_and_sends_a_verification_email(): void
    {
        Notification::fake();

        $this->post(route('register.post'), [
            'name' => 'Admin User',
            'email' => 'ISRARUMAIR07@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('verification.notice'));

        $admin = User::where('email', 'israrumair07@gmail.com')->firstOrFail();
        $this->assertNull($admin->email_verified_at);
        Notification::assertSentTo($admin, VerifyEmail::class);

        $this->post(route('logout'))->assertRedirect(route('login'));

        $this->post(route('register.post'), [
            'name' => 'Unapproved User',
            'email' => 'someone@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('users', ['email' => 'someone@example.com']);
    }

    public function test_verification_link_activates_allowlisted_admin_access(): void
    {
        $admin = User::factory()->create(['email' => 'israrumair07@gmail.com']);
        $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(30), [
            'id' => $admin->id,
            'hash' => sha1($admin->email),
        ]);

        $this->actingAs($admin)
            ->get($url)
            ->assertRedirect(route('dashboard'));

        $this->assertNotNull($admin->fresh()->email_verified_at);
    }

    public function test_email_test_endpoint_is_not_public(): void
    {
        $this->get('/test-email?to=attacker@example.com')
            ->assertRedirect(route('login'));
    }
}
