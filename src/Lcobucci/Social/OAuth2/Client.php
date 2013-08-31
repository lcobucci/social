<?php
namespace Lcobucci\Social\OAuth2;

class Client
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var string
     */
    private $redirectUri;

    /**
     * @var array
     */
    private $defaultScopes;

    /**
     * @param string $id
     * @param string $secret
     * @param string $redirectUri
     * @param array $defaulScopes
     */
    public function __construct(
        $id,
        $secret,
        $redirectUri = null,
        array $defaulScopes = array()
    ) {
        $this->id = $id;
        $this->secret = $secret;
        $this->redirectUri = $redirectUri;
        $this->defaultScopes = $defaulScopes;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @return array
     */
    public function getDefaultScopes()
    {
        return $this->defaultScopes;
    }
}
