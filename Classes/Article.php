<?php

namespace Classes;

use PDO;

class Article
{
    /**
     * @var \PDO $connect
     */
    private $connect;

    /**
     * Article constructor.
     *
     * @param $connect
     */
    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    /**
     * Get Articles
     *
     * @return array|bool
     */
    public function getArticles()
    {
        if ($this->connect) {
            $sql = "SELECT *
                FROM articles
                ";

            return $this->connect->query($sql)->fetchAll(PDO::FETCH_OBJ);
        }

        return false;
    }

    public function getAuthor($id)
    {
        if ($this->connect) {
            $sql = "SELECT *
                FROM users
                WHERE id='$id'
                ";

            return $this->connect->query($sql)->fetch(PDO::FETCH_OBJ);
        }

        return false;
    }
}