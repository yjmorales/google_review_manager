<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Core\Exception;

/**
 * Exception generated every time a not found error occurs.
 */
class ApiNotFoundException extends AbstractApiException
{
    /**
     * @inheritDoc
     */
    protected function getApiErrorType(): ApiErrorType
    {
        return ApiErrorType::ENTITY_NOT_FOUND_ERROR();
    }
}