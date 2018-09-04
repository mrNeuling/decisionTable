<?php

namespace App\Rule;

use App\Entity\Flight;

/**
 * Class DepartedFromEU
 *
 * @author Itransition
 */
class DepartedFromEU implements RuleInterface
{
    private const EUCountryCodes = ['BE', 'BG', 'CZ', 'DK', 'DE', 'EE', 'IE', 'EL', 'ES', 'FR', 'HR', 'IT', 'CY', 'LV', 'LT', 'LU', 'HU', 'MT', 'NL', 'AT', 'PL', 'PT', 'RO', 'SI', 'SK', 'FI', 'SE', 'UK'];

    /**
     * @inheritdoc
     */
    public function check(Flight $flight): bool
    {
        return \in_array($flight->getCountryCode(), self::EUCountryCodes, true);
    }
}
