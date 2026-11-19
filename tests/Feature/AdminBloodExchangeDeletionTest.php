<?php

namespace Tests\Feature;

use App\Models\BloodExchange;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBloodExchangeDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_deleting_an_exchange_returns_a_success_toast_type(): void
    {
        config(['services.admin.emails' => ['admin@example.com']]);
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
        ]);
        $exchange = BloodExchange::create([
            'offered_blood_group' => 'O+',
            'needed_blood_group' => 'A+',
            'units' => 1,
            'patient_name' => 'Test Patient',
            'hospital' => 'City Hospital',
            'city' => 'Lahore',
            'contact_name' => 'Test Contact',
            'contact_phone' => '03001234567',
            'manage_token' => 'test-exchange-token',
        ]);

        $this->actingAs($admin)
            ->deleteJson(route('admin.blood-exchanges.destroy', $exchange))
            ->assertOk()
            ->assertJson([
                'success' => true,
                'type' => 'success',
            ]);

        $this->assertDatabaseMissing('blood_exchanges', ['id' => $exchange->id]);
    }
}
