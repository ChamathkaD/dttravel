<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendContactNotificationEmail;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $contact = Contact::create($request->all());

        if ($contact) {
            SendContactNotificationEmail::dispatch($contact);
        }

        return response()->json([
            'success' => true,
            'message' => 'contact created!',
        ], 200);
    }
}
