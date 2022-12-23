<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Google\GoogleMap\Place\Services\PlaceDetails;

/**
 * Class holding the address components types returned by the Google Places API.
 */
class AddressComponentSectionsType
{
    const STREET_NUMBER = 'street_number';
    const STREET_NAME = 'route';
    const CITY = 'locality';
    const STATE = 'administrative_area_level_1';
    const COUNTRY = 'country';
    const ZIP_CODE = 'postal_code';
    const ADDRESS_TYPES = [
        self::STREET_NUMBER,
        self::STREET_NAME,
        self::CITY,
        self::STATE,
        self::COUNTRY,
        self::ZIP_CODE,
    ];

    public static function isStreetNumber(string $value): bool
    {
        return self::STREET_NUMBER === $value;
    }

    public static function isStreetName(string $value): bool
    {
        return self::STREET_NAME === $value;
    }

    public static function isCity(string $value): bool
    {
        return self::CITY === $value;
    }

    public static function isState(string $value): bool
    {
        return self::STATE === $value;
    }

    public static function isCountry(string $value): bool
    {
        return self::COUNTRY === $value;
    }

    public static function isZipCode(string $value): bool
    {
        return self::ZIP_CODE === $value;
    }
}