<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submitContactForm(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Enregistrement du message dans la base de données
        $contact = Contact::create($validated);

        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return response()->json(['success' => true, 'message' => 'Message supprimé avec succès.']);
    }


    public function index()
    {
        $messages = Contact::latest()->paginate(10);
        return view('adminpanel.adminmessage', compact('messages'));
    }
}
