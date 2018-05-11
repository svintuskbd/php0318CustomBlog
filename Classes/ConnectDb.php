<?php

namespace Classes;

use PDO;
use PDOException;

class ConnectDb
{
    /**
     * @var string $host
     */
    private $host;

    /**
     * @var string $dbName
     */
    private $dbName;

    /**
     * @var string $user
     */
    private $user;

    /**
     * @var string $password
     */
    private $password;

    /**
     * @var PDO $dbh ;
     */
    private $dbh;

    /**
     * ConnectDb constructor.
     *
     * @param string $host
     * @param string $dbName
     * @param string $user
     * @param string $password
     */
    public function __construct($host, $dbName, $user, $password)
    {
        $this->host = $host;
        $this->dbName = $dbName;
        $this->user = $user;
        $this->password = $password;
    }

    public function getConnect()
    {
        if (!$this->dbh) {
            $this->connectDb();
            $this->getConnect();
        }

        return $this->dbh;
    }

    public function __toString()
    {
        return 'Connect DB';
    }

    private function connectDb()
    {
        $this->dbh = new PDO(
            'mysql:host=' . $this->host . ';
            dbname=' . $this->dbName,
            $this->user,
            $this->password
        );
    }
}