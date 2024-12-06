<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webklex\PHPIMAP\ClientManager;
use Illuminate\Support\Facades\Auth;
use App\Models\MailSettings;
use App\Models\Trip;


class MailController extends Controller
{
    //
    public function fetchEmails($tripId)
    {
      //  dd($tripId);
        set_time_limit(300); // Increase execution time limit

        $clientManager = new ClientManager();

        try {
            $user = Auth::guard('masteradmins')->user();
            $mailSettings = MailSettings::where('id', $user->users_id)->first();

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

            $trip = Trip::where('tr_id',$tripId)->firstOrFail();
            if (!$trip) {
                // return response()->json(['error' => 'Trip not found.'], 404);
            }

            $folder = $client->getFolder('INBOX');

            // Fetch emails from specific sender
            $messages = $folder
                ->messages()
                ->from($trip->tr_email)
                ->get();

            if ($messages->count() === 0) {
                // return response()->json(['error' => 'No emails found from the specified sender.'], 404);
            }

            // Limit to the first 5 messages
            $limitedMessages = $messages->slice(0, 50);
            // dd($limitedMessages);
            $emails = [];
            foreach ($limitedMessages as $message) {

                $class_methods = get_class_methods($message);


                $subject = $message->getSubject() ?? 'No Subject';
                $subject = str_replace('[','',$subject);
                $subject = str_replace(']','',$subject);
                // Access the sender's email
                $from = $message->getFrom()[0]->mail ?? 'Unknown Sender';
                $dateValue1 = $message->getDate() ?? 'no date';
                $dateValue1 = trim($dateValue1);
                $date = date('m/d/Y',strtotime($dateValue1));
                // Access the date and convert it to a string using Carbon
                // $dateValue = $message->getDate()->values[0] ?? null; // Access the values array
                // $date = $dateValue ? \Carbon\Carbon::createFromTimestamp($dateValue)->toDateTimeString() : 'No Date';
   
                // \Log::error('mrthods' .  $class_methods );
            
            //    \Log::error('Email date Name: ' . $dateValue1);
            //    \Log::error('Email subject Name: ' . $subject.'-'.$date );
            
                $emails[] = [
                    'subject' =>  $subject ?? '',
                    'from'    => $from,
                    'date'    => $date ?? '',
                    'body'    => $message->getTextBody() ?? 'No Body Content',
                    // 'class_methods' => $class_methods ?? ''
                ];
            }

            
            
            // dd( $emails);

            // \Log::error('Email subject Name: ' . $getClassMethod = get_class_methods('GeeksforGeeks'));
            return response()->json([
                'draw' => intval(request()->get('draw')),
                'recordsTotal' => count($emails),  // Use count() on the array
                'recordsFiltered' => count($emails),  // Use count() on the array
                'data' => $emails
            ]);

                } catch (\Exception $e) {
            \Log::error('Email fetch error: ' . $e->getMessage());
            return response()->json(['error' => "Error: " . $e->getMessage()], 500);
        }
    }

    

}
