<?php
namespace Lcobucci\Social\OAuth;

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
     * @param string $id
     * @param string $secret
     * @param string $redirectUri
     */
    public function __construct($id, $secret, $redirectUri = null)
    {
        $this->id = $id;
        $this->secret = $secret;
        $this->redirectUri = $redirectUri;
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
}
