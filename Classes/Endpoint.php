<?php

/**
 *
 */

/**
 *
 */
class Endpoint
{

    protected string             $rfidCode = '';
    protected null|bool|RfidCard $rfidCard;

    /**
     * @param string $rfidCode
     */
    public function __construct(string $rfidCode)
    {
        $this->rfidCode = $rfidCode;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function respond(): string
    {
        $response = [
            'full_name'  => '',
            'department' => [],
        ];
        if (!$this->checkIfRfidExists()) {
            return $this->stringify($response);
        }
        $employee = $this->rfidCard->employee;
        if (null === $employee || false === $employee) {
            return $this->stringify($response);
        }
        $response['full_name'] = $employee->getFullName();
        $response['department'] = $employee->getDepartments();
        return $this->stringify($response);
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function checkIfRfidExists(): bool
    {
        $this->rfidCard = RfidCard::findByAttributes(['code' => $this->rfidCode]);
        if (false === $this->rfidCard || null === $this->rfidCard) {
            return false;
        }
        return true;
    }

    /**
     * @param array $array
     *
     * @return string
     */
    protected function stringify(array $array): string
    {
        try {
            $json = json_encode($array, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return '{"full_name":"","department":[],"errorCode":' . (int)$e->getCode() . ',"errorMsg":"' . $e->getMessage() . '"}';
        }
        return $json;
    }
}