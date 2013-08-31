<?php
namespace Lcobucci\Social\Exceptions\OAuth2;

use Lcobucci\Social\Exceptions\OAuthException;

class UnauthorizedClientException extends OAuthException
{
    /**
     * @return string
     */
    public static function defaultMessage()
    {
        return 'The client is not authorized to request an authorization '
                . 'code using this method.';
    }
}
