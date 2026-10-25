<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(config('shop.admin_per_page'));

        return view('admin.messages.index', compact('messages'));
    }

    public function show(ContactMessage $message)
    {
        $message->markAsRead();

        return view('admin.messages.show', compact('message'));
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Message supprimé.');
    }
}
