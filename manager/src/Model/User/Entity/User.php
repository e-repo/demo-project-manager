<?php

declare(strict_types=1);

namespace App\Model\User\Entity;

class User
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $passwordHash;

    public function __construct(string $id, \DateTimeImmutable $createdAt, string $email, string $hash)
    {
        $this->id = $id;
        $this->createdAt = $createdAt,
        $this->email = $email;
        $this->passwordHash = $hash;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
}