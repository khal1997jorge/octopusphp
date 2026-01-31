<?php

namespace App\Core;

use App\Core\Interfaces\IModelContract;
use App\Core\Database;
use App\Core\Exceptions\DatabaseException;

abstract class Model extends Database implements IModelContract
{
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Busca un registro coincidente o null
     * @return static
     */
    public static function findOne(array $conditions): ?static
    {
        $where = [];

        foreach ($conditions as $column => $value) {

            if (is_string($value)) {
                $value = self::connection()->real_escape_string($value);
                $value = "'$value'";
            }

            $where[] = "$column = $value";
        }

        $whereSql = implode(' AND ', $where);


        $result = self::select($whereSql);

        return $result[0] ?? null;
    }

    /**
     * Busca registros por condiciones
     * @return static[]
     */
    public static function findBy(array $conditions = []): array
    {
        $where = [];

        foreach ($conditions as $column => $value) {

            if (is_string($value)) {
                $value = self::connection()->real_escape_string($value);
                $value = "'$value'";
            }

            $where[] = "$column = $value";
        }

        $whereSql = $where ? implode(' AND ', $where) : '';

        return self::select($whereSql);
    }

    /**
     * Busca todos los registros coincidentes mediante una cláusula WHERE opcional
     * @return static[]
     */
    protected static function select(string $where = ''): array
    {
        $columns = implode(',', self::getAllColumns());
        $sql = "SELECT $columns FROM " . static::table();

        if ($where) {
            $sql .= " WHERE $where";
        }

        $result = self::connection()->query($sql);

        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        return self::mapToObjects($rows);
    }

    /**
     * Inserta un nuevo registro en la base de datos, omite las claves primarias
     * @return bool
     */
    public function insert(): bool
    {
        $columns = array_filter(
            static::columns(),
            fn($col) => !in_array($col, static::primaryKeys(), true)
        );

        $values = [];
        foreach ($columns as $column) {
            $value = $this->$column;

            if (is_string($value)) {
                $value = self::connection()->real_escape_string($value);
                $value = "'$value'";
            }

            $values[] = ($value === null) ? 'null': $value;
        }

        $sql = "INSERT INTO " . static::table() .
               " (" . implode(',', $columns) . ")" .
               " VALUES (" . implode(',', $values) . ")";

        return self::connection()->query($sql);
    }

    /**
     * Actualiza un registro existente usando la clave primaria
     */
    public function update(): bool
    {
        $sets  = [];
        $where = [];

        // SET → columnas NO PK
        foreach (static::columns() as $column) {
            if (!property_exists($this, $column)) {
                continue;
            }

            $value = $this->$column;

            if (is_string($value)) {
                $value = self::connection()->real_escape_string($value);
                $value = "'$value'";
            }

            $sets[] = "$column = $value";
        }

        // WHERE → primary keys
        foreach (static::primaryKeys() as $pk) {
            self::checkPrimaryKey($pk);

            $value = $this->$pk;
            $value = is_string($value)
                ? "'" . self::connection()->real_escape_string($value) . "'"
                : (int)$value;

            $where[] = "$pk = $value";
        }

        $sql = "UPDATE " . static::table() .
            " SET " . implode(', ', $sets) .
            " WHERE " . implode(' AND ', $where);

        return self::connection()->query($sql);
    }

    /**
     * Elimina un registro usando la clave primaria
     */
    public function delete(): bool
    {
        $where = [];

        foreach (static::primaryKeys() as $pk) {
            self::checkPrimaryKey($pk);

            $value = $this->$pk;
            $value = is_string($value)
                ? "'" . self::connection()->real_escape_string($value) . "'"
                : (int)$value;

            $where[] = "$pk = $value";
        }

        $sql = "DELETE FROM " . static::table() .
            " WHERE " . implode(' AND ', $where);

        return self::connection()->query($sql);
    }

    /**
     * Mapea filas de base de datos a objetos del modelo
     * @return static[]
     */
    protected static function mapToObjects(array $rows): array
    {
        $objects = [];

        foreach ($rows as $row) {
            $objects[] = new static($row);
        }

        return $objects;
    }

    /** 
     * Obtiene todas las columnas del modelo sin repeticiones
     */
    protected static function getAllColumns(): array
    {
        return array_unique(array_merge(static::primaryKeys(), static::columns()));
    }

    /** 
     * Verifica que la clave primaria exista como propiedad del modelo
     * @throws DatabaseException Si la clave primaria no está definida
     */
    private function checkPrimaryKey(string $pk): void
    {
        if (!property_exists($this, $pk)) {
            throw new DatabaseException("Primary key '$pk' no está definida en el modelo '" . static::class . "'");
        }
    }
}
