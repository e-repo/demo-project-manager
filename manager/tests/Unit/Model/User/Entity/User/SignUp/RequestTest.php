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

        $this->assertTrue($user->isWait());
        $this->assertFalse($user->isActive());

        $this->assertEquals($id, $user->getId());
        $this->assertEquals($date, $user->getCreatedAt());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals(self::HASH, $user->getPasswordHash());
        $this->assertEquals(self::TOKEN, $user->getConfirmToken());

        self::assertTrue($user->getRole()->isUser());
    }
}