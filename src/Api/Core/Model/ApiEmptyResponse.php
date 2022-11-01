<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Core\Models;

/**
 * This class represents and empty response to be returned to the api callers.
 */
class ApiEmptyResponse extends AbstractApiResponseModel
{
    /**
     * @inheritDoc
     */
    public function toObject(): array
    {
        return [];
    }
}