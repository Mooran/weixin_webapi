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

                _echo ('服务监听已启动，等待启动消息...');
                if (($msgsock = socket_accept($sock)) === false) {
                    echo "socket_accepty() failed : reason:".socket_strerror(socket_last_error($sock)) . "\n";
                    break;
                }

                $result = json_decode(socket_read($msgsock, 8192));

                socket_close($msgsock);
                
                _echo ('接收到启动消息，启动中...');

                $thread = new ThreadProcess($result->uuid, $result->content, $result->target, $result->hasImage);

                $thread->start();

                $thread_pool[] = $thread;

                _echo ("启动完成...当前线程数：".count($thread_pool));
            } while(true);
        }
    }

}

Server::Main();