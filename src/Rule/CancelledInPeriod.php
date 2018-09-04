<?php

namespace App\Rule;

use App\Entity\Flight;

/**
 * Class CancelledInPeriod
 *
 * @author Itransition
 */
class CancelledInPeriod implements RuleInterface
{
    /**
     * @var int Days
     */
    private $interval;

    /**
     * CancelledInPeriod constructor.
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
        return $flight->isCancelled() && $flight->getDetail() <= $this->interval;
    }
}
