<?php

/**
 *
 */

/**
 *
 */
class DB
{
    const DB_HOST   = 'localhost';
    const DB_PORT   = 3306;
    const DB_USER   = 'root';
    const DB_PASS   = '2b2HGuzx91*!';
    const DB_SCHEMA = 'api_task';

    protected static bool|self $instance = false;

    public array           $params         = [];
    public string|bool|DAO $fetchClassName = false;

    protected bool|PDO          $dbHandle = false;
    protected bool|PDOStatement $stmt     = false;

    public function __construct()
    {
        self::$instance = $this;
    }

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        if (false === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * @param bool $all
     *
     * @return array|false|mixed
     * @throws Exception
     */
    /**
     * @param bool $all
     *
     * @return array|false|mixed
     * @throws Exception
     */
    public function fetch(bool $all = false)
    {
        if (0 < count($this->params)) {
            foreach ($this->params as $paramName => $paramDetails) {
                if (is_array($paramDetails)) {
                    if (2 !== count($paramDetails)) {
                        throw new Exception('Wrong arguments passed as params');
                    }
                    $paramValue = $paramDetails[0];
                    $paramType = $paramDetails[1];
                } else {
                    $paramValue = $paramDetails;
                    $paramType = PDO::PARAM_STR;
                }
                $this->stmt->bindValue($paramName, $paramValue, $paramType);
            }
            $this->params = [];
        }
        $this->stmt->setFetchMode($this->getFetchMode(), $this->fetchClassName ?: null);
        $this->stmt->execute();
        if ($all) {
            return $this->stmt->fetchAll();
        }
        return $this->stmt->fetch();
    }

    /**
     * @return int
     */
    public function getFetchMode(): int
    {
        return false !== $this->fetchClassName ? PDO::FETCH_CLASS : PDO::FETCH_ASSOC;
    }

    /**
     * @param string $query
     *
     * @return void
     * @throws Exception
     */
    public function setQuery(string $query): void
    {
        $this->stmt = $this->getDbHandle()->prepare($query);
        if (false === $this->stmt) {
            throw new Exception('Could not prepare statement');
        }
    }

    /**
     * @return PDO
     */
    private function getDbHandle(): PDO
    {
        if (false === $this->dbHandle) {
            $this->connect();
        }
        return $this->dbHandle;
    }

    /**
     * @return void
     */
    private function connect(): void
    {
        $this->dbHandle = new PDO('mysql:host=' . self::DB_HOST . ';port=' . self::DB_PORT . ';dbname=' . self::DB_SCHEMA, self::DB_USER, self::DB_PASS);
    }
}