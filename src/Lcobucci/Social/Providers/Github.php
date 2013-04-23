<?php
namespace Lcobucci\Social\Providers;

use Lcobucci\Social\OAuth\OAuthException;
use Lcobucci\Social\OAuth\AccessToken;
use Guzzle\Http\Message\Response;
use Lcobucci\Social\User;

class Github extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    protected function getAuthorizationEndpoint()
    {
        return 'https://github.com/login/oauth/authorize';
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenEndpoint()
    {
        return 'https://github.com/login/oauth/access_token';
    }

    /**
     * {@inheritdoc}
     */
    protected function createToken(Response $response)
    {
        parse_str($response->getBody(true), $authData);

        if (isset($authData['error'])) {
            throw new OAuthException($authData['error']);
        }

        return new AccessToken(
            $authData['access_token'],
            $authData['token_type']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(AccessToken $token)
    {
        $request = $this->httpClient->get(
            'https://api.github.com/user',
            array(
                'Accept' => 'application/json',
                'Authorization' => 'token ' . $token->getToken()
            )
        );

        $response = $request->send();
        $user = $response->json();

        return new User(
            $user['id'],
            $user['login'],
            $user['name'],
            $user['email'],
            $user['avatar_url']
        );
    }
}
