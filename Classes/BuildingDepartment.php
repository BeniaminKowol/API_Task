<?php

/**
 * @property int        $id
 * @property int        $building_id
 * @property int        $department_id
 *
 * Relations:
 * @property Building   $building
 * @property Department $department
 */
class BuildingDepartment extends DAO implements DAOInterface
{

    use DAOTrait;

    protected static ?string $tableName = 'building_departments';

    /**
     * @return array[]
     */
    public static function relations(): array
    {
        return [
            'building'   => [RelationType::OneToOne, Building::class, 'building_id'],
            'department' => [RelationType::OneToMany, Department::class, 'department_id'],
        ];
    }
}