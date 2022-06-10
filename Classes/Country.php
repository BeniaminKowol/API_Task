<?php

/**
 * @property int    $id
 * @property string $code
 * @property string $name
 */
class Country extends DAO implements DAOInterface
{

    use DAOTrait;

    protected static ?string $tableName = 'countries';

    /**
     * @return array
     */
    public static function relations(): array
    {
        return [];
    }
}