<?php

namespace test;

use Hoa\Websocket\Client as HoaWebsocketClient;
use Hoa\Socket\Client as SocketClient;

require(__DIR__ . '/vendor/autoload.php');

echo 'Connecting client.';

$client = new HoaWebsocketClient(
    new SocketClient('ws://127.0.0.1:8000')
);

$client->setHost('somehost.com');

$client->on('close', function(){
   echo 'Connection closed';
});

$client->run();