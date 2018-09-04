<?php

namespace App\Rule;

use App\Entity\Flight;

/**
 * Class DelayedForPeriod
 *
 * @author Itransition
 */
class DelayedForPeriod implements RuleInterface
{
    /**
     * @var int Hours
     */
    private $interval;

    /**
     * DelayedForPeriod constructor.
     *
     * @param int $interval
     */
    public function __construct(int $interval)
    {
        $this->interval = $interval;
    }

    /**
     * @inheritdoc
     */
    public function check(Flight $flight): bool
    {
        return $flight->isDelayed() && $flight->getDetail() >= $this->interval;
    }
}
