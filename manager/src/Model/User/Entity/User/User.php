<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;

class User
{
    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';
    private const STATUS_NEW = 'new';

    /**
     * @var string
     */
    private $id;
    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;
    /**
     * @var Email
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
     * @var ResetToken | null
     */
    private $resetToken;
    /**
     * @var string
     */
    private $status;
    /**
     * @var Network[]|ArrayCollection
     */
    private $networks;

    /**
     * User constructor.
     * @param Id $id
     * @param \DateTimeImmutable $createdAt
     */
    public function __construct(Id $id, \DateTimeImmutable $createdAt)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->status = self::STATUS_NEW;
        $this->networks = new ArrayCollection();
    }

    public function signUpByEmail(Email $email, string $hash, string $token): void
    {
        if (! $this->isNew()) {
            throw new \DomainException('User is already signed up.');
        }

        $this->email = $email;
        $this->passwordHash = $hash;
        $this->confirmToken = $token;
        $this->status = self::STATUS_WAIT;
    }
    /**
     * @param string $network
     * @param string $identity
     */
    public function signUpByNetwork(string $network, string $identity): void
    {
        if (! $this->isNew()) {
            throw new \DomainException('User is already signed up.');
        }

        $this->attachNetwork($network, $identity);
        $this->status = self::STATUS_ACTIVE;
    }
    /**
     * @param ResetToken $token
     * @param \DateTimeImmutable $date
     */
    public function requestPasswordReset(ResetToken $token, \DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new \DomainException('User is not active.');
        }
        if (!$this->email) {
            throw new \DomainException('Email is not specified.');
        }
        if ($this->resetToken && !$this->resetToken->isExpiredTo($date)) {
            throw new \DomainException('Resetting is already request.');
        }
        $this->resetToken = $token;
    }
    public function passwordReset(\DateTimeImmutable $date, string $hash): void
    {
        if (!$this->resetToken) {
            throw new \DomainException('Resisting is not requested.');
        }
        if ($this->resetToken->isExpiredTo($date)) {
            throw new \DomainException('Reset token is expired.');
        }
        $this->passwordHash = $hash;
        $this->resetToken = null;
    }
    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->status === self::STATUS_NEW;
    }
    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function confirmSignUp(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already confirmed.');
        }

        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
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
     * @return Email|null
     */
    public function getEmail(): ?Email
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
    /**
     * @return Network[]|ArrayCollection
     */
    public function getNetworks()
    {
        return $this->networks->toArray();
    }
    /**
     * @return ResetToken|null
     */
    public function getRequestToken(): ?ResetToken
    {
        return $this->resetToken;
    }
    /**
     * @param string $network
     * @param string $identity
     */
    private function attachNetwork(string $network, string $identity): void
    {
        foreach ($this->networks as $existing) {
            if ($existing->isForNetwork($network)) {
                throw new \DomainException('Network is already attached.');
            }
        }

        $this->networks->add(new Network($this, $network, $identity));
    }
}