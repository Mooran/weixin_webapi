<?php

require_once 'libs/function.php';

// while (true) {

//     start:

//     $process_list = get_cache('process_list');

//     if (!$process_list) {
//         sleep(1);
//         continue;
//     }

//     foreach ($process_list as $k => $id) {

//         $online_list = get_cache('online_list');

//         if (strtoupper(substr(PHP_OS,0,3))==='WIN'){
//             $process_count = -1;
//         }else{
//             $process_count = exec("ps ax | grep wx_listener.php | grep -v 'grep' | wc -l");
//         }


//         if ($process_count >= 10) {
//             sleep(1);
//             goto start;
//         }

//         if (strtoupper(substr(PHP_OS,0,3)) === 'WIN'){
//             exec('start php wx_listener.php ' . $id . ' > log/'.$id );
//         } else {
//             exec('php wx_listener.php ' . $id . ' > log/'.$id.' &');
//         }
        
//         _echo('启动进程, 用户ID: '.$id);

//         $online_list[] = $id;
//         set_cache('online_list', array_unique($online_list));

//         _echo('当时在线进程数: '.count($online_list));

//         $id_info = array('status'=>2);
//         set_cache($id, $id_info);

//         unset($process_list[array_search($id, $process_list)]);

//         set_cache('process_list', $process_list);
//     }
// }

require_once ('./vendor/autoload.php');

use Lyfz\WebWeixin;

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

                $wx = new WebWeixin();

                $wx->setUUID($result->uuid);

                $thread = new ThreadProcess($wx, $result->content, $result->target);

                $thread->start();

                $thread_pool[] = $thread;

                _echo ("启动完成...当前线程数：".count($thread_pool));
            } while(true);
        }
    }

}

Server::Main();