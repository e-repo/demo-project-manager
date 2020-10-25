<?php

declare(strict_types=1);

namespace App\Security;

use App\ReadModel\User\AuthView;
use App\ReadModel\User\UserFetcher;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * @var UserFetcher
     */
    private $users;

    /**
     * UserProvider constructor.
     * @param UserFetcher $users
     */
    public function __construct(UserFetcher $users)
    {
        $this->users = $users;
    }

    /**
     * @inheritDoc
     */
    public function loadUserByUsername($username): UserInterface
    {
        $user = $this->loadUser($username);
        return self::identityByUser($user);
    }

    /**
     * Метод дергается каждый раз при заходе на
     * любую из страниц
     *
     * Параметр получаем из cookie пользователя
     * @param UserInterface $identity
     * @inheritDoc
     */
    public function refreshUser(UserInterface $identity)
    {
        $user = $this->loadUser($identity->getUsername());
        return self::identityByUser($user);
    }

    private function loadUser($userName): AuthView
    {
        if (! $user = $this->users->findForAuth($userName)) {
            throw new UsernameNotFoundException('');
        }
        return $user;
    }

    private static function identityByUser(AuthView $user): UserIdentity
    {
        return new UserIdentity(
            $user->id,
            $user->email,
            $user->password_hash,
            $user->role,
            $user->status
        );
    }

    /**
     * @inheritDoc
     */
    public function supportsClass($class)
    {
        return $class === UserIdentity::class;
    }
}