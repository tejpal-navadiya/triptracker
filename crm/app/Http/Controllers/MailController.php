<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webklex\PHPIMAP\ClientManager;
use Illuminate\Support\Facades\Auth;
use App\Models\MailSettings;
use App\Models\Trip;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MailController extends Controller
{
    //
    public function fetchEmails($tripId, $userId, $uniqId)
    {
        set_time_limit(300); // Increase execution time limit

        $clientManager = new ClientManager();

        try {
            $tableName = $uniqId . '_tc_mail_smtp_settings'; // Correct table name construction
        
            // Fetch the mail settings from the dynamically generated table
            $mailSettings = DB::table($tableName)
                ->where('id', $userId)
                ->first(); 

            if (!$mailSettings) {
                return response()->json(['error' => 'Mail settings not found.'], 404);
            }

            $client = $clientManager->make([
                'host'          => $mailSettings->mail_incoming_host,
                'port'          => $mailSettings->mail_incoming_port,
                'encryption'    => 'ssl',
                'validate_cert' => true,
                'username'      => $mailSettings->mail_username,
                'password'      => $mailSettings->mail_password,
                'protocol'      => 'imap'
            ]);

            $client->connect();
            if (!$client->isConnected()) {
                // return response()->json(['error' => 'Failed to connect to the mail server.'], 500);
            }

            $tableName1 = $uniqId . '_tc_trip'; 
            $trip = DB::table($tableName1)
                ->where('tr_id', $tripId)
                ->first(); 

            if (!$trip) {
                // return response()->json(['error' => 'Trip not found.'], 404);
            }

            $folder = $client->getFolder('INBOX');
            
            // Fetch the last fetched email ID from the database
            $tableNameinbox = $uniqId . '_tc_mail_inbox'; 
            $lastFetched = DB::table($tableNameinbox)
                ->where('uniq_id', $uniqId)
                ->first();

                $messages = $folder->messages()->from($trip->tr_email)->get();

                // Check if $messages is empty
                if ($messages->isEmpty()) {
                    // return response()->json(['error' => 'No emails found in the inbox.'], 404);
                }
                
                // Filter messages based on last fetched email ID
                if ($lastFetched && $lastFetched->last_email_id) {
                    $messages = $messages->filter(function ($message) use ($lastFetched) {
                        // Ensure $message is not null before accessing getUid
                        if ($message && $message->getUid()) {
                            return $message->getUid() > $lastFetched->last_email_id;
                        }
                        // return '0';
                    });
                }
                
                // If no valid messages remain after filtering
                if ($messages->isEmpty()) {
                    // return response()->json(['error' => 'No new emails found from the specified sender.'], 404);
                }
                
                $emails = [];
                foreach ($messages as $message) {
                    // Check if the message is null to avoid errors
                    
                    $subject = $message->getSubject() ?? 'No Subject';
                    $subject = str_replace('[', '', $subject);
                    $subject = str_replace(']', '', $subject);
                
                    $from = $message->getFrom()[0]->mail ?? 'Unknown Sender';
                    $dateValue1 = $message->getDate() ?? 'no date';
                    $dateValue1 = trim($dateValue1);
                    $date = date('m/d/Y', strtotime($dateValue1));
                
                    $emails[] = [
                        'subject' => $subject,
                        'from'    => $from,
                        'date'    => $date,
                        'body'    => $message->getTextBody() ?? 'No Body Content',
                    ];
                
                    DB::table($tableNameinbox)->insert([
                        'inbox_subject' => $subject,
                        'id' => $userId,
                        'inbox_from'    => $from,
                        'inbox_date'    => $date,
                        'inbox_body'    => $message->getTextBody() ?? 'No Body Content',
                        'uniq_id'       => $uniqId,
                        'tr_id'         => $tripId,
                        'last_email_id' => $message->getUid(),
                        'created_at'    => now(),
                        'inbox_status'  => '1',
                    ]);
                }
                
                // Save the last email UID safely
                $lastFetchedEmailId = $messages->last() ? $messages->last()->getUid() : null;
                if ($lastFetchedEmailId) {
                    DB::table($tableNameinbox)
                        ->updateOrInsert(
                            ['uniq_id' => $uniqId],
                            ['last_email_id' => $lastFetchedEmailId]
                        );
                }
            //    dd($emails);

            $email_fetch = DB::table($tableNameinbox)
            ->where('tr_id', $tripId)
            ->orderBy('created_at', 'desc') // Sort emails by the latest
            ->get();
            
                
            return response()->json([
                'draw' => intval(request()->get('draw')),
                'recordsTotal' => $email_fetch->count(), // Use the count of the collection
                'recordsFiltered' => $email_fetch->count(),
                'data' => $email_fetch->map(function ($email) {
                    return [
                        'from'    => $email->inbox_from,    // Correctly access properties of the object
                        'subject' => $email->inbox_subject,
                        'date'    => $email->inbox_date,   // Use the correct property name
                    ];
                })->toArray(), // Convert the collection to an array
            ]);

        } catch (\Exception $e) {
            \Log::error('Email fetch error: ' . $e->getMessage());
            return response()->json(['error' => "Error: " . $e->getMessage()], 500);
        }
    }

    
        

}
