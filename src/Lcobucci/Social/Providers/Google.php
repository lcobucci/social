<?php
namespace Lcobucci\Social\Providers;

use Lcobucci\Social\OAuth\AccessToken;
use Lcobucci\Social\OAuth\OAuthException;
use Guzzle\Http\Message\Response;
use Lcobucci\Social\User;

class Google extends OAuth2
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
        $authData = json_decode($response->getBody(true));

        if (isset($authData->error)) {
            throw new OAuthException($authData->error_description);
        }

        return new AccessToken(
            $authData->access_token,
            $authData->token_type,
            array(),
            (int) $authData->expires_in
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
        $user = json_decode($response->getBody(true));

        return new User(
            $user->id,
            $user->email,
            $user->name,
            $user->email,
            $user->picture
        );
    }
}
