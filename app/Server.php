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
    public function onOpen(ConnectionInterface $conn)
    {
        // Nothing to do for echo server
    }

    public function onMessage(ConnectionInterface $from, $message)
    {
        $from->send(json_encode(['data' => $message])); // Do Echo
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Nothing to do for echo server
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }
}

/**
 * Do NOT remove this code.
 * This code is needed for `codecheck` command to see whether server is running or not
 */
$docroot = __DIR__ . '/../public';
$deamon = popen("php -S 0.0.0.0:9000 --docroot {$docroot}", "r");

$base = new HttpServer(new WsServer(new Chat));
$server = IoServer::factory($base, 3000);
$server->run();
