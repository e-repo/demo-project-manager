<?php

declare(strict_types=1);

namespace App\Security;

use App\Model\User\Entity\User\User;
use Symfony\Component\Security\Core\User\UserInterface;

class UserIdentity implements UserInterface
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $userName;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $role;
    /**
     * @var string
     */
    private $status;

    /**
     * UserIdentity constructor.
     * @param string $id
     * @param string $userName
     * @param string $password
     * @param string $role
     * @param string $status
     */
    public function __construct(
        string $id,
        string $userName,
        string $password,
        string $role,
        string $status
    )
    {
        $this->id = $id;
        $this->userName = $userName;
        $this->password = $password;
        $this->role = $role;
        $this->status = $status;
    }

    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        return [$this->role];
    }

    /**
     * @inheritDoc
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function isActive()
    {
        return $this->status === User::STATUS_ACTIVE;
    }

    /**
     * @inheritDoc
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        $this->userName;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }
}