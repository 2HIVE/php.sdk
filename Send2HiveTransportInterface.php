<?php

interface Send2HiveTransportInterface
{
    public function send($apiKey, array $data);

    public function get($apiKey);
}