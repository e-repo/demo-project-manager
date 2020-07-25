<?php

declare(strict_types=1);

namespace App\Tests\Builder\User;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;

class UserBuilder
{
    private $id;
    private $date;

    private $email;
    private $hash;
    private $token;

    private $network;
    private $identity;

    /**
     * UserBuilder constructor.
     */
    public function __construct()
    {
        $this->id = Id::next();
        $this->date = new \DateTimeImmutable();
    }

    /**
     * @param Email|null $email
     * @param string|null $hash
     * @param string|null $token
     * @return $this
     */
    public function viaEmail(
        Email $email = null,
        string $hash = null,
        string $token = null
    ): self
    {
        $clone = clone $this;
        $clone->email = $email ?? new Email('test@test.ru');
        $clone->hash = $hash ?? 'hash';
        $clone->token = $token ?? 'token';
        return $clone;
    }

    /**
     * @param string|null $network
     * @param string|null $identity
     * @return $this
     */
    public function viaNetwork(string $network = null, string $identity = null): self
    {
        $clone = clone $this;
        $clone->network = $network ?? 'vk';
        $clone->identity = $identity ?? '0000001';
        return $clone;
    }

    /**
     * @return User
     */
    public function build(): User
    {
        $user = new User(
            $this->id,
            $this->date
        );
        if ($this->email) {
            $user->signUpByEmail(
                $this->email,
                $this->hash,
                $this->token
            );
        }
        if ($this->network) {
            $user->signUpByNetwork(
                $this->network,
                $this->identity
            );
        }
        return $user;
    }

    /**
     * @return Id|null
     */
    public function getId(): ?Id
    {
        return $this->id;
    }

    /**
     * @return Email|null
     */
    public function getEmail(): ?Email
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getHash(): ?string
    {
        return $this->hash;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @return string|null
     */
    public function getNetwork(): ?string
    {
        return $this->network;
    }

    /**
     * @return string|null
     */
    public function getIdentity(): ?string
    {
        return $this->identity;
    }
}