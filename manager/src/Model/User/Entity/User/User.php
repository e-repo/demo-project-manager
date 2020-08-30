<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package App\Model\User\Entity\User
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="user_users", uniqueConstraints={
 *      @ORM\UniqueConstraint(columns={"email"}),
 *      @ORM\UniqueConstraint(columns={"reset_token_token"})
 * })
 */
class User
{
    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';
    private const STATUS_NEW = 'new';

    /**
     * @var Id
     * @ORM\Column(type="user_user_id")
     * @ORM\Id
     */
    private $id;
    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="date_immutable")
     */
    private $createdAt;
    /**
     * @var Email
     * @ORM\Column(type="user_user_email", nullable=true)
     */
    private $email;
    /**
     * @var string
     * @ORM\Column(type="string", name="password_hash", nullable=true)
     */
    private $passwordHash;
    /**
     * @var string | null
     * @ORM\Column(type="string", name="confirm_token", nullable=true)
     */
    private $confirmToken;
    /**
     * @var ResetToken | null
     * @ORM\Embedded(class="ResetToken", columnPrefix="reset_token_")
     */
    private $resetToken;
    /**
     * @var string
     * @ORM\Column(type="string", length=16)
     */
    private $status;
    /**
     * @var Network[]|ArrayCollection
     *
     * cascade={"persist"} - задан с целью сохранения сущности пользователя
     * одновременной с сохранением сущности 'network', что-то вроде
     * группового сохранения
     *
     * mappedBy="user" - создает 'network' четез связь свойство(связь) 'user'
     *
     * orphanRemoval=true - дополнительная пометка для каскадного удаления
     * связанных элементов, что-бы связанные элементы были "наверняка" удалены
     *
     * @ORM\OneToMany(targetEntity="Network", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private $networks;
    /**
     * @var Role
     * @ORM\Column(type="user_user_role", length=16)
     */
    private $role;

    /**
     * User constructor.
     * @param Id $id
     * @param \DateTimeImmutable $createdAt
     */
    private function __construct(Id $id, \DateTimeImmutable $createdAt)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->role = Role::user();
        $this->networks = new ArrayCollection();
    }

    /**
     * @param Id $id
     * @param \DateTimeImmutable $createdAt
     * @param Email $email
     * @param string $hash
     * @param string $token
     * @return static
     */
    public static function signUpByEmail(Id $id, \DateTimeImmutable $createdAt, Email $email, string $hash, string $token): self
    {
        $user = new self($id, $createdAt);
        $user->email = $email;
        $user->passwordHash = $hash;
        $user->confirmToken = $token;
        $user->status = self::STATUS_WAIT;
        return $user;
    }

    /**
     * @param Id $id
     * @param \DateTimeImmutable $createdAt
     * @param string $network
     * @param string $identity
     * @return User
     */
    public static function signUpByNetwork(Id $id, \DateTimeImmutable $createdAt, string $network, string $identity): self
    {
        $user = new self($id, $createdAt);
        $user->attachNetwork($network, $identity);
        $user->status = self::STATUS_ACTIVE;
        return $user;
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
    /**
     * @param \DateTimeImmutable $date
     * @param string $hash
     */
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
    public function changeRole(Role $role): void
    {
        if ($this->role->isEqual($role)) {
            throw new \DomainException('Role is already same.');
        }
        $this->role = $role;
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

    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @ORM\PostLoad()
     */
    public function CheckEmbeds()
    {
        if ($this->resetToken->isEmpty()) {
            $this->resetToken = null;
        }
    }
}