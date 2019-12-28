<?php
namespace Modules\Core\Traits;


use DateTime;
use Doctrine\Common\Persistence\Proxy;
use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Common\Collections\ArrayCollection;
use EntityManager;
use RepoFactory;
use Module;
use Modules\CMS\Entities\Doctrine\Favorites;

trait DoctrineBaseModel
{

    /**
     * DoctrineBaseModel constructor.
     * @param $input
     */
    public function __construct($input)
    {

    }

    public function getHiddenFields()
    {
        return [];
    }

    public function getExportableRelations()
    {
        return [];
    }

    /**
     * Update changed entity
     * @param $user
     */
    protected function updateEntity($entity)
    {
        $repo = RepoFactory::create(get_class($entity));
        $repo->updateModel($entity);
    }

    /**
     * prepare UpdatedAt field
     */
    public function preUpdate()
    {
        $this->setDateUpdated(new DateTime());
    }

    /**
     * Magic method make all getters and setters
     * @param $method
     * @param $value
     * @return null
     */
    public function __call($method, $value)
    {
        if (!method_exists($this, $method)) {
            if (substr($method, 0, 3) == 'set') {
                $property = Inflector::camelize(substr($method, 3));
                if (property_exists($this, $property)) {
                    if (isset($value[0])) {
                        $this->$property = $value[0];
                    } else {
                        $this->$property = null;
                    }
                }
            } else if (substr($method, 0, 3) == 'get') {
                $property = Inflector::camelize(substr($method, 3));
                if (property_exists($this, $property)) {
                    return $this->$property;
                }
                return null;
            }
        }
    }

    /**
     * Set data to model
     * @param array $data
     */
    public function setData($data = [])
    {
        if ($data == []) return;
        foreach ((array)$data as $key => $value) {
            $key = Inflector::camelize($key);
            if (property_exists($this, $key)) {
                $propertyName = 'set' . ucfirst($key);
                $this->$propertyName($value);
            }
        }
    }

    public function getData()
    {
        $result = [];
        $keys = array_keys(get_object_vars($this));
        foreach($keys as $key) {
            $result[$key] = $this->{'get' . ucfirst($key)}();
        }

        return $result;
    }

    function isEntity($class)
    {
        if (is_object($class)) {
            $class = ($class instanceof Proxy)
                ? get_parent_class($class)
                : get_class($class);
        }

        return !EntityManager::getMetadataFactory()->isTransient($class);
    }

    function isCollection($object)
    {
        if (is_object($object)) {
            return ($object instanceof PersistentCollection);
        }
        return false;
    }

    /**
     * @param $field
     * @return bool
     */
    public function isJson($field)
    {
        $metadata = EntityManager::getClassMetadata(get_class($this));
        return (isset($metadata->fieldMappings[$field]) && $metadata->fieldMappings[$field]['type'] === 'json');
    }

    /**
     * Convert Model to array
     * @param array $data
     * @return array
     */
    public function toArray($data = [])
    {
        if ($data === []) $data = $this->getData();
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                if ((is_numeric($key) || !in_array($key, $this->getHiddenFields())) && $value !== [] && strpos($key, '__') !== 0) {
                    $property = Inflector::tableize($key);
                    if(is_object($value) && $this->isEntity($value)) {
                        $result[$property] = (!is_numeric($key) && in_array($key, $this->getExportableRelations())) ? $value->toArray() : $value->getId();
                    } else {
                        if(is_array($value) && $this->isJson($key)) {
                            $result[$property] = $value;
                        } else {
                            if($this->isCollection($value) && (!is_numeric($key) && in_array($key, $this->getExportableRelations()))) {
                                foreach($value as $item) {
                                    $result[$property][] = $item->toArray();
                                }
                            } elseif($value instanceof \DateTime) {
                                $result[$property] = $value->format('c');
                            } else {
                                $result[$property] = $this->toArray($value);
                            }
                        }
                    }
                }
            }
            return $result;
        }
        return $data;
    }

    /**
     * @param string $entityName
     * @param array $data
     * @return object
     */
    public function getModelBy($entityName, array $data)
    {
        $repository = RepoFactory::create($entityName);

        $model = $repository->findOneBy($data);
        if (!$model) {
            $model = $repository->prepareData($data);
        }

        return $model;
    }

    /**
     * @return object
     */
    public function getModule() :object
    {
        $className = get_class($this);
        $classNameParts = explode('\\', $className);
        $moduleName = $classNameParts[1];

        return Module::find($moduleName);
    }

    /**
     * @return bool
     */
    public function getInFavorites(): bool
    {
        $favoritesRepo = RepoFactory::create(Favorites::class);

        $user = auth()->user();
        $objectAlias = array_search(__CLASS__, $favoritesRepo::OBJECT_ALIASES_MAPPER);
        $foreignKey = $this->getId();

        if (!$user || !$objectAlias) {
            return false;
        };

        $recordInFavorites = $favoritesRepo->findOneBy([
            'user' => $user,
            'objectAlias' => $objectAlias,
            'foreignKey' => $foreignKey,
        ]);

        return (bool)$recordInFavorites;
    }
}
