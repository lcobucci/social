<?php
namespace Lcobucci\Social\OAuth;

class AccessToken
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $scopes;

    /**
     * @var int
     */
    private $expires;

    /**
     * @var string
     */
    private $refreshToken;

    /**
     * @param string $token
     * @param string $type
     * @param array $scopes
     * @param int $expires
     */
    public function __construct(
        $token,
        $type = null,
        array $scopes = array(),
        $expires = null,
        $refreshToken = null
    ) {
        $this->token = $token;
        $this->type = $type;
        $this->scopes = $scopes;
        $this->expires = $expires;
        $this->refreshToken = $refreshToken;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return multitype:
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * @param string $scope
     * @return boolean
     */
    public function hasScope($scope)
    {
        return in_array($scope, $this->scopes);
    }

    /**
     * @return number
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }
}
