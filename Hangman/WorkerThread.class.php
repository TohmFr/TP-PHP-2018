<?php
/**
 * Created by PhpStorm.
 * User: tbardy
 * Date: 12/10/2018
 * Time: 15:17
 */

class WorkerThread extends Thread  {

    private $_function;

    public function __construct($function)
    {
        $this->_function = $function;
    }

    public  function run(){
        $this->_function();

    }
}