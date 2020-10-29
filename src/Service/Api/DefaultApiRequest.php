<?php

namespace App\Service\Api;

use App\Service\Api\RequestInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Sources;

class DefaultApiRequest implements RequestInterface
{
    protected $country;
    protected $city;
    protected $source;
    protected $client;
    protected $cordsource;

    /**
     * DefaultApiRequest contructor
     * 
     * @param string $country 
     * @param string $city
     * @param Sources $source api source
     * @param HttpClientInterface $client
     * @param Sources $cordsource coordinates source
     */
    public function __construct(
        string $country,
        string $city,
        Sources $source,
        HttpClientInterface $client,
        Sources $cordsource
        ) 
    {
        $this->country = $country;
        $this->city = $city;
        $this->source = $source;
        $this->client = $client;
        $this->cordsource = $cordsource;
    }

    /**
     * @inheritDoc
     */
    public function generateRequestUrl() : string
    {
        $result = $this->source->getUrl();
        if ( substr($result,0,4) != 'http' ) {
            $result = 'https://'.$result;
        }
        $result = str_replace('{country}',$this->country,$result);
        $result = str_replace('{city}',$this->city,$result);
        $result = str_replace('{apikey}',$this->source->getApikey(),$result);
        $result.= '&'.$this->source->getOptions();

        return $result;
    }

    /**
     * Get country code if needed
     */
    protected function getCountryCode() 
    {
        $url = 'https://restcountries.eu/rest/v2/name/'.$this->country;
        $response = $this->client->request('GET',$url);

        if ( $response->getStatusCode() === 200 ) {
            $content = $response->toArray();
            return $content[0]['alpha2Code'];
        } 

        return false;
    }

    /**
     * Get latitude/longitude pair if needed
     */
    protected function getLocation() 
    {
        $cords = $this->cordsource;

        $result = $cords->getUrl();
        $result = str_replace('{country}',$this->country,$result);
        $result = str_replace('{city}',$this->city,$result);
        $result = str_replace('{apikey}',$cords->getApikey(),$result);

        $response = $this->client->request('GET',$result);

        if ( $response->getStatusCode() === 200 ) {
            $content = $response->toArray()['data'][0];
            if ( isset($content['latitude']) && isset($content['longitude']) ) {
                return [$content['latitude'],$content['longitude']];
            }
        } 

        return false;
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
        return true;
    }

}