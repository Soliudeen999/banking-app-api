<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static NGN()
 * @method static static USD()
 * @method static static EUR()
 */
final class AccountCurrency extends Enum
{
    const NGN = 'NGN';

    const USD = 'USD';

    const EUR = 'EUR';
}
