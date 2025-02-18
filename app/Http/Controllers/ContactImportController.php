<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use SimpleXMLElement;
use Illuminate\Support\Facades\Auth;

class ContactImportController extends Controller
{
    // Function to import contacts from XML
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xml',
        ]);
        $file = $request->file('file');
        $xmlContent = file_get_contents($file->getRealPath());
        $xml = simplexml_load_string($xmlContent);

        foreach ($xml->contact as $contact) {
            $name = (string) $contact->name;
            $phone = (string) $contact->phone;

            Contact::create([
                'name' => $name,
                'phone' => $phone,
                'user_id' =>  Auth::id()
            ]);
        }
        return redirect()->route('contacts.index')->with('success', 'Contacts imported successfully.');
    }
}
