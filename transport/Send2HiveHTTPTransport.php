<?php

// import
include_once(__DIR__ . '/../AbstractSend2HiveTransport.php');

class Send2HiveHTTPTransport extends AbstractSend2HiveTransport
{
    private $url = '2hive.org';


    public function send($apiKey, array $data)
    {
        return $this->makeRequest($apiKey, $data);
    }

    public function get($apiKey)
    {
        return $this->makeRequest($apiKey);
    }

    private function makeRequest($apiKey, array $data = array())
    {
        // reset last error report
        $this->_errstr = null;
        $this->_errno  = null;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);

        $url = "http://{$this->url}/api/?apikey={$apiKey}&mode=async";

        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('data' => json_encode($data)));
        }

        curl_setopt($ch, CURLOPT_URL, $url);

        $result = curl_exec($ch);

        if ($this->_errno = curl_errno($ch)) {
            $this->_errstr = curl_error($ch);
            throw new Exception('Fail (' . $this->_errstr . ') http request ' . $url . ' / params: ' . print_r($data, true));
        }

        curl_close($ch);

        return $result;
    }
}
