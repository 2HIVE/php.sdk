<?php

// import
include_once(__DIR__ . '/Send2HiveTransportInterface.php');

abstract class AbstractSend2HiveTransport implements Send2HiveTransportInterface
{
    protected $_errno;
    protected $_errstr;

    public function getError()
    {
        return $this->$errno;
    }

    public function getErrorMessage()
    {
        return $this->$_errstr;
    }
}