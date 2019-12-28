<?php

namespace Modules\CMS\Repositories\Doctrine;

use Modules\Core\Repositories\Doctrine\EntityRepository as BaseEntityRepository;

class FavoritesRepository extends BaseEntityRepository
{
    const OBJECT_ALIASES_MAPPER = [
        'company' => 'Modules\Company\Entities\Doctrine\Company',
        'project' => 'Modules\Company\Entities\Doctrine\Project',
        'event' => 'Modules\Event\Entities\Doctrine\Event'
    ];
}
