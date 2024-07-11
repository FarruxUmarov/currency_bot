<?php

declare(strict_types=1);

class Currency
{
    const string CB_RATE_API_URL = 'https://cbu.uz/uz/arkhiv-kursov-valyut/json/';
    private GuzzleHttp\Client $http;

    public function __construct()
    {
        $this->http = new GuzzleHttp\Client(['base_uri' => self::CB_RATE_API_URL]);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRates()
    {
        return json_decode($this->http->get('')->getBody()->getContents());
    }

    public function getUsd()
    {
        return $this->getRates()[0];
    }
}