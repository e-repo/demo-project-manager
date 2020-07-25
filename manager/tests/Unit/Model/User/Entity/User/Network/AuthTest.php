<?php

declare(strict_types=1);

namespace App\Test\Unit\Model\User\Entity\User\Network;

use App\Model\User\Entity\User\Network;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = $user = (new UserBuilder())->build();
        $user->signUpByNetwork(
            $network = 'vk',
            $identity = '0000001'
        );

        self::assertCount(1, $networks = $user->getNetworks());
        self::assertInstanceOf(Network::class, $first = reset($networks));
        self::assertEquals($network, $first->getNetwork());
        self::assertEquals($identity, $first->getIdentity());
    }

    public function testAlready(): void
    {
        $user = $user = (new UserBuilder())->build();
        $user->signUpByNetwork(
            $network = 'vk',
            $identity = '0000001'
        );

        self::expectExceptionMessage('User is already signed up.');
        $user->signUpByNetwork($network, $identity);
    }
}