<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Core\Controller;

use App\Api\Core\Exception\ApiErrorException;
use Common\Security\AntiSpam\ReCaptcha\v3\ReCaptchaV3Validator;
use Exception;

/**
 * Trait to be used by those API Controllers.
 */
trait TApiController
{
    /**
     * Used to verify that a human has submitted a form, not a robot. If the robot submits the form then
     * the method thrown an exception.
     *
     * @param ReCaptchaV3Validator $reCaptchaV3Validator
     *
     * @return void
     * @throws ApiErrorException
     */
    protected function _validateReCaptchaV3(ReCaptchaV3Validator $reCaptchaV3Validator): void
    {
        /*
        *  Verifies that's not a robot.
        */
        try {
            $isHuman = $reCaptchaV3Validator->validateToken();
        } catch (Exception $e) {
            throw new ApiErrorException(['Unable to validate google recaptcha v3 token.']);
        }
        if (!$isHuman) {
            throw new ApiErrorException(['Invalid token. You are a robot']);
        }
    }
}