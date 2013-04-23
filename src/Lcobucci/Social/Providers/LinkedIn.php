<?php
namespace Lcobucci\Social\Providers;

use Lcobucci\Social\OAuth\OAuthException;
use Lcobucci\Social\OAuth\AccessToken;
use Guzzle\Http\Message\Response;
use Lcobucci\Social\User;

class LinkedIn extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    protected function getAuthorizationEndpoint()
    {
        return 'https://www.linkedin.com/uas/oauth2/authorization';
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenEndpoint()
    {
        return 'https://www.linkedin.com/uas/oauth2/accessToken';
    }

    /**
     * {@inheritdoc}
     */
    protected function createToken(Response $response)
    {
        $authData = $response->json();

        if (isset($authData['error'])) {
            throw new OAuthException($authData['error']);
        }

        return new AccessToken(
            $authData['access_token'],
            null,
            array(),
            $authData['expires_in']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(AccessToken $token)
    {
        $request = $this->httpClient->get(
            'https://api.linkedin.com/v1/people/~:(id,formatted-name,'
            . 'picture-url)?oauth2_access_token=' . $token->getToken()
        );

        $response = $request->send();
        $user = $response->xml();

        return new User(
            (string) $user->id,
            null,
            (string) $user->{'formatted-name'},
            null,
            (string) $user->{'picture-url'}
        );
    }
}
