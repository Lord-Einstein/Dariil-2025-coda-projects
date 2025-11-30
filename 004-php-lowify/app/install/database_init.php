<?php

require_once __DIR__ . '/../inc/database.inc.php';

class DatabaseInitializer
{
    private const SUCCESS_MESSAGE = '✅ OK';
    private const ERROR_MESSAGE = '❌ Erreur';

    private DatabaseManager $dbm;

    public function __construct()
    {
        $this->dbm = new DatabaseManager(
            dsn: 'mysql:host=mysql;dbname=lowify;charset=utf8mb4',
            username: 'lowify',
            password: 'lowifypassword'
        );
    }

    public function initialize(): array
    {
        $steps = [
            'Connexion à la base de données' => $this->stepConnect(),
            'Création du schéma & import des données' => $this->stepCreateSchema(),
        ];

        return $steps;
    }

    private function stepConnect(): string
    {
        try {
            $this->dbm->executeQuery('SELECT 1');
            return self::SUCCESS_MESSAGE;
        } catch (PDOException $e) {
            return self::ERROR_MESSAGE . ' : ' . $e->getMessage();
        }
    }

    private function stepCreateSchema(): string
    {
        try {
            $this->dbm->executeUpdate(file_get_contents(__DIR__ . '/odb.sql'));
            return self::SUCCESS_MESSAGE;
        } catch (PDOException $e) {
            return self::ERROR_MESSAGE . ' : ' . $e->getMessage();
        }
    }
}
