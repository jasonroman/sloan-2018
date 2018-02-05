<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @Groups({"public"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Groups({"public"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $apiKey;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * Password is the same as the api key.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->apiKey;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }
}