<?php
namespace Lcobucci\Social\Exceptions\OAuth2;

use Lcobucci\Social\Exceptions\OAuthException;

class UnsupportedResponseTypeException extends OAuthException
{
    /**
     * @return string
     */
    public static function defaultMessage()
    {
        return 'The authorization server does not support obtaining an '
                . 'authorization code using this method.';
    }
}
