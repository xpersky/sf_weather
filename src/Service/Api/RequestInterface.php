<?php

namespace App\Service\Api;

interface RequestInterface
{

    /**
     * Generate request url
     * 
     * @return string result url
     */
    public function generateRequestUrl() :string;

    /**
     * Fetch temperature
     * 
     * @return string temperature
     */
    public function fetchTemperature();

    /**
     * Check if base url is supported
     * 
     * @return bool
     */
    public function doesSupport() : bool;
}