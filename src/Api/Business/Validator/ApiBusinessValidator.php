<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Business\Controller;

use ArrayObject;
use Common\DataManagement\Validator\DataValidator;

/**
 * Validator used to validate data respective to business.
 */
class ApiBusinessValidator
{
    /**
     * @var DataValidator
     */
    private DataValidator $_validator;

    public function __construct()
    {
        $this->_validator = new DataValidator();
    }

    /**
     * Validates the information provided to send a review by email.
     *
     * @param mixed       $reviews Holds the review ids. It will be validated.
     * @param ArrayObject $error   Will hold the errors.
     *
     * @return bool
     */
    public function validateEmailSending(mixed $reviews, ArrayObject $error): bool
    {
        if (!$reviews) {
            $error->append('No review has been provided to be sent via email.');

            return false;
        }
        if (!is_array($reviews)) {
            $error->append('The review ids are invalid.');

            return false;
        }
        if (!count($reviews)) {
            $error->append('You should provide the review to be sent.');

            return false;
        }
        $isValid = true;
        foreach ($reviews as $reviewId) {
            $isValid &= $validId = $this->_validator->isValidInt($reviewId);
            if (!$validId) {
                $error->append('There is a review id that is not valid.');
            }
        }

        return $isValid;
    }
}