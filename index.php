<?php

//require_once ('./vendor/autoload.php');

// $test = 1;
// $name = 'test';

// class Person{
//     private $name_i = 'zjy';
//     private $age_i = '233';
//     public function getProperty($propertyName){
//         $propertyName .= '_i';
//         return $this->$propertyName;
//     }
// }
// $p = new Person();
// echo $p->getProperty('age');
// 

class Person {

    protected $name;

    function setName($name){
        $this->name = $name;
        echo $this->getName();
    }

    function getName(){
        return $this->name;
    }
}

class My extends Thread{

    function __construct(){
    }

    function run(){
        $p = new Person();
        $p->setName($this->getThreadId());
        echo $p->getName();
    }
}

// for ($i = 0;$i < 20;$i ++)
// {

    $thread = new My();

    $thread->start();
// }
