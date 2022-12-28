<?php

namespace App\Security\Role;

use Common\Utils\Enum\TEnum;

/**
 * @method static RoleTypes ROLE_ADMIN
 * @method static RoleTypes ROLE_USER
 */
class RoleTypes
{
    use TEnum;

    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';
    const MAP = [
        self::ROLE_ADMIN,
        self::ROLE_USER,
    ];

    private $id;

    private $label;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return self[]
     */
    public static function getOptions(): array
    {
        $options = [];
        foreach (self::getValues() as $item) {
            $options[$item->getId()] = $item->getLabel();
        }

        return $options;
    }

    /**
     * @inheritDoc
     */
    protected static function _initializeDefinitions(): array
    {
        return [
            'ROLE_ADMIN' => [
                'id'    => self::ROLE_ADMIN,
                'label' => 'Administrator',
            ],
            'ROLE_USER'  => [
                'id'    => self::ROLE_USER,
                'label' => 'User',
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    protected function _populate(array $definition): void
    {
        $this->id    = $definition['id'];
        $this->label = $definition['label'];
    }
}