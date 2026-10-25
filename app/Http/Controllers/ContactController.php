<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessageAcknowledged;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('contact');
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        $contactMessage = ContactMessage::create(
            $request->safe()->only(['name', 'email', 'subject', 'message'])
        );

        Mail::to(config('shop.contact_email'))->send(new ContactMessageReceived($contactMessage));
        Mail::to($contactMessage->email)->send(new ContactMessageAcknowledged($contactMessage));

        return redirect()->route('contact.create')
            ->with('success', 'Merci ! Votre message a bien été envoyé, nous vous répondrons rapidement.');
    }
}
