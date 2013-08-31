<?php
namespace Lcobucci\Social\Exceptions\OAuth2;

use Lcobucci\Social\Exceptions\OAuthException;

class InvalidRequestException extends OAuthException
{
    /**
     * @return string
     */
    public static function defaultMessage()
    {
        return 'The request is missing a required parameter, includes an '
                . 'invalid parameter value, includes a parameter more than '
                . 'once, or is otherwise malformed.';
    }
}
