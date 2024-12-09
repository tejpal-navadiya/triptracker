<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\MailController;

class FetchEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:fetch {tripId} {userId} {uniqId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch emails for a specific trip every 5 minutes.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $tripId = $this->argument('tripId');
        $userId = $this->argument('userId');
        $uniqId = $this->argument('uniqId');
        try {
            $mailController = new MailController();
            $response = $mailController->fetchEmails($tripId, $userId, $uniqId);
            $this->info('Emails fetched successfully: ' . json_encode($response->getData()));
        } catch (\Exception $e) {
            $this->error('Error fetching emails: ' . $e->getMessage());
        }
    }
}
