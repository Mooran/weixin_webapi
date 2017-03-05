<?php

require_once ('./vendor/autoload.php');

use Lyfz\WebWeixin;

class Api {
    public static function Main(){

        $wx = new WebWeixin();

        $uuid = $wx->getUUID();

        $image = file_get_contents($wx->genQRCodeImg());

        $content = filter_input(INPUT_GET, 'content');

        $target = filter_input(INPUT_GET, 'target');

        $image_url = filter_input(INPUT_GET, 'img_url');

        if ($image_url) {

            $file = file_get_contents($image_url);

            file_put_contents('saved/uploads/'.$uuid.'.tmp', $file);
        }

        self::notifyWorkServer($uuid, $content, $target, !!$image_url);

        echo json_encode([
            'code' => 0,
            'info' => 'successfully',
            'data' => [
                'uuid' => $uuid,
                'qrcode' => base64_encode($image)
            ]
        ]);
    }

    public static function notifyWorkServer($uuid, $content, $target, $hasImage = false){
        $address = 'localhost';
        $service_port = 36640;
        
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) {
            echo json_encode([
                'code' => -1,
                'info' => socket_strerror(socket_last_error())
            ]);
            exit;
        }

        $result = socket_connect($socket, $address, $service_port);
        if($result === false) {
            echo json_encode([
                'code' => -1,
                'info' => socket_strerror(socket_last_error())
            ]);
            exit;
        }

        $in = json_encode([
            'uuid' => $uuid,
            'content' => $content,
            'target' => $target,
            'hasImage' => $hasImage
        ]);

        socket_write($socket, $in, strlen($in));
    }
}
Api::Main();