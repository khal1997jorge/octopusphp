<?php

namespace App\Core\Database\Clauses;

readonly class OrderBy
{
    public const string ASCENDENT = 'ASC';
    public const string DESCENDENT = 'DESC';

    public string $column;
    public string $order;

    public function __construct(string $column, string $order){
        $this->column = $column;
        $this->order = $order;
    }
}