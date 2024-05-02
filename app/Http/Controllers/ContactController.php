<?php

namespace App\Http\Controllers;

use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::all();

        return inertia('Contacts/ContactList')
            ->with(compact('contacts'));
    }

    public function show(Contact $contact)
    {
        return inertia('Contacts/ContactDetails')
            ->with(compact('contact'));
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return to_route('contacts.index');
    }
}
