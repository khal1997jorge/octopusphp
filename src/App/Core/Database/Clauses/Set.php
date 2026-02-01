<?php

namespace App\Core\Database\Clauses;

readonly class Set
{
    public string $column;
    public mixed $value;

    public function __construct(string $column, mixed $value){
        $this->column = $column;
        $this->value = $value;
    }
}