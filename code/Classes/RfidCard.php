<?php

/**
 * @property int           $id
 * @property string        $code
 *
 * Relations:
 * @property bool|Employee $employee
 *
 * @method static bool|self findByPk(string|int $pk)
 * @method static bool|self|array findByAttributes(array $attributes, bool $all = false, SQLOperator $operator = SQLOperator::AND)
 */
class RfidCard extends DAO implements DAOInterface
{

    use DAOTrait;

    protected static ?string $tableName = 'rfid_cards';

    /**
     * @return array[]
     */
    public static function relations(): array
    {
        return [
            'employee' => [RelationType::OneToOne, Employee::class, 'id', 'rfid_card_id'],
        ];
    }
}
