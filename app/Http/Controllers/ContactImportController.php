<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use SimpleXMLElement;

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

        // Loop through the XML data and create contacts
        foreach ($xml->contact as $contact) {
            // Extract name and phone values
            $name = (string) $contact->name;
            $phone = (string) $contact->phone;

            // Save to the database
            Contact::create([
                'name' => $name,
                'phone' => $phone,
                'user_id' => auth()->user()->id,
            ]);
        }

        // Redirect with success message
        return redirect()->route('contacts.index')->with('success', 'Contacts imported successfully.');
    }
}
