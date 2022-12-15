<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Google\GoogleMap\Place\Core\GoogleMapPlaceApiServices;

/**
 * This abstraction holds common properties for all responses representing the data returned by the Google Places API.
 */
abstract class AbstractPlaceResponse implements IPlaceResponse
{
    /**
     * Indicates whether the response is success or not.
     *
     * @var bool
     */
    protected bool $success;

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * Indicates whether the response is success or not.
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }
}