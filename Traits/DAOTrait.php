<?php

/**
 *
 */

/**
 *
 */
trait DAOTrait
{

    /** @noinspection PhpMultipleClassDeclarationsInspection */
    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return bool|DAO
     */
    public static function __callStatic(string $name, array $arguments): bool|DAO
    {
        parent::$tableName = self::$tableName;
        parent::$pkColumn = self::$pkColumn;
        parent::$relations = self::relations();
        return parent::$name(...$arguments);
    }

    /**
     * @return void
     */
    public static function refreshRelations(): void
    {
        parent::$relations = self::relations();
    }
}