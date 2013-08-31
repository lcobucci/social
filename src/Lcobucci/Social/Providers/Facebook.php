<?php
namespace Lcobucci\Social\Providers;

use Guzzle\Http\Message\Response;
use Lcobucci\Social\OAuth2\AccessToken;
use Lcobucci\Social\OAuth2\BaseProvider;
use Lcobucci\Social\OAuth2\User;

class Facebook extends BaseProvider
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
        $this->raiseException($authData);

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
        $user = $response->json();

        return new User(
            $token,
            $user['id'],
            $user['username'],
            $user['name'],
            isset($user['email']) ? $user['email'] : $user['username'] . '@facebook.com',
            'https://graph.facebook.com/' . $user['username'] . '/picture'
        );
    }
}
