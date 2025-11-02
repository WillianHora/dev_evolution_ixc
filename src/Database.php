<?php

class Database
{
    /** @var PDO */
    private $pdo;

    public function __construct()
    {
        try {
            $databasePath = __DIR__ . '/database/sqlite.db';

            $this->pdo = new PDO('sqlite:' . $databasePath);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            

        } catch (PDOException $e) {
            die("Database connection refused: " . $e->getMessage());
        }
    }

   
    public function exec($sql)
    {
        return $this->pdo->exec($sql);
    }


    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }
}