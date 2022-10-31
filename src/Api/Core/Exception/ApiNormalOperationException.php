<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Core\Exception;

/**
 * Exception generated once a payload validation failed.
 */
class ApiNormalOperationException extends AbstractApiException
{
    /**
     * @inheritDoc
     */
    protected function getApiErrorType(): ApiErrorType
    {
        return ApiErrorType::PAYLOAD_VALIDATION_ERROR();
    }
}