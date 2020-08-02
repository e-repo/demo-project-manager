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
    public function testSuccess(): void
    {
        $user = User::signUpByEmail(
            Id::next(),
            new \DateTimeImmutable(),
            $email = new Email('test@test.ru'),
            $hash = 'hash',
            $token = 'token'
        );

        $this->assertTrue($user->isWait());
        $this->assertFalse($user->isActive());

        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($hash, $user->getPasswordHash());
        $this->assertEquals($token, $user->getConfirmToken());
        $this->expectExceptionMessage('User is already signed up.');
        User::signUpByEmail(
            Id::next(),
            new \DateTimeImmutable(),
            $email = new Email('test@test.ru'),
            $hash = 'hash',
            $token = 'token'
        );
    }
}