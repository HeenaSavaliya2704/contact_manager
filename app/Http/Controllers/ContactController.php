<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use SimpleXMLElement;

class ContactController extends Controller
{

    public function index()
    {
        $contacts = Contact::where('user_id', Auth::id())->get();
        return view('contacts.index', compact('contacts'));
    }

    public function show()
    {
        $contacts = Contact::where('user_id', Auth::id())->get();
        $xml = new \SimpleXMLElement('<contacts/>');

        foreach ($contacts as $contact) {
            $contactElement = $xml->addChild('contact');
            $contactElement->addChild('name', $contact->name);
            $contactElement->addChild('phone', $contact->phone);
        }

        return response($xml->asXML())
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', 'attachment; filename="contacts.xml"');
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
            'user_id' => Auth::id()
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



}
