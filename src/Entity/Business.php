<?php

namespace App\Entity;

class Business
{
    private int $id;

    private string$name;

    private string$location;

    private bool $active;

    /**
     * @param int    $id
     * @param string $name
     * @param string $location
     * @param bool   $active
     */
    public function __construct(int $id, string $name, string $location, bool $active)
    {
        $this->id       = $id;
        $this->name     = $name;
        $this->location = $location;
        $this->active   = $active;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }
}