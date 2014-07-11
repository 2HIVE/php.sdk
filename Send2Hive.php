<?php

class Send2Hive
{
    private static $apiKey;
    private static $transport;

    private static $transportInstance;

    private static $pool = array();


    public static function init($apiKey)
    {
        self::$apiKey    = $apiKey;
        self::$transport = 'HTTP'; // only HTTP for now

        // import
        include_once(__DIR__ . '/transport/Send2HiveHTTPTransport.php');
    }


    public static function add(array $m)
    {
        self::$pool[] = $m;
    }

    public static function send()
    {
        $response = self::getTransport()->send(self::$apiKey, self::$pool);

        self::$pool = array();

        return self::processJSONResponse($response);
    }

    public static function get()
    {
        $response = $response = self::getTransport()->send(self::$apiKey);

        return self::processJSONResponse($response);
    }

    public static function getError()
    {
        return self::getTransport()->getError();
    }

    public static function getErrorMessage()
    {
        return self::getTransport()->getErrorMessage();
    }

    protected static function getTransport()
    {
        if (self::$transportInstance == null) {
            $className = 'Send2Hive' . self::$transport . 'Transport';
            self::$transportInstance = new $className;
        }

        return self::$transportInstance;
    }

    private static function processJSONResponse($response)
    {
        if (!trim($response)) {
            return null;
        }

        $result = json_decode($response, true);

        if (empty($result) || !isset($result['status'])) {
            throw new Exception('Invalid Response format: ' . $response);
        }

        if (!isset($result['status']['code'])) {
            $result['status']['code'] = 0; // Unknown Error
        }
        if (!isset($result['status']['msg'])) {
            $result['status']['msg'] = 'Unknown Error';
        }

        if ($result['status']['code'] != 200) {
            self::$_errno  = $result['status']['code'];
            self::$_errstr = $result['status']['msg'];
            throw new Exception(self::$_errstr);
        }

        if (!isset($result['response']) || empty($result['response'])) {
            return null;
        }

        return $result['response'];
    }
}