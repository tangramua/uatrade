<?php

namespace Modules\Core\Entities\Doctrine;

use Modules\Core\Traits\DoctrineBaseModel;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;
use Illuminate\Contracts\Support\Arrayable;

class Translation extends AbstractTranslation implements Arrayable
{
    public function toArray()
    {
        return (array) $this;
    }
}