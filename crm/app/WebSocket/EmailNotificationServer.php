<?php 
namespace App\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class EmailNotificationServer implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    // When a new client connects
    public function onOpen(ConnectionInterface $conn)
    {
        \Log::info('New WebSocket connection: ' . $conn->resourceId);
        $conn->send('Hello, WebSocket!');
        $this->clients->attach($conn);
        
    }

    // When a message is received from a client (you can handle it here if needed)
    public function onMessage(ConnectionInterface $from, $msg)
    {
        // Optionally handle incoming messages from clients
    }

    // When a client disconnects
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    // When an error occurs
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }

    // Send a message to all connected clients
    public function sendMessage($message)
    {
        foreach ($this->clients as $client) {
            $client->send($message);
        }
    }


}
