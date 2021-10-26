<?php

namespace App\Dao;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


use Symfony\Contracts\HttpClient\HttpClientInterface;


use Symfony\Component\HttpClient\HttpClient;

class OpenWeathermap
{

    private $client;
    private $apiKey = "5466fb57562c713caac7552dcd211a65";

    public function __construct()
    {
        $this->client = HttpClient::create();
    }



    public function getWeather(String $lat, String $lon): Array {

        //https://symfony.com/doc/current/http_client.html
    
        $response = $this->client->request(
            'GET',
            'https://api.openweathermap.org/data/2.5/onecall?exclude=hourly,current,minutely,alerts&units=metric&lat='.$lat.'&lon='.$lon.'&appid='.$this->apiKey
        );
            
        return $response->toArray();
    
    }

    public function getWeatherByTown(String $town): Array {

        //https://symfony.com/doc/current/http_client.html
    
        $response = $this->client->request(
            'GET',
            'https://api.openweathermap.org/data/2.5/weather?units=metric&q='.$town.'&appid='.$this->apiKey
        );
            
        return $response->toArray();
    
    }


    public function getLatLon(String $town): Array {
    
        $response = $this->client->request(
            'GET',
            'https://api.openweathermap.org/geo/1.0/direct?limit=1&q='.$town.'&appid='.$this->apiKey
        );
            
        return $response->toArray();
    
    }

        
}
