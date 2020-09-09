<?php

declare(strict_types=1);

namespace App\Model\User\Service;

class ResetTokenizerFactory
{
    /**
     * @param string $duration
     * @return ResetTokenizer
     * @throws \Exception
     */
    public function create(string $duration): ResetTokenizer
    {
        return new ResetTokenizer(new \DateInterval($duration));
    }
}