<?php
namespace Lcobucci\Social\Exceptions\OAuth2;

use Lcobucci\Social\Exceptions\OAuthException;

class InvalidScopeException extends OAuthException
{
    /**
     * @return string
     */
    public static function defaultMessage()
    {
        return 'The requested scope is invalid, unknown, or malformed.';
    }
}
