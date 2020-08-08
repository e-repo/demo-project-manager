<?php

declare(strict_types=1);

namespace App\Test\Unit\Model\User\Entity\User\Network;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Network;
use App\Model\User\Entity\User\User;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    const NETWORK_NAME = 'vk';
    const NETWORK_IDENTITY = '0000001';

    public function testSuccess(): void
    {
        $user = User::signUpByNetwork(
            $id = Id::next(),
            $date = new \DateTimeImmutable(),
            self::NETWORK_NAME,
            self::NETWORK_IDENTITY
        );

        self::assertTrue($user->isActive());

        self::assertEquals($id, $user->getId());
        self::assertEquals($date, $user->getCreatedAt());

        $this->assertCount(1, $networks = $user->getNetworks());
        $this->assertInstanceOf(Network::class, $first = reset($networks));
        $this->assertEquals(self::NETWORK_NAME, $first->getNetwork());
        $this->assertEquals(self::NETWORK_IDENTITY, $first->getIdentity());
    }
}