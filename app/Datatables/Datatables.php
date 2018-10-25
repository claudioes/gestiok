<?php

namespace App\Datatables;

// use Illuminate\Database\Eloquent\Builder;
// use Psr\Http\Message\RequestInterface;
// use Psr\Http\Message\ResponseInterface;

class Datatables
{
    // protected $query;
    // protected $response;
    // protected $params;
    // protected $columns;

    // public function __construct(Builder $query, RequestInterface $request, ResponseInterface $response)
    // {
    //     $this->query = $query;
    //     $this->response = $response;
    //     $this->params = $request->getQueryParams();
    // }

    // public function response(): ResponseInterface
    // {
    //     return $this->response->withJson([
	// 		"draw"            => (int)$this->params['draw'],
	// 		"recordsTotal"    => $this->count(),
	// 		"recordsFiltered" => $this->filteredCount(),
	// 		"data"            => $this->data()
    //     ]);
    // }

    // public function count(): int
    // {
    //     return $this->query->count();
    // }

    // public function filteredCount()
    // {
    //     return $this->query->count();
    // }

    // public function addColumn(string $name, callable $formatter)
    // {
    //     $this->columns[] = [
    //         'name' => $name,
    //         'formatter' => $formatter,
    //     ];
    // }

    // public function data()
    // {
    //     if (isset($this->params['order'])) {
    //         foreach($this->params['order'] as $order) {
    //             $column = $this->getColumn($order['column']);

    //             if ($column['orderable'] == 'true') {
    //                 $this->query->orderBy($column['data'], $order['dir']);
    //             }
    //         }
    //     }

    //     if (isset($this->params['search']) && $this->params['search']['value'] !== '') {
	// 		$keyword = $this->params['search']['value'];
    //         $columns = $this->params['columns'];

    //         $this->query->where(function ($query) use ($columns, $keyword) {
    //             foreach($columns as $column) {
    //                 if ($column['searchable'] === 'true') {
    //                     $this->query->orWhere($column['data'], 'LIKE', '%'.$keyword.'%');
    //                 }
    //             }
    //         });
	// 	}

	// 	// // Individual column filtering
	// 	// for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
	// 	// 	$requestColumn = $request['columns'][$i];
	// 	// 	$columnIdx = array_search( $requestColumn['data'], $dtColumns );
	// 	// 	$column = $columns[ $columnIdx ];
    //     //
	// 	// 	$str = $requestColumn['search']['value'];
    //     //
	// 	// 	if ( $requestColumn['searchable'] == 'true' &&
	// 	// 	 $str != '' ) {
	// 	// 		$binding = self::bind( $bindings, '%'.$str.'%', \PDO::PARAM_STR );
	// 	// 		$columnSearch[] = "`".$column['db']."` LIKE ".$binding;
	// 	// 	}
	// 	// }

    //     $this->query->limit((int)$this->params['length']);
    //     $this->query->offset((int)$this->params['start']);

    //     $data = $this->query->get()->toArray();

    //     foreach($data as $i => $row) {
    //         foreach($this->columns as $column) {
    //             $data[$i][$column['name']] = $column['formatter']($row);
    //         }
    //     }

    //     return $data;
    // }

    // public function getColumn(int $index): array
    // {
    //     return $this->params['columns'][$index];
    // }
}
