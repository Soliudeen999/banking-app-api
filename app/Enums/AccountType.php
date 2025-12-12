<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static SAVINGS()
 * @method static static CURRENT()
 * @method static static CORPORATE()
 */
final class AccountType extends Enum
{
    const SAVINGS = 'savings';
    const CURRENT = 'current';
    const CORPORATE = 'corporate';
}
