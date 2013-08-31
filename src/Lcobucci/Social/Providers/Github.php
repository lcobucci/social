<?php
namespace Lcobucci\Social\Providers;

use Guzzle\Http\Message\Response;
use Lcobucci\Social\OAuth2\AccessToken;
use Lcobucci\Social\OAuth2\BaseProvider;
use Lcobucci\Social\OAuth2\User;

class Github extends BaseProvider
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
        $this->raiseException($authData);

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
            $token,
            $user['id'],
            $user['login'],
            $user['name'],
            $user['email'],
            $user['avatar_url']
        );
    }
}
