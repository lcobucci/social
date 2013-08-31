<?php
namespace Lcobucci\Social\Exceptions\OAuth2;

use Lcobucci\Social\Exceptions\OAuthException;

class AccessDeniedException extends OAuthException
{
    /**
     * @return string
     */
    public static function defaultMessage()
    {
        return 'The resource owner or authorization server denied the '
                . 'request.';
    }
}
