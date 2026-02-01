<?php

namespace App\Core\Database;
use App\Core\Exceptions\DatabaseException;
use mysqli;

class Database
{
    private const CONFIG_DB_PATH = CONFIG_PATH.'/app.ini';
    private const CONFIG_SECTION = 'database';

    protected static ?mysqli $conn = null; //patron Singleton

    /**
     * @throws DatabaseException
     */
    protected static function connection(): mysqli
    {

        if (!self::$conn) {
            $dbConfig = self::getConfigArray();

            self::$conn = new mysqli(
                $dbConfig['host'],
                $dbConfig['user'],
                $dbConfig['password'],
                $dbConfig['name'],
                $dbConfig['port']
            );

            if (self::$conn->connect_error) {
                throw new DatabaseException('Error de conexión MySQL: ' . self::$conn->connect_error);
            }

            self::$conn->set_charset('utf8mb4');
        }

        return self::$conn;
    }

    /**
     * @throws DatabaseException
     */
    private static function getConfigArray(): array {
        if (!file_exists(self::CONFIG_DB_PATH)) {
            throw new DatabaseException("Config file not found: " . self::CONFIG_DB_PATH);
        }
        $config = parse_ini_file(
            self::CONFIG_DB_PATH,
            true,
            INI_SCANNER_TYPED
        );

        if (!$config || !isset($config[self::CONFIG_SECTION])) {
            throw new DatabaseException("Invalid or missing section [" .self::CONFIG_SECTION . "] in: " . self::CONFIG_DB_PATH);
        }

        $config = $config[self::CONFIG_SECTION];

        $missingParameters = [] ;

        foreach ($config as $nameConfig => $valueConfig) {
            if(!$valueConfig)
                $missingParameters[] = $nameConfig;
        }

        if($missingParameters) {
            $errorMessage = 'Error DB Connection: Missing parameters in .env file : ';

            $errorMessage .= implode(', ', $missingParameters);

            throw new DatabaseException($errorMessage);
        }

        return $config;
    }
}
