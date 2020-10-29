<?php

namespace App\Service\Api;

use App\Service\Api\DefaultApiRequest;

class OpenWeatherApiRequest extends DefaultApiRequest
{

    /**
     * @inheritDoc
     */
    public function fetchTemperature()
    {
        $requestUrl = $this->generateRequestUrl();

        $response = $this->client->request('GET',$requestUrl);
        
        if ( $response->getStatusCode() === 200 ) {
            $content = $response->toArray();
            if ( isset($content['main'][$this->source->getField()]) ) {
                return $content['main'][$this->source->getField()];
            }
        } 

        return 'Something went wrong, try again later';
    }

    /**
     * @inheritDoc
     */
    public function doesSupport() : bool
    {
        $base = $this->source->getUrl();
        $needle = 'api.openweathermap.org';
        return stripos($base,$needle) !== false;
    }

}