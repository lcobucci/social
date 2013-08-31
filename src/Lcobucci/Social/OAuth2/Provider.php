<?php
namespace Lcobucci\Social\OAuth2;

interface Provider extends \Lcobucci\Social\Provider
{
    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUri
     * @return BaseProvider
     */
    public static function create($clientId, $clientSecret, $redirectUri = null);

    /**
     * @param Client $client
     */
    public function __construct(Client $client);

    /**
     * @param AccessToken $token
     * @return User
    */
    public function getUser(AccessToken $token);

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
}
