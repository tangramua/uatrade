<?php
namespace Modules\Core\Traits;

use Modules\GeoNames\Entities\Doctrine\Geonames\Translations;
use RepoFactory;

trait GeoNamesTranslate
{
    public function translateGeoname($locale)
    {
        return RepoFactory::create(Translations::class)->findBy(['geonames_id'=>$this->geonames->getId(), 'locale'=>$locale]);
    }
}