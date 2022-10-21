<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Repository\Business;

/**
 * Holds the criteria elements used to filter a business list.
 */
class BusinessCriteria
{
    private ?string $businessName = null;

    private ?string $businessCreatedDate = null;

    private ?int $businessIndustrySector = null;

    private ?int $businessStatus = null;

    private ?string $businessAddress = null;

    private ?string $businessCity = null;

    private ?string $businessState = null;

    private ?string $businessZipCode = null;

    /**
     * @return string|null
     */
    public function getBusinessName(): ?string
    {
        return $this->businessName;
    }

    /**
     * @param string|null $businessName
     */
    public function setBusinessName(?string $businessName): void
    {
        $this->businessName = $businessName;
    }

    /**
     * @return string|null
     */
    public function getBusinessCreatedDate(): ?string
    {
        return $this->businessCreatedDate;
    }

    /**
     * @param string|null $businessCreatedDate
     */
    public function setBusinessCreatedDate(?string $businessCreatedDate): void
    {
        $this->businessCreatedDate = $businessCreatedDate;
    }

    /**
     * @return int|null
     */
    public function getBusinessIndustrySector(): ?int
    {
        return $this->businessIndustrySector;
    }

    /**
     * @param int|null $businessIndustrySector
     */
    public function setBusinessIndustrySector(?int $businessIndustrySector): void
    {
        $this->businessIndustrySector = $businessIndustrySector;
    }

    /**
     * @return int|null
     */
    public function getBusinessStatus(): ?int
    {
        return $this->businessStatus;
    }

    /**
     * @param int|null $businessStatus
     */
    public function setBusinessStatus(?int $businessStatus): void
    {
        $this->businessStatus = $businessStatus;
    }

    /**
     * @return string|null
     */
    public function getBusinessAddress(): ?string
    {
        return $this->businessAddress;
    }

    /**
     * @param string|null $businessAddress
     */
    public function setBusinessAddress(?string $businessAddress): void
    {
        $this->businessAddress = $businessAddress;
    }

    /**
     * @return string|null
     */
    public function getBusinessCity(): ?string
    {
        return $this->businessCity;
    }

    /**
     * @param string|null $businessCity
     */
    public function setBusinessCity(?string $businessCity): void
    {
        $this->businessCity = $businessCity;
    }

    /**
     * @return string|null
     */
    public function getBusinessState(): ?string
    {
        return $this->businessState;
    }

    /**
     * @param string|null $businessState
     */
    public function setBusinessState(?string $businessState): void
    {
        $this->businessState = $businessState;
    }

    /**
     * @return string|null
     */
    public function getBusinessZipCode(): ?string
    {
        return $this->businessZipCode;
    }

    /**
     * @param string|null $businessZipCode
     */
    public function setBusinessZipCode(?string $businessZipCode): void
    {
        $this->businessZipCode = $businessZipCode;
    }
}