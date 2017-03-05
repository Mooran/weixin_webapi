<?php

require_once 'libs/function.php';

require_once ('./vendor/autoload.php');

use Lyfz\ThreadProcess;

class Server {
    public static function Main(){
        $thread_pool = [];
        $address = 'localhost';
        $port = 36640;

        if( ($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
            echo "socket_create() failed : reason:" . socket_strerror(socket_last_error()) . "\n";
        }

        if (socket_bind($sock, $address, $port) === false) {
            echo "socket_bind() failed : reason:" . socket_strerror(socket_last_error($sock)) . "\n";
        }
        //监听
        if (socket_listen($sock, 5) === false) {
            echo "socket_bind() failed : reason:" . socket_strerror(socket_last_error($sock)) . "\n";
        }

        while (true) {
            _echo('微信网页版 ... 启动');

            do {

                _echo ('Service Listening...');
                if (($msgsock = socket_accept($sock)) === false) {
                    echo "socket_accepty() failed : reason:".socket_strerror(socket_last_error($sock)) . "\n";
                    break;
                }

                $result = json_decode(socket_read($msgsock, 8192));

                _echo ('Receive a start signal...');

                $thread = new ThreadProcess($result->uuid, $result->content, $result->target, $result->hasImage);

                $thread->start();

                $thread_pool[] = $thread;
                foreach ($thread_pool as $c_key => $c_thread) {
                    if (!$c_thread->isRunning()){
                        unset($thread_pool[$c_key]);
                    }
                }
                _echo ("Start Successfully...The Count of Current Threads is：".count($thread_pool));

                $in = json_encode([
                    "thread_count" => count($thread_pool)
                ]);

                socket_write($msgsock, $in, strlen($in));

                socket_close($msgsock);
                
            } while(true);
        }
    }

}

Server::Main();