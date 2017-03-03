<?php
$address = 'localhost';
$service_port = 36640;
echo "Attempting to connect to '$address' on port '$service_port'...";
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} else {
    echo "OK. \n";
}

$result = socket_connect($socket, $address, $service_port);
if($result === false) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "OK \n";
}
$in = '{"name":"zhongjianyu"}';
socket_write($socket, $in, strlen($in));