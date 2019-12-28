<?php

namespace Modules\Core\Repositories\Doctrine\Helper;

use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Inflector\Inflector;
use App\Exceptions\DoctrineException;
use Illuminate\Validation\ValidationException;
use EntityManager;

class QueryMapper
{
    const AVAILABLE_ORDERBY_VALUES = ['ASC', 'DESC'];
    const OPERATORS_COLLECTION = [
        '=' => '=',
        '<>' => '<>',
        '<' => '<',
        '<=' => '<=',
        '>' => '>',
        '>=' => '>=',
        'like' => 'LIKE',
        'not_like' => 'NOT LIKE',
        'is_null' => 'IS NULL',
        'is_not_null' => 'IS NOT NULL'
    ];

    public $queryBuilder;
    protected $entityClassName;
    protected $joins = [];
    protected $where = [];
    protected $rules = [];
    protected $parameters = [];
    protected $orderBy = [];

    /**
     * QueryMapper constructor.
     * @param string $entityClassName
     */
    public function __construct(string $entityClassName)
    {
        $this->entityClassName = $entityClassName;
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @return mixed
     * @throws DoctrineException
     * @throws ValidationException
     */
    public function buildQuery(array $criteria = [], array $orderBy = null, int $limit = null)
    {
        $this->initQueryBuilder();

        $this->setCriteria($criteria);
        $this->setOrderBy($orderBy);

        $this->compileQuery($limit);

        return $this->queryBuilder->getQuery();
    }

    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    /**
     * @param string|null $entityClassName
     * @return string
     */
    public function getTableAlias(string $entityClassName = null): string
    {
        $entityClassName = $entityClassName ?: $this->entityClassName;

        $entityMetadata = EntityManager::getClassMetadata($entityClassName);
        return $entityMetadata->getTableName();
    }

    protected function initQueryBuilder()
    {
        $tableAlias = $this->getTableAlias($this->entityClassName);

        $this->queryBuilder = EntityManager::createQueryBuilder()
            ->select($tableAlias)
            ->from($this->entityClassName, $tableAlias);
    }

    /**
     * @param int|null $limit
     */
    protected function compileQuery(int $limit = null)
    {
        foreach ($this->joins as $alias => $join) {
            $this->queryBuilder->leftJoin($join, $alias);
        }

        foreach ($this->where as $whereExpression) {
            $this->queryBuilder->andWhere($whereExpression);
        }

        $this->queryBuilder->setParameters($this->parameters);

        foreach ($this->orderBy as $sort => $order) {
            $this->queryBuilder->addOrderBy($sort, $order);
        }

        if($limit) {
            $this->queryBuilder->setMaxResults($limit);
        }

        //Remove duplicates caused by one to many / many to many relations
        $this->queryBuilder->groupBy($this->getTableAlias($this->entityClassName).'.id');
    }

    /**
     * @param array $criteria
     * @throws DoctrineException
     * @throws ValidationException
     */
    protected function setCriteria(array $criteria)
    {
        if (empty($criteria)) return;

        foreach ($criteria as $key => $value) {
            $value = $this->handleCriteriaValue($key, $value);

            if ($this->hasRelations($key)) {
                $fieldRelations = $this->getRelationsList($key);

                $field = Inflector::camelize(array_pop($fieldRelations));
                $fieldEntityClassName = $this->setJoinRelations($fieldRelations);
            } else {
                $field = Inflector::camelize($key);
                $fieldEntityClassName = $this->entityClassName;
            }

            $variableName = $this->getVariableNameByKey($key);
            $tableAlias = $this->getTableAlias($fieldEntityClassName);

            $this->setRule($variableName, $field, $fieldEntityClassName);
            $this->setWhere($tableAlias, $field, $this->rules[$variableName], $variableName);

            $this->setParameters($variableName, $value);
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed|null
     * @throws ValidationException
     */
    protected function handleCriteriaValue(string $key, $value)
    {
        $this->validateCriteriaValue($key, $value);
        $variableName = $this->getVariableNameByKey($key);

        if (is_array($value)) {
            if (isset($value['operator'])) {
                $operator = strtolower($value['operator']);
                $value = isset($value['value']) ? $value['value'] : null;

                $this->rules[$variableName] = self::OPERATORS_COLLECTION[$operator];
            } else {
                $this->rules[$variableName] = Comparison::IN;
            }
        }

        return $value;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @throws ValidationException
     */
    protected function validateCriteriaValue(string $key, $value)
    {
        $validationErrorMessages = [];

        if (is_array($value) && isset($value['operator'])) {

            $operator = is_string($value['operator']) ? strtolower($value['operator']) : null;

            if(!$operator) {
                $validationErrorMessages["filter[$key]['operator']"] = "'operator' must be a string. Available values for 'operator': " . implode(', ', array_keys(self::OPERATORS_COLLECTION));
            } elseif(!array_key_exists($operator, self::OPERATORS_COLLECTION)) {
                $validationErrorMessages["filter[$key]['operator']"] = "'operator' has not valid value '{$value['operator']}'. You can use only: " . implode(', ', array_keys(self::OPERATORS_COLLECTION));
            }

            if(($operator === 'is_null' || $operator === 'is_not_null')) {
                if(isset($value['value']) && !is_null($value['value'])) {
                    $validationErrorMessages["filter[$key]['value']"] = "Value for operator '{$value['operator']}' must be null";
                }
            } else {
                if(!isset($value['value'])) {
                    $validationErrorMessages["filter[$key]['value']"] = "'value' for '$key'' not found";
                } elseif (!is_scalar($value['value'])) {
                    $validationErrorMessages["filter[$key]['value']"] = "Incorrect type of 'value'. Value can be only integer, float, string or boolean.";
                }
            }

            if($excessKeys = array_diff(array_keys($value), ['operator', 'value'])) {
                $validationErrorMessages["filter[$key]"] = "There are excess keys: " . implode(', ', $excessKeys);
            }

        } elseif (!is_array($value) && !is_scalar($value)) {
            $validationErrorMessages["filter[$key]"] = "Incorrect type of Value. Value can be only array, integer, float, string or boolean";
        } elseif (is_array($value)) {
            foreach ($value as $itemKey => $itemValue) {
                if(!is_scalar($itemValue)) {
                    $validationErrorMessages["filter[$key]['value'][$itemKey]"] = "Incorrect type of Value. Value can be only integer, float, string or boolean";
                }
            }
        }

        if(!empty($validationErrorMessages)) {
            $validationError = ValidationException::withMessages($validationErrorMessages);
            throw $validationError;
        }
    }

    /**
     * @param array|null $orderBy
     * @throws DoctrineException
     * @throws ValidationException
     */
    protected function setOrderBy(array $orderBy = null)
    {
        if (!is_array($orderBy) || empty($orderBy)) return;

        foreach ($orderBy as $key => $order) {

            if ($this->hasRelations($key)) {
                $fieldRelations = $this->getRelationsList($key);

                $field = Inflector::camelize(array_pop($fieldRelations));
                $fieldEntityClassName = $this->setJoinRelations($fieldRelations);
            } else {
                $field = Inflector::camelize($key);
                $fieldEntityClassName = $this->entityClassName;
            }

            $order = strtoupper($order);
            $this->validateOrderByValue($key, $order);

            $this->orderBy[sprintf('%s.%s', $this->getTableAlias($fieldEntityClassName), $field)] = $order;
        }
    }

    /**
     * @param string $key
     * @param string $order
     * @throws ValidationException
     */
    protected function validateOrderByValue(string $key, string $order)
    {
        if(!in_array($order, self::AVAILABLE_ORDERBY_VALUES)) {
            $validationError = ValidationException::withMessages([
                'sort_value' => "Value for sort by '$key' isn't valid. Available values: " . implode(', ', self::AVAILABLE_ORDERBY_VALUES)
            ]);
            throw $validationError;
        }
    }

    /**
     * @param array $fieldRelations
     * @param string|null $parentEntityClassName
     * @return string
     * @throws DoctrineException
     */
    protected function setJoinRelations(array $fieldRelations, string $parentEntityClassName = null)
    {
        $parentEntityClassName = $parentEntityClassName ?: $this->entityClassName;

        if (!$fieldRelations) return $parentEntityClassName;

        $relation = array_shift($fieldRelations);
        $parentAssociationMappings = $this->getAssociationMappings($parentEntityClassName);

        if (array_key_exists($relation, $parentAssociationMappings)) {
            $entityClassName = $parentAssociationMappings[$relation]['targetEntity'];
            $this->joins[$this->getTableAlias($entityClassName)] = $this->getTableAlias($parentEntityClassName) . '.' . $relation;

            return $this->setJoinRelations($fieldRelations, $entityClassName);
        } else {
            throw new DoctrineException("$parentEntityClassName hasn't relations with $relation");
        }

    }

    /**
     * @param string $variableName
     * @param string $field
     * @param string|null $entityClassName
     */
    protected function setRule(string $variableName, string $field, string $entityClassName = null)
    {
        $entityClassName = $entityClassName ?: $this->entityClassName;

        $this->rules[$variableName] = isset($this->rules[$variableName]) ? $this->rules[$variableName] : $this->getEntityRule($entityClassName, $field);
    }

    /**
     * @param string $tableAlias
     * @param string $field
     * @param string $rule
     * @param string $variableName
     */
    protected function setWhere(string $tableAlias, string $field, string $rule, string $variableName)
    {
        $variablePlaceholdersMapper = [
            Comparison::IN => '(:%s)',
            self::OPERATORS_COLLECTION['is_null'] => '',
            self::OPERATORS_COLLECTION['is_not_null'] => '',
            'default' => ':%s'
        ];

        $variablePlaceholder = array_key_exists($this->rules[$variableName], $variablePlaceholdersMapper) ? $variablePlaceholdersMapper[$this->rules[$variableName]] : $variablePlaceholdersMapper['default'];

        $this->where[$variableName] = sprintf(
            "%s.%s %s {$variablePlaceholder}",
            $tableAlias,
            $field,
            $rule,
            $variableName
        );
    }

    /**
     * @param string $variableName
     * @param string|array $value
     */
    protected function setParameters(string $variableName, $value)
    {
        switch($this->rules[$variableName]) {
            case self::OPERATORS_COLLECTION['like']:
            case self::OPERATORS_COLLECTION['not_like']:
                $parameter = is_string($value) ? "%${value}%" : $value;
                break;
            default:
                $parameter = $value;
        }
        if($parameter) {
            $this->parameters[$variableName] = $parameter;
        }
    }

    /**
     * @param string $entityClassName
     * @param string $field
     * @return string
     */
    protected function getEntityRule(string $entityClassName, string $field): string
    {
        $entityRepository = EntityManager::getRepository($entityClassName);
        return $entityRepository->getSearchRule($field);
    }

    /**
     * @param string $entityClassName
     * @return array
     */
    protected function getAssociationMappings(string $entityClassName): array
    {
        $entityMetadata = EntityManager::getClassMetadata($entityClassName);
        return $entityMetadata->associationMappings;
    }

    /**
     * @param string $key
     * @return string
     */
    protected function getVariableNameByKey(string $key): string
    {
        return Inflector::camelize(str_replace('.', '_', $key));
    }

    /**
     * @param string $key
     * @return bool
     */
    protected function hasRelations(string $key): bool
    {
        return (strpos($key, '.') === false) ? false : true;
    }

    /**
     * @param string $key
     * @return array
     */
    protected function getRelationsList(string $key): array
    {
        return explode('.', $key);
    }
}