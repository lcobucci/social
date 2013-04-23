<?php
namespace Lcobucci\Social\Providers;

use Lcobucci\Social\OAuth\AccessToken;
use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Message\Response;
use Lcobucci\Social\OAuth\Client;
use Lcobucci\Social\Provider;

abstract class OAuth2 implements Provider
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @param Client $client
     * @param HttpClient $client
     */
    public function __construct(Client $client, HttpClient $httpClient)
    {
        $this->client = $client;
        $this->httpClient = $httpClient;
    }

    /**
     * @param Client $client
     * @param array $scopes
     * @param string $state
     * @return string
     */
    public function createAuthorizationUri(array $scopes = array(), $state = null)
    {
        $uri = $this->getAuthorizationEndpoint();
        $params = http_build_query($this->createAuthorizationParameters($scopes, $state));

        return $uri . '?' . $params;
    }

    /**
     * @param array $scopes
     * @param string $state
     * @return array
     */
    protected function createAuthorizationParameters(array $scopes, $state)
    {
        $params = array(
            'client_id' => $this->client->getId(),
            'response_type' => 'code'
        );

        if ($url = $this->client->getRedirectUri()) {
            $params['redirect_uri'] = $url;
        }

        if ($state) {
            $params['state'] = $state;
        }

        if ($scopes && isset($scopes[0])) {
            $params['scope'] = implode(' ', $scopes);
        }

        return $params;
    }

    /**
     * @param Client $client
     * @param string $code
     * @return AccessToken
    */
    public function retrieveToken($code)
    {
        $request = $this->httpClient->post(
            $this->getTokenEndpoint(),
            array(
                'Content-Type' => 'application/x-www-form-urlencoded'
            ),
            http_build_query($this->createTokenParameters($code))
        );

        return $this->createToken($request->send());
    }

    /**
     * @param string $code
     * @return array
     */
    protected function createTokenParameters($code)
    {
        $params = array(
            'code' => $code,
            'client_id' => $this->client->getId(),
            'client_secret' => $this->client->getSecret(),
            'grant_type' => 'authorization_code'
        );

        if ($url = $this->client->getRedirectUri()) {
            $params['redirect_uri'] = $url;
        }

        return $params;
    }

    /**
     * @return string
     */
    abstract protected function getAuthorizationEndpoint();

    /**
     * @return string
     */
    abstract protected function getTokenEndpoint();

    /**
     * @param Response $response
     * @return AccessToken
     */
    abstract protected function createToken(Response $response);
}
