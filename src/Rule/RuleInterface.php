<?php


namespace App\Rule;

use App\Entity\Flight;

interface RuleInterface
{
    public function check(Flight $flight): bool;
}