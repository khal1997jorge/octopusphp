<?php

namespace App\Core\Interfaces;

interface IModelContract
{
    /**
     * Nombre de la tabla en la base de datos
     */
    public static function table(): string;

    /**
     * Columnas permitidas del modelo (no se debería incluir PK)
     */
    public static function columns(): array;

    /**
     * Columnas pk del modelo
     */
    public static function primaryKey(): string;
}
