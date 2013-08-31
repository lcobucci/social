<?php
namespace Lcobucci\Social\Exceptions\OAuth2;

use Lcobucci\Social\Exceptions\OAuthException;

class ServerErrorException extends OAuthException
{
    /**
     * @return string
     */
    public static function defaultMessage()
    {
        return 'The authorization server encountered an unexpected '
                . 'condition that prevented it from fulfilling the request.';
    }
}
