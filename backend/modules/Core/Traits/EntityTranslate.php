<?php

namespace Modules\Core\Traits;

use RepoFactory;

trait EntityTranslate
{
    /** @var string $locale */
    protected $locale;

    public function getTranslations()
    {
        return RepoFactory::create(get_class($this))->findEntityTranslations($this);
    }
}
