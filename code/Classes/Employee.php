<?php

/**
 * @property int                        $id
 * @property string                     $first_name
 * @property string                     $last_name
 * @property int                        $rfid_card_id
 *
 * Relations:
 * @property RfidCard                   $rfidCard
 * @property EmployeeDepartmentAccess[] $departmentAccesses
 */
class Employee extends DAO implements DAOInterface
{

    use DAOTrait;

    protected static ?string $tableName = 'employees';

    protected int   $departmentCount   = -1;
    protected array $departments       = [];
    protected array $departmentsConcat = [];

    public function __construct()
    {
        $this->departments = [
            DepartmentDataType::IDS->value   => [],
            DepartmentDataType::NAMES->value => [],
        ];
        $this->departmentsConcat = [
            DepartmentDataType::IDS->value   => '',
            DepartmentDataType::NAMES->value => '',
        ];
    }

    /**
     * @return array[]
     */
    public static function relations(): array
    {
        return [
            'rfidCard'           => [RelationType::OneToOne, RfidCard::class, 'rfid_card_id'],
            'departmentAccesses' => [RelationType::OneToMany, EmployeeDepartmentAccess::class, 'id', 'employee_id'],
        ];
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @param DepartmentDataType $departmentDataType
     * @param bool               $concat
     *
     * @return array|string
     */
    public function getDepartments(DepartmentDataType $departmentDataType = DepartmentDataType::NAMES, bool $concat = true): array|string
    {
        $this->refreshRelations();
        if (0 === $this->departmentCount || null === $this->departmentAccesses) {
            return $concat ? [] : '';
        }
        if (-1 === $this->departmentCount) {
            $this->departmentCount = 0;
            foreach ($this->departmentAccesses as $departmentAccess) {
                $departmentAccess->refreshRelations();
                if (null === $departmentAccess->department) {
                    continue;
                }
                $this->departmentCount++;
                $this->departments[DepartmentDataType::IDS->value][$departmentAccess->department->id] = $departmentAccess->department->id;
                $this->departments[DepartmentDataType::NAMES->value][$departmentAccess->department->id] = $departmentAccess->department->name;
            }

            $this->departmentsConcat[DepartmentDataType::IDS->value] = implode(', ', $this->departments[DepartmentDataType::IDS->value]);
            $this->departmentsConcat[DepartmentDataType::NAMES->value] = implode(', ', $this->departments[DepartmentDataType::NAMES->value]);
        }
        $property = $concat ? 'departmentsConcat' : 'departments';
        return $this->$property[$departmentDataType->value];
    }
}
