<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Core\Models;

/**
 * Class acting as json response model holding the information to be exported to the json response.
 */
abstract class AbstractApiResponseModel
{
    /**
     * Returns the data in form of an array of the elements to be returned via json response.
     *
     * @return array
     */
    abstract public function toObject(): array;
}
