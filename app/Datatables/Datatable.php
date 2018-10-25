<?php

namespace App\Datatables;

class Datatable {
    private $pdo;
    private $table;
    private $params;
    private $columns = [];
    private $where = [];

    public function __construct($table, \PDO $pdo, array $params)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->params = $params;
    }

    public function addWhere($where) {
        $this->where[] = $where;
        return $this;
    }

    public function addColumn($field, $title = '', $formatter = null) {
        $col = [
            'db' => $field,
            'dt' => $title ?: $field,
        ];

        if ($formatter) {
            $col['formatter'] = $formatter;
        }

        $this->columns[] = $col;
        return $this;
    }

    public function addRowId($field = 'id')
    {
        $this->columns[] = [
            'db' => $field,
            'dt' => 'DT_RowId',
            'formatter' => function ($data, $row) {
                return 'row_' . $data;
            },
        ];
        return $this;
    }

    public function toArray()
    {
        if (count($this->where)) {
            return SSP::complex(
                $this->params,
                $this->pdo,
                $this->table,
                $this->columns,
                null,
                join(' AND ', $this->where)
            );
        }

        return SSP::simple(
            $this->params,
            $this->pdo,
            $this->table,
            $this->columns
        );
    }
}
