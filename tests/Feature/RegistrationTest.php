<?php

namespace Tests\Feature;

use App\Mail\NewVisitorRegistered;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_registration_emails_the_admin(): void
    {
        Mail::fake();
        config(['shop.admin_email' => 'admin@example.test']);

        $response = $this->post(route('register.store'), [
            'name' => 'Visiteur Test',
            'email' => 'visiteur@example.com',
            'phone' => '1234567890',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'visiteur@example.com')->firstOrFail();

        $response->assertRedirect(route('home'))->assertSessionHas('success');
        $this->assertAuthenticatedAs($user);

        Mail::assertSent(NewVisitorRegistered::class, function (NewVisitorRegistered $mail) use ($user): bool {
            $mail->assertSeeInHtml($user->name);
            $mail->assertSeeInHtml($user->email);
            $mail->assertSeeInHtml($user->phone);
            $mail->assertSeeInHtml($user->created_at->format('d/m/Y à H:i'));

            return $mail->hasTo(config('shop.admin_email')) && $mail->user->is($user);
        });
    }
}
