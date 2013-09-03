<?php
namespace Lcobucci\Social\Providers;

use Guzzle\Http\Message\Response;
use Lcobucci\Social\OAuth2\AccessToken;
use Lcobucci\Social\OAuth2\BaseProvider;
use Lcobucci\Social\OAuth2\User;

class Google extends BaseProvider
{
    /**
     * {@inheritdoc}
     */
    protected function getAuthorizationEndpoint()
    {
        return 'https://accounts.google.com/o/oauth2/auth';
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenEndpoint()
    {
        return 'https://accounts.google.com/o/oauth2/token';
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
            array(),
            (int) $authData['expires_in']
        );
    }

    /**
     * @param array $scopes
     * @param string $state
     * @return array
     */
    protected function createAuthorizationParameters(array $scopes, $state)
    {
        $scopes[] = 'https://www.googleapis.com/auth/userinfo.email';
        $scopes[] = 'https://www.googleapis.com/auth/userinfo.profile';

        return parent::createAuthorizationParameters($scopes, $state);
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(AccessToken $token)
    {
        $request = $this->httpClient->get(
            'https://www.googleapis.com/oauth2/v2/userinfo',
            array(
                'Accept' => 'application/json',
                'Authorization' => 'OAuth ' . $token->getToken()
            )
        );

        $response = $request->send();
        $user = $response->json();

        return new User(
            $token,
            isset($user['id']) ? $user['id'] : null,
            isset($user['email']) ? $user['email'] : null,
            isset($user['name']) ? $user['name'] : null,
            isset($user['email']) ? $user['email'] : null,
            isset($user['picture']) ? $user['picture'] : null
        );
    }
}
