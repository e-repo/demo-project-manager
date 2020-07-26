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
            ->confirmed()
            ->build();

        $user->requestPasswordReset($token, $now);

        $this->assertNotNull($user->getRequestToken());
    }

    public function testAlready(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user = $user = (new UserBuilder())
            ->viaEmail()
            ->confirmed()
            ->build();

        $user->requestPasswordReset($token, $now);
        $this->expectExceptionMessage('Resetting is already request.');
        $user->requestPasswordReset($token, $now);
    }

    public function testExpired(): void
    {
        $now = new \DateTimeImmutable();
        $user = $user = (new UserBuilder())
            ->viaEmail()
            ->confirmed()
            ->build();

        $token1 = new ResetToken('token', $now->modify('+1 day'));
        $token2 = new ResetToken('token', $now->modify('+3 day'));

        $user->requestPasswordReset($token1, $now);
        $this->assertEquals($token1, $user->getRequestToken());

        $user->requestPasswordReset($token2, $now->modify('+2 day'));
        $this->assertEquals($token2, $user->getRequestToken());
    }

    public function testNotConfirmed(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));
        $user = (new UserBuilder())
            ->viaEmail()
            ->build();

        $this->expectExceptionMessage('User is not active.');
        $user->requestPasswordReset($token, $now);
    }

    public function testWithoutEmail(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));
        $user = (new UserBuilder())
            ->viaNetwork()
            ->build();

        $this->expectExceptionMessage('Email is not specified.');
        $user->requestPasswordReset($token, $now);
    }
}