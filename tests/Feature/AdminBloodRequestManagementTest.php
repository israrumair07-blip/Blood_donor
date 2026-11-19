<?php

namespace Tests\Feature;

use App\Models\BloodRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBloodRequestManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.admin.emails' => ['admin@example.com']]);
    }

    public function test_admin_can_view_requests_in_any_status(): void
    {
        $this->actingAs($this->admin());

        BloodRequest::create($this->requestAttributes(['status' => 'open']));
        BloodRequest::create($this->requestAttributes(['status' => 'fulfilled']));

        $this->get(route('admin.urgent-requests.index'))
            ->assertOk()
            ->assertSee('Open')
            ->assertSee('Fulfilled')
            ->assertSee('Delete');
    }

    public function test_admin_can_delete_open_and_fulfilled_requests(): void
    {
        $this->actingAs($this->admin());

        foreach (['open', 'fulfilled'] as $status) {
            $bloodRequest = BloodRequest::create($this->requestAttributes(['status' => $status]));

            $this->delete(route('blood-requests.destroy', $bloodRequest))
                ->assertRedirect();

            $this->assertDatabaseMissing('blood_requests', ['id' => $bloodRequest->id]);
        }
    }

    private function requestAttributes(array $overrides = []): array
    {
        return array_merge([
            'patient_name' => 'Test Patient',
            'blood_group' => 'O+',
            'units_needed' => 1,
            'hospital' => 'City Hospital',
            'city' => 'Lahore',
            'contact_name' => 'Test Contact',
            'contact_phone' => '03001234567',
            'urgency_level' => 'Immediate',
            'status' => 'open',
            'manage_token' => 'test-management-token',
        ], $overrides);
    }

    private function admin(): User
    {
        return User::factory()->create([
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
        ]);
    }
}
