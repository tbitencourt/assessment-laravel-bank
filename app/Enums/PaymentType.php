<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentType: string
{
    case CreditCard = 'C';
    case DebitCard = 'D';
    case Pix = 'P';

    public function fee(): float
    {
        return match ($this) {
            self::CreditCard => 0.05, // 5%
            self::DebitCard => 0.03, // 3%
            self::Pix => 0.00, // free
        };
    }
}
