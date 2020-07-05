<?php

declare(strict_types=1);

namespace App\Test\Model\User\Entity\User\SignUp;

use App\Model\User\Entity\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Nonstandard\Uuid;

class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = new User(
            $id = Uuid::uuid4()->toString(),
            $createdAt = new \DateTimeImmutable(),
            $email = 'test@app.test',
            $hash = 'hash'
        );

        self::assertEquals($id, $user->getId());
        self::assertEquals($createdAt, $user->getCreatedAt());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($hash, $user->getPasswordHash());
    }
}