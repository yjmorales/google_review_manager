<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Core\Model;

use stdClass;

/**
 * This class represents and empty response to be returned to the api callers.
 */
class ApiEmptyResponse extends AbstractApiResponseModel
{
    /**
     * @inheritDoc
     */
    public function toObject(): stdClass
    {
        return new stdClass();
    }
}