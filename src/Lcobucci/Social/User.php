<?php
namespace Lcobucci\Social;

class User
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $avatar;

    /**
     * @param string $id
     * @param string $login
     * @param string $name
     * @param string $email
     * @param string $avatar
     */
    public function __construct($id, $login, $name, $email = null, $avatar = null)
    {
        $this->id = $id;
        $this->login = $login;
        $this->name = $name;
        $this->email = $email;
        $this->avatar = $avatar;
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
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @return boolean
     */
    public function hasAvatar()
    {
        return $this->avatar !== null;
    }
}
