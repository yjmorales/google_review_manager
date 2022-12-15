<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Business\Model;

use App\Core\Models\AbstractApiResponseModel;
use App\Entity\Business;
use stdClass;

/**
 * Model responsible to hold the data to return once a business is removed.
 */
class BusinessRemoveModel extends AbstractApiResponseModel
{
    /**
     * Removed Entity
     *
     * @var Business
     */
    private Business $_business;

    /**
     * @param Business $_business
     */
    public function __construct(Business $_business)
    {
        $this->_business = $_business;
    }

    /**
     * @inheritDoc
     */
    public function toObject(): stdClass
    {
        $business          = new stdClass();
        $business->id      = $this->_business->getId();
        $business->name    = $this->_business->getName();
        $business->active  = $this->_business->isActive();
        $business->address = $this->_business->getAddress();
        $business->city    = $this->_business->getCity();
        $business->state   = $this->_business->getState();
        $business->zipCode = $this->_business->getZipCode();
        $result            = new stdClass();
        $result->business  = $business;

        return $result;
    }
}