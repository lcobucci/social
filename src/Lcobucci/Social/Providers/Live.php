<?php
namespace Lcobucci\Social\Providers;

use Guzzle\Http\Message\Response;
use Lcobucci\Social\OAuth2\AccessToken;
use Lcobucci\Social\OAuth2\BaseProvider;
use Lcobucci\Social\OAuth2\User;

class Live extends BaseProvider
{
    /**
     * {@inheritdoc}
     */
    protected function getAuthorizationEndpoint()
    {
        return 'https://login.live.com/oauth20_authorize.srf';
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenEndpoint()
    {
        return 'https://login.live.com/oauth20_token.srf';
    }

    protected function createAuthorizationParameters(array $scopes, $state)
    {
        $scopes[] = 'wl.basic';
        $scopes[] = 'wl.emails';

        return parent::createAuthorizationParameters($scopes, $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function createToken(Response $response)
    {
        $authData = $response->json();
        $this->raiseException($authData);

        return new AccessToken(
            $authData['access_token'],
            $authData['token_type'],
            explode(' ', $authData['scope']),
            $authData['expires_in']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(AccessToken $token)
    {
        $request = $this->httpClient->get(
            'https://apis.live.net/v5.0/me?access_token=' . $token->getToken(),
            array('Accept' => 'application/json')
        );

        $response = $request->send();
        $user = $response->json();

        return new User(
            $token,
            $user['id'],
            $user['emails']['preferred'],
            $user['name'],
            $user['emails']['preferred']
        );
    }
}
