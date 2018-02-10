<?php

namespace App\Entity;

use App\Security\ApiKeyGenerator;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity({"username"}, {"apiKey"})
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @Groups({"public"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true, length=50)
     * @Groups({"public"})
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true, length=128)
     */
    private $apiKey;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=64, nullable=true)
     * @Groups({"public"})
     */
    private $email;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isSuperAdmin;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     * @Groups({"public"})
     */
    private $isAdmin;

    /**
     * For new users, default to not having an admin access
     *
     * @param string|null $username
     */
    public function __construct($username = null)
    {
        $this->username = $username;

        if (strlen($this->username)) {
            $this->apiKey = ApiKeyGenerator::generate();
        }

        $this->isSuperAdmin = false;
        $this->isAdmin      = false;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @var string $username
     * @return $this
     */
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @var string $username
     * @return $this
     */
    public function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Password is the same as the api key.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->apiKey;
    }

    /**
     * @var string $email
     * @return $this
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @var bool $isSuperAdmin
     * @return $this
     */
    public function setIsSuperAdmin(bool $isSuperAdmin)
    {
        $this->isSuperAdmin = $isSuperAdmin;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsSuperAdmin(): bool
    {
        return $this->isSuperAdmin;
    }

    /**
     * More human-readable function; alias of getIsSuperAdmin().
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->isSuperAdmin;
    }

    /**
     * @var bool $isAdmin
     * @return $this
     */
    public function setIsAdmin(bool $isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * More human-readable function; alias of getIsAdmin().
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /** Symfony-specific; not using for this demo */
    public function getSalt() {}
    public function eraseCredentials() {}
}