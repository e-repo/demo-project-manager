<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class Id
{
    /**
     * @var string
     */
    private $value;

    /**
     * Id constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        $this->value = $value;
    }
    /**
     * @return $this
     */
    public static function next(): self
    {
        return new self(Uuid::uuid4()->toString());
    }
    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}