<?php
namespace Lcobucci\Social\Providers;

use Lcobucci\Social\OAuth\OAuthException;
use Lcobucci\Social\OAuth\AccessToken;
use Guzzle\Http\Message\Response;
use Lcobucci\Social\User;

class Facebook extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    protected function getAuthorizationEndpoint()
    {
        return 'https://www.facebook.com/dialog/oauth';
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenEndpoint()
    {
        return 'https://graph.facebook.com/oauth/access_token';
    }

    /**
     * {@inheritdoc}
     */
    protected function createToken(Response $response)
    {
        parse_str($response->getBody(true), $authData);

        if (isset($authData['error'])) {
            throw new OAuthException($authData['error_description']);
        }

        return new AccessToken(
            $authData['access_token'],
            null,
            array(),
            (int) $authData['expires']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(AccessToken $token)
    {
        $request = $this->httpClient->get(
            'https://graph.facebook.com/me?access_token=' . $token->getToken(),
            array(
                'Accept' => 'application/json'
            )
        );

        $response = $request->send();
        $user = json_decode($response->getBody(true));

        return new User(
            $user->id,
            $user->username,
            $user->name,
            isset($user->email) ? $user->email : $user->username . '@facebook.com',
            'https://graph.facebook.com/' . $user->username . '/picture'
        );
    }
}
