<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\Reset;

use App\Model\User\Entity\User\ResetToken;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user = $user = (new UserBuilder())
            ->viaEmail()
            ->build();

        $user->requestPasswordReset($token, $now);

        self::assertNotNull($user->getRequestToken());
    }

    public function testAlready(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user = $user = (new UserBuilder())
            ->viaEmail()
            ->build();

        $user->requestPasswordReset($token, $now);
        self::expectExceptionMessage('Resetting is already request.');
        $user->requestPasswordReset($token, $now);
    }

    public function testExpired(): void
    {
        $now = new \DateTimeImmutable();
        $user = $user = (new UserBuilder())
            ->viaEmail()
            ->build();

        $token1 = new ResetToken('token', $now->modify('+1 day'));
        $token2 = new ResetToken('token', $now->modify('+3 day'));

        $user->requestPasswordReset($token1, $now);
        self::assertEquals($token1, $user->getRequestToken());

        $user->requestPasswordReset($token2, $now->modify('+2 day'));
        self::assertEquals($token2, $user->getRequestToken());
    }
}