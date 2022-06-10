<?php

/**
 * @property int     $id
 * @property int     $country_id
 * @property string  $name
 *
 * Relations:
 * @property Country $country
 */
class Building extends DAO implements DAOInterface
{

    use DAOTrait;

    protected static ?string $tableName = 'buildings';

    /**
     * @return array[]
     */
    public static function relations(): array
    {
        return [
            'country' => [RelationType::OneToOne, Country::class, 'country_id'],
        ];
    }
}
