<?php

namespace Tests\Feature;

use App\Mail\ContactMessageAcknowledged;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Visiteur de test',
            'email' => 'visiteur@example.test',
            'subject' => 'Question sur un produit',
            'message' => 'Bonjour, je souhaite obtenir plus d’informations.',
        ], $overrides);
    }

    public function test_valid_contact_submission_saves_message_and_emails_the_shop_and_sender(): void
    {
        Mail::fake();
        config(['shop.contact_email' => 'boutique@example.test']);

        $payload = $this->payload();

        $response = $this->post(route('contact.store'), $payload);

        $response->assertRedirect(route('contact.create'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'subject' => $payload['subject'],
            'message' => $payload['message'],
        ]);

        Mail::assertSent(ContactMessageReceived::class, function (ContactMessageReceived $mail) use ($payload): bool {
            $mail->assertSeeInHtml($payload['name']);
            $mail->assertSeeInHtml($payload['message']);

            return $mail->hasTo('boutique@example.test')
                && $mail->hasReplyTo($payload['email']);
        });
        Mail::assertSent(ContactMessageAcknowledged::class, function (ContactMessageAcknowledged $mail) use ($payload): bool {
            $mail->assertSeeInHtml($payload['name']);
            $mail->assertSeeInHtml($payload['subject']);

            return $mail->hasTo($payload['email']);
        });
    }

    public function test_invalid_contact_submission_does_not_save_or_send_emails(): void
    {
        Mail::fake();

        $this->post(route('contact.store'), ['email' => 'adresse-invalide'])
            ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);

        $this->assertSame(0, ContactMessage::count());
        Mail::assertNothingSent();
    }
}
