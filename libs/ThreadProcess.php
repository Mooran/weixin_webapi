<?php
namespace Lyfz;

class ThreadProcess extends \Thread{

    function __construct($instence, $content, $target){
        $this->instence = $instence;
        $this->content = $content;
        $this->target = $target;
    }

    function run(){
        if ($this->instence->init($this->content,$this->target)){
            echo "发送完成，结束进程";
        }
    }
}
