<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Business\Model;

use App\Core\Models\AbstractApiResponseModel;
use App\Entity\Business;
use stdClass;

/**
 * Class holding the business list information to be returned via json response
 */
class BusinessListModel extends AbstractApiResponseModel
{
    /**
     * Holds the list of business.
     *
     * @var Business[]
     */
    private array $_list;

    /**
     * @param Business[] $list
     */
    public function __construct(array $list)
    {
        $this->_list = $list;
    }

    /**
     * @inheritDoc
     */
    public function toObject(): array
    {
        $data = [];

        foreach ($this->_list as $item) {
            $business             = new stdClass();
            $business->id         = $item->getId();
            $business->name       = $item->getName();
            $business->active     = $item->isActive();
            $business->address    = $item->getAddress();
            $business->city       = $item->getCity();
            $business->state      = $item->getState();
            $business->zipCode    = $item->getZipCode();
            $data[$item->getId()] = $business;
        }

        return ['business' => $data];
    }
}