<?php
namespace Lcobucci\Social\OAuth2;

use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Message\Response;
use Lcobucci\Social\Exceptions\OAuthException;

abstract class BaseProvider implements Provider
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
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUri
     * @param array $defaultScopes
     * @return BaseProvider
     */
    public static function create(
        $clientId,
        $clientSecret,
        $redirectUri = null,
        array $defaultScopes = array()
    ) {
        return new static(
            new Client($clientId, $clientSecret, $redirectUri, $defaultScopes)
        );
    }

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @see \Lcobucci\Social\Provider::setHttpClient()
     */
    public function setHttpClient(HttpClient $httpClient)
    {
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

        $scopes = array_merge($this->client->getDefaultScopes(), $scopes);

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
        $request = $this->httpClient->post($this->getTokenEndpoint());
        $request->addPostFields($this->createTokenParameters($code));

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
     * @param array $data
     * @throws OAuthException
     */
    protected function raiseException(array $data)
    {
        if (isset($data['error'])) {
            throw OAuthException::createFromError(
                $data['error'],
                isset($data['error_description']) ? $data['error_description'] : null
            );
        }
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
