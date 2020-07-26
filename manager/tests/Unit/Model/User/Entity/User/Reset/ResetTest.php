<?php

declare(strict_types=1);

namespace App\Test\Unit\Model\User\Entity\User\Reset;

use App\Model\User\Entity\User\ResetToken;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class ResetTest extends TestCase
{
    public function testSuccess(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));
        $user  = $user = (new UserBuilder())
            ->viaEmail()
            ->confirmed()
            ->build();

        $user->requestPasswordReset($token, $now);
        self::assertNotNull($user->getRequestToken());

        $user->passwordReset($now, $hash = 'hash');
        self::assertNull($user->getRequestToken());
        self::assertEquals($hash, $user->getPasswordHash());
    }

    public function testExpiredToken(): void
    {
        $user = $user = (new UserBuilder())
            ->viaEmail()
            ->confirmed()
            ->build();
        $now = new \DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user->requestPasswordReset($token, $now);

        self::expectExceptionMessage('Reset token is expired.');
        $user->passwordReset($now->modify('+2 day'), 'hash');
    }

    public function testNotRequested(): void
    {
        $user = $user = (new UserBuilder())
            ->viaEmail()
            ->confirmed()
            ->build();
        $now = new \DateTimeImmutable();

        self::expectExceptionMessage('Resisting is not requested.');
        $user->passwordReset($now, 'hash');
    }
}