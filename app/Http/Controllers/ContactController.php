<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use SimpleXMLElement;

class ContactController extends Controller
{
    // public function index()
    // {
    //     $contacts = Contact::all();
    //     return view('contacts.index', compact('contacts'));
    // }

    public function index()
    {
        $contacts = Contact::where('user_id', auth()->user()->id)->get();
        return view('contacts.index', compact('contacts'));
    }


    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:contacts',
        ]);

        Contact::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'user_id' => auth()->user()->id,
        ]);

        return redirect()->route('contacts.index')->with('success', 'Contact added successfully.');
    }

    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:contacts,phone,' . $contact->id,
        ]);

        $contact->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return redirect()->route('contacts.index')->with('success', 'Contact updated successfully.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('contacts.index')->with('success', 'Contact deleted successfully.');
    }




    public function import(Request $request)
    {
        // Validate the file input
        $request->validate([
            'file' => 'required|file|mimes:xml',
        ]);

        // Load the XML file
        $xml = simplexml_load_file($request->file('file')->getRealPath());

        // Iterate over each contact in the XML
        foreach ($xml->contact as $contactData) {
            // Insert each contact into the database
            Contact::create([
                'name' => (string) $contactData->name,
                'phone' => (string) $contactData->phone,
            ]);
        }

        return redirect()->route('contacts.index')->with('success', 'Contacts imported successfully.');
    }
}
