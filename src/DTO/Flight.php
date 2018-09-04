<?php

namespace App\DTO;

/**
 * Class Flight
 *
 * @author Itransition
 */
class Flight
{
    /**
     * @var string
     */
    public $countryCode;

    /**
     * @var string
     */
    public $status;

    /**
     * @var int
     */
    public $detail;

    /**
     * Flight constructor.
     *
     * @param string|null $countryCode
     * @param string|null $status
     * @param int|null $detail
     */
    public function __construct(?string $countryCode, ?string $status, ?int $detail)
    {
        $this->countryCode = $countryCode;
        $this->status = $status;
        $this->detail = $detail;
    }
}
