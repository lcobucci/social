<?php
namespace Lcobucci\Social;

use Lcobucci\Social\OAuth\AccessToken;
use Guzzle\Http\Client as HttpClient;
use Lcobucci\Social\OAuth\Client;

interface Provider
{
    /**
     * @param Client $client
     * @param HttpClient $client
     */
    public function __construct(Client $client, HttpClient $httpClient);

    /**
     * @param array $scopes
     * @param string $state
     * @return string
     */
    public function createAuthorizationUri(array $scopes = array(), $state = null);

    /**
     * @param Client $client
     * @param string $code
     * @return AccessToken
     */
    public function retrieveToken($code);

    /**
     * @param AccessToken $token
     * @return User
     */
    public function getUser(AccessToken $token);
}
