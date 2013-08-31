<?php
namespace Lcobucci\Social;

use Guzzle\Http\Client as HttpClient;
use Lcobucci\Social\Exceptions\ProviderNotFoundException;
use Lcobucci\Social\OAuth2\Provider as OAuth2Provider;
use Lcobucci\Social\OAuth2\AccessToken as OAuth2Token;
use Lcobucci\Social\OAuth2\User as OAuth2User;
use Symfony\Component\HttpFoundation\ParameterBag;
use Lcobucci\Social\Exceptions\OAuthException;
use Lcobucci\Social\Exceptions\OAuth2\GrantCodeNotFoundException;

class AuthClient
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var array
     */
    private $providers;

    /**
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new HttpClient();
        $this->providers = array();
    }

    /**
     * @param string $identifier
     * @param OAuth2Provider $provider
     */
    public function appendProvider($providerId, OAuth2Provider $provider)
    {
        $provider->setHttpClient($this->httpClient);

        $this->providers[$providerId] = $provider;
    }

    /**
     * @param string $providerId
     * @param array $scopes
     * @param string $state
     * @return string
     */
    public function getAuthorizationUri(
        $providerId,
        array $scopes = array(),
        $state = null
    ) {
        $provider = $this->getProvider($providerId);

        return $provider->createAuthorizationUri($scopes, $state);
    }

    /**
     * @param unknown $providerId
     * @param ParameterBag $getParams
     * @return User|OAuth2User
     */
    public function getAuthenticatedUser($providerId, ParameterBag $getParams)
    {
        $provider = $this->getProvider($providerId);
        $token = $this->retrieveToken($provider, $getParams);

        return $provider->getUser($token);
    }

    /**
     * @param OAuth2Provider $provider
     * @param ParameterBag $getParams
     * @return OAuth2Token
     * @throws OAuthException
     */
    protected function retrieveToken(
        OAuth2Provider $provider,
        ParameterBag $getParams
    ) {
        if ($getParams->has('error')) {
            throw OAuthException::createFromError(
                $getParams->get('error'),
                $getParams->get('error_description')
            );
        }

        if (!$getParams->has('code')) {
            throw new GrantCodeNotFoundException(
                'The grant code parameter was not found'
            );
        }

        return $provider->retrieveToken($getParams->get('code'));
    }

    /**
     * @param string $providerId
     * @return OAuth2Provider
     * @throws ProviderNotFoundException
     */
    protected function getProvider($providerId)
    {
        if (isset($this->providers[$providerId])) {
            return $this->providers[$providerId];
        }

        throw new ProviderNotFoundException(
            'The provider "' . $providerId . '" was not configured'
        );
    }
}
