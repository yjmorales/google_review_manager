<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Model;

use Common\Utils\Enum\TEnum;

/**
 * Class acting as enum of active status.
 *
 * @method static ActiveEnum ALL
 * @method static ActiveEnum ACTIVE
 * @method static ActiveEnum INACTIVE
 */
class ActiveEnum
{
    use TEnum;

    private int $_id;

    private string $_label;

    public static function map()
    {
        $result = [];
        /** @var self $type */
        foreach (self::getValues() as $type) {
            $result[$type->getId()] = $type->getLabel();
        }

        return $result;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->_id;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->_label;
    }

    protected function _populate(array $definition): void
    {
        $this->_id    = $definition['id'];
        $this->_label = $definition['label'];
    }

    protected static function _initializeDefinitions(): array
    {
        return [
            'ALL'      => [
                'id'    => 1,
                'label' => 'All',
            ],
            'ACTIVE'   => [
                'id'    => 2,
                'label' => 'Active',
            ],
            'INACTIVE' => [
                'id'    => 3,
                'label' => 'Inactive',
            ],
        ];
    }
}