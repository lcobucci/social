<?php
namespace Lcobucci\Social\OAuth2;

class User extends \Lcobucci\Social\User
{
    /**
     * @var AccessToken
     */
    protected $token;

    /**
     * @param AccessToken $token
     * @param string $id
     * @param string $username
     * @param string $name
     * @param string $email
     * @param string $avatar
     */
    public function __construct(
        AccessToken $token,
        $id,
        $username,
        $name,
        $email = null,
        $avatar = null
    ) {
        $this->token = $token;
        $this->id = $id;
        $this->username = $username;
        $this->name = $name;
        $this->email = $email;
        $this->avatar = $avatar;
    }

    /**
     * @return AccessToken
     */
    public function getToken()
    {
        return $this->token;
    }
}
