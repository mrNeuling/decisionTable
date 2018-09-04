<?php

namespace App\Entity;

/**
 * Class Flight
 *
 * @author Itransition
 */
class Flight
{
    private const STATUS_CANCELLED = 'Cancel';
    private const STATUS_DELAYED = 'Delay';

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @var string
     */
    private $status;

    /**
     * @var int
     */
    private $detail;

    /**
     * Flight constructor.
     *
     * @param string $countryCode
     * @param string $status
     * @param int $detail
     */
    public function __construct(string $countryCode, string $status, int $detail)
    {
        $this->countryCode = \strtoupper($countryCode);
        $this->status = \ucfirst(\strtolower($status));
        $this->detail = $detail;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getDetail(): int
    {
        return $this->detail;
    }

    public function isCancelled(): bool
    {
        return self::STATUS_CANCELLED === $this->status;
    }

    public function isDelayed(): bool
    {
        return self::STATUS_DELAYED === $this->status;
    }

    /**
     * @param \App\DTO\Flight $flight
     *
     * @return Flight
     */
    public static function createFromDTO(\App\DTO\Flight $flight): self
    {
        return new self($flight->countryCode, $flight->status, $flight->detail);
    }
}
