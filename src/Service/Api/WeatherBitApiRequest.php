<?php

namespace App\Service\Api;

use App\Service\Api\DefaultApiRequest;

class WeatherBitApiRequest extends DefaultApiRequest
{

    /**
     * @inheritDoc
     */
    public function generateRequestUrl() : string
    {
        $result = $this->source->getUrl();
        if ( substr($result,0,4) != 'http' ) {
            $result = 'https://'.$result;
        }
        $country = $this->getCountryCode();
        $result = str_replace('{country}',$country,$result);
        $result = str_replace('{city}',$this->city,$result);
        $result = str_replace('{apikey}',$this->source->getApikey(),$result);
        $result.= '&'.$this->source->getOptions();

        return $result;
    }
    
    /**
     * @inheritDoc
     */
    public function fetchTemperature()
    {
        $requestUrl = $this->generateRequestUrl();

        $response = $this->client->request('GET',$requestUrl);
        if ( $response->getStatusCode() === 200 ) {
            $content = $response->toArray();
            $content = $content['data'][0];
            if ( isset($content[$this->source->getField()]) ) {
                return $content[$this->source->getField()];
            }
        } 

        return false;
    }

    /**
     * @inheritDoc
     */
    public function doesSupport() : bool
    {
        $base = $this->source->getUrl();
        $needle = 'api.weatherbit.io';
        return stripos($base,$needle) !== false;
    }

}