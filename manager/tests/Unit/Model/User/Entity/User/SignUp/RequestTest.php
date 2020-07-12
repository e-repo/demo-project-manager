<?php

declare(strict_types=1);

namespace App\Test\Model\User\Entity\User\SignUp;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use App\Model\User\Service\PasswordHasher;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = new User(
            $id = Id::next(),
            $createdAt = new \DateTimeImmutable(),
            $email = new Email('test@app.test'),
            $hash = (new PasswordHasher())->hash('hash')
        );

        self::assertEquals($id, $user->getId());
        self::assertEquals($createdAt, $user->getCreatedAt());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($hash, $user->getPasswordHash());
    }
}