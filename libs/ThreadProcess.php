<?php
namespace Lyfz;

class ThreadProcess extends \Thread{

    function __construct($uuid, $content, $target, $hasImage){
        $this->uuid = $uuid;
        $this->content = $content;
        $this->target = $target;
        $this->hasImage = $hasImage;
    }

    function run(){
        /* 线程处理函数中 无法调用vendor的autoload，需要手动require*/
        require_once("./libs/WebWeixin.php");
        
        $wx = new WebWeixin();

        $wx->setUUID($this->uuid);

        if ($wx->init()){

            $wx->MassSend($this->content, $this->target, $this->hasImage);

            $wx->logout();

            echo "发送完成，结束进程";
        }
    }
}
