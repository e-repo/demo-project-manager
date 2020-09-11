<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Reset\Reset;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="6")
     * @var string
     */
    public $password;
    /**
     * @Assert\NotBlank()
     * @var string
     */
    public $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
}