<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Reset\Reset;

class Command
{
    /**
     * @var string
     */
    public $password;
    /**
     * @var string
     */
    public $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
}