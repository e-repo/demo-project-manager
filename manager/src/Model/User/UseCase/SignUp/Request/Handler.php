<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Request;

use App\Model\Flusher;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\PasswordHasher;

class Handler {
    /**
     * @var UserRepository
     */
    private $users;
    /**
     * @var PasswordHasher
     */
    private $hasher;
    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(UserRepository $users, PasswordHasher $hasher, Flusher $flusher)
    {
        $this->users = $users;
        $this->hasher = $hasher;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new \DomainException('User already exist.');
        }

        $user = new User(
            Id::next(),
            new \DateTimeImmutable(),
            new Email($command->email),
            $this->hasher->hash($command->password)
        );

        $this->flusher->flush();
    }
}

//class Handler0
//{
//    private $em;
//
//    public function __construct(EntityManagerInterface $em)
//    {
//        $this->em = $em;
//    }
//
//    public function handle(Command $command): void
//    {
//        $email = mb_strtolower($command->email);
//
//        if ($this->em->getRepository(User::class)->findBy(['email' => $email])) {
//            throw new \DomainException('User already exist.');
//        }
//
//        $user = new User(
//            Uuid::uuid4()->toString(),
//            new \DateTimeImmutable(),
//            $email,
//            password_hash($command->password, PASSWORD_ARGON2I, ['cost' => 12])
//        );
//
//        $this->em->persist($user);
//        $this->em->flush();
//    }
//}