<?php
namespace Lcobucci\Social\Exceptions;

use Lcobucci\Social\Exceptions\OAuth2\AccessDeniedException;
use Lcobucci\Social\Exceptions\OAuth2\InvalidRequestException;
use Lcobucci\Social\Exceptions\OAuth2\InvalidScopeException;
use Lcobucci\Social\Exceptions\OAuth2\ServerErrorException;
use Lcobucci\Social\Exceptions\OAuth2\TemporarilyUnavailableException;
use Lcobucci\Social\Exceptions\OAuth2\UnauthorizedClientException;
use Lcobucci\Social\Exceptions\OAuth2\UnsupportedResponseTypeException;
use Lcobucci\Social\Exceptions\OAuth2\BadVerificationCodeException;

class OAuthException extends \RuntimeException
{
    /**
     * @param string $errorType
     * @param string $errorDescription
     * @return OAuthException
     */
    public static function createFromError($errorType, $errorDescription = null)
    {
        switch ($errorType) {
            case 'invalid_request':
                return new InvalidRequestException(
                    $errorDescription ?: InvalidRequestException::defaultMessage()
                );
            case 'unauthorized_client':
                return new UnauthorizedClientException(
                    $errorDescription ?: UnauthorizedClientException::defaultMessage()
                );
            case 'access_denied':
                return new AccessDeniedException(
                    $errorDescription ?: AccessDeniedException::defaultMessage()
                );
            case 'unsupported_response_type':
                return new UnsupportedResponseTypeException(
                    $errorDescription ?: UnsupportedResponseTypeException::defaultMessage()
                );
            case 'invalid_scope':
                return new InvalidScopeException(
                    $errorDescription ?: InvalidScopeException::defaultMessage()
                );
            case 'server_error':
                return new ServerErrorException(
                    $errorDescription ?: ServerErrorException::defaultMessage()
                );
            case 'temporarily_unavailable':
                return new TemporarilyUnavailableException(
                    $errorDescription ?: TemporarilyUnavailableException::defaultMessage()
                );
            case 'bad_verification_code':
                return new BadVerificationCodeException(
                    $errorDescription ?: BadVerificationCodeException::defaultMessage()
                );
            default:
                return new OAuthException($errorDescription);
        }
    }
}
