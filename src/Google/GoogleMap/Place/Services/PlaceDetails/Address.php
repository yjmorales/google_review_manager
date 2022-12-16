<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Google\GoogleMap\Place\Services\PlaceDetails;

use JsonSerializable;

/**
 * Class responsible to represents the address components.
 */
class Address implements JsonSerializable
{
    private ?string $streetNumber = null;

    private ?string $streetName = null;

    private ?string $city = null;

    private ?string $state = null;

    private ?string $country = null;

    private ?string $zipCode = null;

    private ?int $placeId = null;

    /**
     * @param string $streetNumber
     */
    public function setStreetNumber(string $streetNumber): void
    {
        $this->streetNumber = $streetNumber;
    }

    /**
     * @param string $streetName
     */
    public function setStreetName(string $streetName): void
    {
        $this->streetName = $streetName;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @param string $state
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * @param string $zipCode
     */
    public function setZipCode(string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @param int $placeId
     */
    public function setPlaceId(int $placeId): void
    {
        $this->placeId = $placeId;
    }

    /**
     * @return string|null
     */
    public function getStreetNumber(): ?string
    {
        return $this->streetNumber;
    }

    /**
     * @return string|null
     */
    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @return string|null
     */
    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    /**
     * @return int|null
     */
    public function getPlaceId(): ?int
    {
        return $this->placeId;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $address = $this->streetNumber ?? '';
        if ($this->streetName) {
            $address .= " $this->streetName";
        }

        return [
            'address' => $address,
            'city'    => $this->city,
            'state'   => $this->state,
            'country' => $this->country,
            'zipCode' => $this->zipCode,
            'placeId' => $this->placeId,
        ];
    }
}