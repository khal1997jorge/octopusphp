<?php

namespace App\Core\Database\Clauses;

readonly class Where
{
    public string $column;
    public string $operator;
    public mixed $value;

    public function __construct(string $column, string $operator, mixed $value){
        $this->column = $column;
        $this->operator = $operator;
        $this->value = $value;
    }
}