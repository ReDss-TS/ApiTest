<?php

namespace Api\Entity;

use Doctrine\ORM\Mapping as ORM;
use stdClass;

/**
 * Api\Entity\AbstractEntity
 */
abstract class AbstractEntity implements \JsonSerializable
{
    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }
}