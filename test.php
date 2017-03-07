<?php

require_once ('./vendor/autoload.php');

use Lyfz\WebWeixin;

class TestApp {

    public static function Main(){
        $wx = new WebWeixin();

        $wx->getUUID();

        exec('"'.$wx->genQRCodeImg().'"');

        $wx->init();

        $wx->MassSend("测试", "contact");

        $wx->logout();
    }

}

TestApp::Main();