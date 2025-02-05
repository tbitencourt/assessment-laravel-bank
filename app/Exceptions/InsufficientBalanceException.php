<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

final class InsufficientBalanceException extends Exception
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            $message !== '' && $message !== '0' ? $message : __('exceptions.insufficient_funds'),
            $code,
            $previous
        );
    }
}
