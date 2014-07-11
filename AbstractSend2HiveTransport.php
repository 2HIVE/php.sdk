<?php

// import
include_once(__DIR__ . '/Send2HiveTransportInterface.php');

abstract class AbstractSend2HiveTransport implements Send2HiveTransportInterface
{
    protected static $_errno;
    protected static $_errstr;

    public static function getError()
    {
        return self::$errno;
    }

    public static function getErrorMessage()
    {
        return self::$_errstr;
    }
}