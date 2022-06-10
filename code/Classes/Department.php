<?php

/**
 * @property int    $id
 * @property string $name
 */
class Department extends DAO implements DAOInterface
{

    use DAOTrait;

    protected static ?string $tableName = 'departments';

    /**
     * @return array
     */
    public static function relations(): array
    {
        return [];
    }
}
