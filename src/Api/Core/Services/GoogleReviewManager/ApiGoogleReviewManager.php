<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Core\Services\GoogleReviewManager;

/**
 * Class responsible to manage the Google Review link string.
 */
class ApiGoogleReviewManager
{
    /**
     * Base URL used by google to generate a Review.
     *
     * @var string
     */
    private string $_baseUrl;

    /**
     * @param string $baseUrl Base URL used by google to generate a Review.
     */
    public function __construct(string $baseUrl)
    {
        $this->_baseUrl = $baseUrl;
    }

    /**
     * Use this function to generate a Google Review link string.
     *
     * @param string $placeId
     *
     * @return string
     */
    public function generateLink(string $placeId): string
    {
        return "{$this->_baseUrl}{$placeId}";
    }
}