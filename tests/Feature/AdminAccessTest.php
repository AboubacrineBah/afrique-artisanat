<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_a_customer_gets_a_403_on_admin_pages(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($customer)->get(route('admin.products.index'))->assertForbidden();
        $this->actingAs($customer)->get(route('admin.orders.index'))->assertForbidden();
    }

    public function test_the_role_cannot_be_set_through_registration(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Pirate',
            'email' => 'pirate@example.test',
            'password' => 'Motdepasse-Solide-123',
            'password_confirmation' => 'Motdepasse-Solide-123',
            'role' => 'admin',
        ]);

        $user = User::where('email', 'pirate@example.test')->firstOrFail();

        $this->assertFalse($user->isAdmin());
    }
}
