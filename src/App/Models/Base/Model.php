<?php

namespace App\Models\Base;

use App\Core\Database\Clauses\OrderBy;
use App\Core\Database\Clauses\Set;
use App\Core\Database\Clauses\Where;
use App\Core\Database\Database;
use App\Core\Exceptions\DatabaseException;
use App\Core\Exceptions\InvalidParametersException;
use App\Core\Interfaces\IModelContract;
use DateTime;

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
     * Verifica que exista un registro en BD con las claúsulas buscadas
     *
     * @param Where[] $wheres
     *
     * @return bool
     * @throws InvalidParametersException|DatabaseException
     */
    public static function exists(array $wheres): bool{
        return self::findOne($wheres) !== null;
    }

    /**
     * Busca un registro por múltiples condiciones.
     *
     * @param Where[] $wheresConditions
     *
     * @return static|null
     * @throws InvalidParametersException|DatabaseException
     */
    public static function findOne(array $wheresConditions): ?static
    {
        $whereStatement = self::buildWhereStatement($wheresConditions);
        $tableName = static::table();

        $sql = "SELECT * FROM $tableName WHERE $whereStatement LIMIT 1";

        $result = self::connection()->query($sql);

        if (!$result || $result->num_rows === 0) {
            return null;
        }

        return new static($result->fetch_assoc());
    }

    /**
     * Busca múltiples registros por condiciones.
     *
     * @param Where[] $wheresConditions
     *
     * @return static[]
     * @throws DatabaseException
     * @throws InvalidParametersException
     */
    public static function findBy(array $wheresConditions, ?OrderBy $order = null): array
    {
        $whereStatement = self::buildWhereStatement($wheresConditions);
        $tableName = static::table();


        $sql = "SELECT * FROM $tableName WHERE $whereStatement";

        if($order){
            $orderByStatement = self::buildOrderByStatement($order);
            $sql .= " ORDER BY $orderByStatement";
        }

        $result = self::connection()->query($sql);

        if (!$result || $result->num_rows === 0) {
            return [];
        }

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = new static($row);
        }

        return $rows;
    }





    /**
     * Inserta un nuevo registro en la base de datos, omite las claves primarias
     * @return bool
     * @throws DatabaseException
     */
    public function insert(): bool
    {
        $columnsModel = static::columns();

        $valuesFormatted = [];
        foreach ($columnsModel as $modelProperty) {
            $value = $this->$modelProperty;
            $valuesFormatted[] = self::formatValueForSql($value);
        }

        $tableName = static::table();
        $columnsNameAsString = implode(', ', $columnsModel);
        $valuesAsString = implode(', ', $valuesFormatted);

        $sql = "INSERT INTO $tableName ($columnsNameAsString) VALUES ($valuesAsString)";

        return self::connection()->query($sql);
    }

    /**
     * Actualiza un registro existente usando la clave primaria
     * @throws DatabaseException
     * @throws InvalidParametersException
     */
    public function update(): bool
    {
        $sets  = [];
        foreach (static::columns() as $columnName) {
            if ( !property_exists($this, $columnName) ) {
                continue;
            }

            $sets[] = new Set($columnName, $this->$columnName);
        }
        $setStatement = self::buildSetStatement($sets);

        $wheres = [];
        $pk = static::primaryKey();
        self::checkPrimaryKey($pk);
        $wheres[] = new Where($pk, '=', $this->$pk);

        $whereStatement = self::buildWhereStatement($wheres);

        $tableName = static::table();

        $sql = "UPDATE $tableName SET $setStatement WHERE $whereStatement";

        return self::connection()->query($sql);
    }

    /**
     * Elimina un registro usando la clave primaria
     * @throws DatabaseException
     */
    public function delete(): bool
    {
        $wheres = [];

        $pk = static::primaryKey();
        self::checkPrimaryKey($pk);

        $wheres[] = new Where($pk, '=', $this->$pk);

        $tableName = static::table();
        $whereStatement = self::buildWhereStatement($wheres);

        $sql = "DELETE FROM $tableName WHERE $whereStatement";

        return self::connection()->query($sql);
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

    private static function buildOrderByStatement(OrderBy $clause){
        return $clause->column . ' ' . $clause->order;
    }

    /**
     * @param Where[] $wheresConditions
     * @return string
     */
    private static function buildWhereStatement(array $wheresConditions): string{
        $whereParts = [];

        foreach ($wheresConditions as $condition) {

            if (!$condition instanceof Where) {
                throw new InvalidParametersException('Invalid where condition');
            }

            $column   = $condition->column;
            $operator = $condition->operator;

            if(is_array($condition->value)){
                if(empty($condition->value)){
                    $whereParts[] = '1 = 0'; // Busqueda imposible
                   continue;
                }

                $valuesParsed = array_map(
                    fn($val) => self::formatValueForSql($val),
                    $condition->value
                );

                $value = '(' . implode(', ', $valuesParsed) . ')';

            } else {
                $value = self::formatValueForSql($condition->value);
            }

            $whereParts[] = "$column $operator $value";
        }

        return implode(' AND ', $whereParts);
    }

    /**
     * @param Set[] $sets
     * @return string
     * @throws DatabaseException|InvalidParametersException
     */
    private static function buildSetStatement(array $sets): string {
        $setsPart = [];

        foreach ($sets as $condition) {

            if (!$condition instanceof Set) {
                throw new InvalidParametersException('Invalid where condition');
            }

            $column   = $condition->column;
            $value    = self::formatValueForSql($condition->value);

            $setsPart[] = "$column = $value";
        }

        return implode(' , ', $setsPart);
    }

    /**
     * @param $value
     * @return float|int|string
     * @throws DatabaseException
     */
    private static function formatValueForSql($value): float|int|string
    {
        if ($value === null) {
            return 'NULL';
        } else if (is_int($value) || is_float($value)) {
            return $value;
        } else if (is_bool($value)) {
            return $value ? '1' : '0';
        } else {
            $escaped = self::connection()->real_escape_string((string) $value);
            return "'$escaped'";
        }
    }
}
