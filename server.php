<?php

namespace test;

use Hoa\Event\Bucket;
use Hoa\Websocket\Connection;
use Hoa\Websocket\Node;
use Hoa\Websocket\Server as WebSocketServer;
use Hoa\Socket\Server as SocketServer;

require(__DIR__ . '/vendor/autoload.php');

$websocket = new WebSocketServer(
    new SocketServer("ws://127.0.0.1:8000")
);

/** @var Node[] $connectedNodes */
$connectedNodes = [];

echo 'Starting server';

$websocket->on('open', function (Bucket $bucket) use (&$connectedNodes) {
    /** @var \Hoa\Websocket\Server $source */
    /** @var \Hoa\Socket\Connection\Connection $connection */
    /** @var \Hoa\Socket\Node $node */
    $source = $bucket->getSource();
    $connection = $source->getConnection();
    $node = $connection->getCurrentNode();

    $connectedNodes[] = $node;

    if(count($connectedNodes) > 1){
        // Second client is connected.
        // Now I want to disconnect the first client.
        $firstNode = $connectedNodes[0];

        /** @var \Hoa\Websocket\Connection $connection */
        $connection = $firstNode->getConnection();

        $connection->close(
            Connection::CLOSE_NORMAL,
            'Go away'
        );
    }
});

$websocket->run();