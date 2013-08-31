<?php
namespace Lcobucci\Social\Exceptions\OAuth2;

use Lcobucci\Social\Exceptions\OAuthException;

class BadVerificationCodeException extends OAuthException
{
    /**
     * @return string
     */
    public static function defaultMessage()
    {
        return 'The verification code is not valid.';
    }
}
