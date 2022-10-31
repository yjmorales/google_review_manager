<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Core\Exception;

/**
 * Exception generated every time an internal server error occurs.
 */
class ApiErrorException extends AbstractApiException
{
    /**
     * @inheritDoc
     */
    protected function getApiErrorType(): ApiErrorType
    {
        return ApiErrorType::UNEXPECTED_FAILURE();
    }
}