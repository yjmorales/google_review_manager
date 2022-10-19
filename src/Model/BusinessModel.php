<?php

namespace App\Entity;

class BusinessModel
{
    public function generate()
    {
        $list = [];
        for ($i = 0; $i < 10; $i++) {
            $list[] = new Business($i, "Name $i", "Locatiion $i", true);
        }

        return $list;
    }

}