<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TripDocumentController extends Controller
{
    //
    public function saveDocumentToSession(Request $request)
    {
        $request->validate([
            'trp_name' => 'required|string|max:255',
            'trp_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Handle file upload if provided
        $filePath = null;
        if ($request->hasFile('trp_document')) {
            $filePath = $request->file('trp_document')->store('documents/temp', 'public');
        }

        // Get the current session data
        $documents = Session::get('trip_documents', []);

        // Add the new document entry to the session
        $documents[] = [
            'trp_name' => $request->input('trp_name'),
            'trp_document' => $filePath,
        ];

        // Update the session
        Session::put('trip_documents', $documents);

        return response()->json([
            'success' => true,
            'message' => 'Document added to session successfully!',
            'documents' => $documents,
        ]);
    }

    public function getSessionDocuments()
    {
        return response()->json([
            'success' => true,
            'documents' => Session::get('trip_documents', []),
        ]);
    }
}
