<?php
namespace Lcobucci\Social\Providers;

use Guzzle\Http\Message\Response;
use Lcobucci\Social\OAuth2\AccessToken;
use Lcobucci\Social\OAuth2\BaseProvider;
use Lcobucci\Social\OAuth2\User;

class LinkedIn extends BaseProvider
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
     * @param array $scopes
     * @param string $state
     * @return array
     */
    protected function createAuthorizationParameters(array $scopes, $state)
    {
        $scopes[] = 'r_basicprofile';
        $scopes[] = 'r_emailaddress';

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
        $email = $this->getEmail($token);

        return new User(
            $token,
            (string) $user->id,
            $email,
            (string) $user->{'formatted-name'},
            $email,
            (string) $user->{'picture-url'}
        );
    }

    protected function getEmail(AccessToken $token)
    {
        $request = $this->httpClient->get(
            'https://api.linkedin.com/v1/people/~/email-address?oauth2_access_token='
            . $token->getToken()
        );

        $response = $request->send();

        return (string) $response->xml();
    }
}
