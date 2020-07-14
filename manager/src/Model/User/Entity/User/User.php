<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

class User
{
    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';

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
    /**
     * @var string | null
     */
    private $confirmToken;
    /**
     * @var string
     */
    private $status;

    /**
     * User constructor.
     * @param Id $id
     * @param \DateTimeImmutable $createdAt
     * @param Email $email
     * @param string $hash
     * @param string $token
     */
    public function __construct(
        Id $id,
        \DateTimeImmutable $createdAt,
        Email $email,
        string $hash,
        string $token
    )
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->email = $email;
        $this->passwordHash = $hash;
        $this->confirmToken = $token;
        $this->status = self::STATUS_WAIT;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }
    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }
    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
    /**
     * @return Email
     */
    public function getEmail(): Email
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
    /**
     * @return string | null
     */
    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }
}