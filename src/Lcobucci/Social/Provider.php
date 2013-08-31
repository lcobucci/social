<?php
namespace Lcobucci\Social;

use Guzzle\Http\Client as HttpClient;

interface Provider
{
    /**
     * @param HttpClient $httpClient
     */
    public function setHttpClient(HttpClient $httpClient);
}
