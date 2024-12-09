<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\App;
use App\WebSocket\EmailNotificationServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class StartWebSocketServer extends Command
{
    protected $signature = 'websocket:start';
    protected $description = 'Start the WebSocket server for email notifications';

    public function __construct()
    {
        parent::__construct();
    }

    // This command will start the WebSocket server
    public function handle()
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new EmailNotificationServer()
                )
            ),6001
        );
    }
}
