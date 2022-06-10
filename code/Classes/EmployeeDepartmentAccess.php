<?php

/**
 * @property int        $id
 * @property int        $employee_id
 * @property int        $department_id
 *
 * Relations:
 * @property Employee   $employee
 * @property Department $department
 */
class EmployeeDepartmentAccess extends DAO implements DAOInterface
{

    use DAOTrait;

    protected static ?string $tableName = 'employee_department_access';

    /**
     * @return array[]
     */
    public static function relations(): array
    {
        return [
            'employee'   => [RelationType::OneToOne, Employee::class, 'employee_id'],
            'department' => [RelationType::OneToOne, Department::class, 'department_id'],
        ];
    }
}
