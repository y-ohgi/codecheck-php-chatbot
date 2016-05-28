<?php
namespace Sprint;
require 'autoload.php';

# Servers
use \Ratchet\Http\HttpServer;
use \Ratchet\Server\IoServer;
use \Ratchet\WebSocket\WsServer;
# Components
use \Ratchet\ConnectionInterface;
use \Ratchet\MessageComponentInterface;

class Chat implements MessageComponentInterface
{
    private $clients;

    public function __construct()
    {
        // initialize clients storage
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // store new connection in clients
        $this->clients->attach($conn);
        printf("New connection: %s\n", $conn->resourceId);
        // send a welcome message to the client that just connected
        $conn->send(json_encode(array('type' => 'message', 'text' => 'Welcome to the test chat app!')));
    }

    public function onClose(ConnectionInterface $conn)
    {
        // remove connection from clients
        $this->clients->detach($conn);
        printf("Connection closed: %s\n", $conn->resourceId);
    }

    public function onError(ConnectionInterface $conn, Exception $error)
    {
        // display error message and close connection
        printf("Error: %s\n", $error->getMessage());
        $conn->close();
    }

    public function onMessage(ConnectionInterface $from, $message)
    {
        // send message out to all connected clients
        foreach ($this->clients as $client) {
            $client->send(json_encode(['data' => $message]));
        }
    }
}
    /* protected $clients = array(); */

    /* public function __construct(){ */
    /*     $this->clients = new \SplObjectStorage; */
    /* } */
    
    /* public function onOpen(ConnectionInterface $conn) */
    /* { */
    /*     // Nothing to do for echo server */
    /*     $this->clients->attach($conn); */
    /* } */

    /* public function onMessage(ConnectionInterface $from, $message) */
    /* { */
    /*     $from->send(json_encode(['data' => $message])); // Do Echo */

    /*     foreach ($this->clients as $client) { */
    /*         if ($from !== $client) { */
    /*             $client->send($message); */
    /*         } */
    /*     } */
    /* } */

    /* public function onClose(ConnectionInterface $conn) */
    /* { */
    /*     // Nothing to do for echo server */
    /*     $this->clients->detach($conn); */
    /* } */

    /* public function onError(ConnectionInterface $conn, \Exception $e) */
    /* { */
    /*     $conn->close(); */
    /* } */
/* } */

/**
 * Do NOT remove this code.
 * This code is needed for `codecheck` command to see whether server is running or not
 */
$docroot = __DIR__ . '/../public';
$deamon = popen("php -S 0.0.0.0:9000 --docroot {$docroot}", "r");

$base = new HttpServer(new WsServer(new Chat));
$server = IoServer::factory($base, 3000);
$server->run();
