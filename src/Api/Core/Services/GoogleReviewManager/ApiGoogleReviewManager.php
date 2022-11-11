<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Core\Services\GoogleReviewManager;

use App\Entity\Business;

/**
 * Class responsible to manage the Google Review link string.
 */
class ApiGoogleReviewManager
{
    /**
     * Use this function to generate a Google Review link string.
     *
     * @param Business $business
     *
     * @return string
     */
    public function generateLink(Business $business): string
    {
        return 'link';
    }

}