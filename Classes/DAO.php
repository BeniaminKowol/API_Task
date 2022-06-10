<?php

/**
 *
 */

/**
 *
 */
class DAO implements DAOInterface
{

    protected static string  $pkColumn = 'id';
    protected static ?string $tableName;
    protected static ?array  $relations;

    /**
     * @return string
     */
    public static function pkColumn(): string
    {
        return self::$pkColumn;
    }

    /**
     * @param string|int $pk
     *
     * @return bool|static
     * @throws Exception
     */
    protected static function findByPk(string|int $pk): bool|self
    {
        if (empty(self::$tableName)) {
            throw new Exception('Table name not specified');
        }
        $tableName = self::$tableName;
        $pkColumn = self::$pkColumn;
        $query = <<<SQL
    SELECT
        *
    FROM {$tableName}
    WHERE
        {$pkColumn} = :pk
    LIMIT 1
SQL;
        $db = DB::getInstance();
        $db->fetchClassName = get_called_class();
        $db->setQuery($query);
        $db->params[':pk'] = [$pk, PDO::PARAM_INT];
        return $db->fetch();
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        /** @var DAOInterface $className */
        $className = get_called_class();
        $className::refreshRelations();
        if (isset(self::$relations[$name])) {
            if (!isset($this->$name)) {
                $this->initRelation($name);
            }
            return $this->$name;
        }
        return null;
    }

    /**
     * @param string $relationName
     *
     * @return void
     */
    private function initRelation(string $relationName): void
    {
        $className = self::$relations[$relationName][1];
        $refColumn = self::$relations[$relationName][3] ?? $className::$pkColumn;
        switch (self::$relations[$relationName][0]) {
            case RelationType::OneToOne:
                $this->$relationName = $className::findByAttributes([
                    $refColumn => [
                        $this->{self::$relations[$relationName][2]},
                        PDO::PARAM_INT,
                    ],
                ]);
                break;
            case RelationType::OneToMany:
                $this->$relationName = $className::findByAttributes([
                    $refColumn => [
                        $this->{self::$relations[$relationName][2]},
                        PDO::PARAM_INT,
                    ],
                ], true, SQLOperator::OR);
                break;
        }
    }

    /**
     * @param array       $attributes
     * @param bool        $all
     * @param SQLOperator $operator
     *
     * @return bool|array|static
     * @throws Exception
     */
    protected static function findByAttributes(array $attributes, bool $all = false, SQLOperator $operator = SQLOperator::AND): bool|self|array
    {
        if (empty(self::$tableName)) {
            throw new Exception('Table name not specified');
        }
        if (empty($attributes)) {
            throw new Exception('Empty attributes');
        }
        $db = DB::getInstance();
        $db->fetchClassName = get_called_class();
        $attributesConditionArray = [];
        foreach ($attributes as $attributeName => $attributeDetails) {
            if (is_array($attributeDetails)) {
                if (2 !== count($attributeDetails)) {
                    throw new Exception('Wrong arguments passed as attribute value');
                }
                $paramValue = $attributeDetails[0];
                $paramType = $attributeDetails[1];
            } else {
                $paramValue = $attributeDetails;
                $paramType = PDO::PARAM_STR;
            }
            $db->params[':' . $attributeName] = [$paramValue, $paramType];
            $attributesConditionArray[] = $attributeName . ' = :' . $attributeName;
        }
        $attributesCondition = implode(' ' . $operator->value . ' ', $attributesConditionArray);
        $tableName = $db->fetchClassName::$tableName;
        $query = <<<SQL
    SELECT
        *
    FROM {$tableName}
    WHERE
        {$attributesCondition}
SQL;
        $db->setQuery($query);
        return $db->fetch($all);
    }

    /**
     * @return void
     */
    public function initAllRelations(): void
    {
        /** @var DAOInterface $className */
        $className = get_called_class();
        self::$pkColumn = $className::$pkColumn ?? self::$pkColumn;
        self::$tableName = $className::$tableName;
        self::$relations = $className::relations();

        foreach (self::$relations as $relationName => $relationDetails) {
            $this->initRelation($relationName);
        }
    }

    /**
     * @return array
     */
    public static function relations(): array
    {
        return self::$relations;
    }
}