<?php
namespace Lcobucci\Social\Exceptions\OAuth2;

use Lcobucci\Social\Exceptions\OAuthException;

class TemporarilyUnavailableException extends OAuthException
{
    /**
     * @return string
     */
    public static function defaultMessage()
    {
        return 'The authorization server is currently unable to handle '
                . 'the request due to a temporary overloading or '
                . 'maintenance of the server.';
    }
}
