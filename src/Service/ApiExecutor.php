<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Sources;

use App\Service\Api\DefaultApiRequest;
use App\Service\Api\OpenWeatherApiRequest;
use App\Service\Api\WeatherBitApiRequest;

class ApiExecutor
{

    /**
     * ApiExecutor execute method
     * 
     * @param string $country 
     * @param string $city
     * @param array $sources api sources
     * @param HttpClientInterface $client
     * @param Sources $cordsource coordinates source
     */
    public function execute(
        string $country,
        string $city,
        array $sources,
        HttpClientInterface $client,
        Sources $cordsource
        ) 
    {
        $temperature = [];

        foreach ( $sources as $source ) {

            $apiExec = new OpenWeatherApiRequest($country,$city,$source,$client,$cordsource);
            if ( $apiExec->doesSupport() ) {
                $temperature[] = $apiExec->fetchTemperature();
                continue;
            } else {
                unset($apiExec);
            }

            $apiExec = new WeatherBitApiRequest($country,$city,$source,$client,$cordsource);
            if ( $apiExec->doesSupport() ) {
                $temperature[] = $apiExec->fetchTemperature();
                continue;
            } else {
                unset($apiExec);
            }
            
            $apiExec = new DefaultApiRequest($country,$city,$source,$client,$cordsource);
            if ( $apiExec->doesSupport() ) {
                $temperature[] = $apiExec->fetchTemperature();
                continue;
            } else {
                unset($apiExec);
            }
        }
        
        $temperature = array_filter($temperature,function($o) { return $o !== false;});
        
        if ( count($temperature) ) {
            return array_sum($temperature) / count($temperature);
        } return false;
    }

}