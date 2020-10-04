<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\SignUp;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use App\Model\User\Service\PasswordHasher;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    const HASH = 'hash';
    const TOKEN = 'token';

    public function testSuccess(): void
    {
        $user = User::signUpByEmail(
            $id = Id::next(),
            $date = new \DateTimeImmutable(),
            $email = new Email('test@test.ru'),
            self::HASH,
            self::TOKEN
        );

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());

        self::assertEquals($id, $user->getId());
        self::assertEquals($date, $user->getCreatedAt());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals(self::HASH, $user->getPasswordHash());
        self::assertEquals(self::TOKEN, $user->getConfirmToken());

        self::assertTrue($user->getRole()->isUser());
    }
}