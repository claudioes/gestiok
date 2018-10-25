<?php
namespace App\Controllers;

use Slim\Http\{
    Request,
    Response
};
use App\Models\Product;
use App\Validators\ProductValidator;
use App\Datatables\Datatable;

class ProductController extends Controller
{
    /**
     * Create or edit
     * @param  Response
     * @param  App\Models\Product   $product
     * @return Response
     */
    private function createOrEdit(Response $response, Product $product = null): Response
    {
        if ($product) {
            $view = 'edit';
        } else {
            $view = 'create';
        }

        return $this->render($response, "product/{$view}.twig", compact('product'));
    }

    /**
     * Store or update
     * @param  Request  $request
     * @param  Response $response
     * @param  App\Models\Product $product
     * @return Response
     */
    private function storeOrUpdate(Request $request, Response $response, Product $product = null): Response
    {
        $input = $request->getParams(['code', 'description']);

        /** @var Symfony\Component\Translation\Translator */
        $translator = $this->get('translator');
        $validation = ProductValidator::with($input, compact('product'))->setTranslator($translator);

        if ($validation->fails()) {
            return $response->withJson($validation->getErrors(), 400);
        }

        if ($product) {
            $product->setUpdatedBy($this->user);
        } else {
            $product = new Product;
            $product->setCreatedBy($this->user);
        }

        $product->fill($input)->save();

        return $response->withJson([
            'redirect' => $this->router->pathFor('product.view', ['product' => $product->id])
        ]);
    }

    /**
     * Index
     * @param  Request  $request
     * @param  Response $response
     * @return Response
     */
    public function index(Request $request, Response $response): Response
    {
        $this->authorize('product.view');

        return $this->render($response, 'product/index.twig');
    }

    /**
     * View
     * @param  Request  $request
     * @param  Response $response
     * @param  array    $args
     * @return Response
     */
    public function view(Request $request, Response $response, array $args): Response
    {
        $this->authorize('product.view');

        $product = Product::findOrFail($args['product']);

        return $this->render($response, 'product/view.twig', compact('product'));
    }

    /**
     * Create
     * @param  Request  $request
     * @param  Response $response
     * @return Response
     */
    public function create(Request $request, Response $response): Response
    {
        $this->authorize('product.create');

        return $this->createOrEdit($response);
    }

    /**
     * Store
     * @param  Request  $request
     * @param  Response $response
     * @return Response
     */
    public function store(Request $request, Response $response): Response
    {
        $this->authorize('product.create');

        return $this->storeOrUpdate($request, $response);
    }

    /**
     * Edit
     * @param  Request  $request
     * @param  Response $response
     * @param  array    $args
     * @return Response
     */
    public function edit(Request $request, Response $response, array $args): Response
    {
        $product = Product::findOrFail($args['product']);

        $this->authorize('edit', $product);

        return $this->createOrEdit($response, $product);
    }

    /**
     * Update
     * @param  Request  $request
     * @param  Response $response
     * @param  array    $args
     * @return Response
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $product = Product::findOrFail($args['product']);

        $this->authorize('edit', $product);

        return $this->storeOrUpdate($request, $response, $product);
    }

    /**
     * Delete
     * @param  Request  $request
     * @param  Response $response
     * @param  array    $args
     * @return Response
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        $product = Product::findOrFail($args['product']);

        $this->authorize('delete', $product);

        try {
            $product->delete();

            $this->user->addActivity('Producto eliminado', $product, $request->getAttribute('ip_address'));
            $this->flash->addMessage('success', sprintf(MESSAGE_DELETE_OK, 'el producto <strong>'.$product->full_description.'</strong>'));
        } catch (\Throwable $t) {
            $this->flash->addMessage('error', sprintf(MESSAGE_DELETE_ERROR, 'el producto <strong>'.$product->full_description.'</strong>'));
        }

        return $this->redirect($response, 'product.index');
    }

    /**
     * Datatable
     * @param  Request  $request
     * @param  Response $response
     * @return Response
     */
    public function datatable(Request $request, Response $response): Response
    {
        $this->authorize('product.view');

        $router = $this->router;

        $datatable = new Datatable('product', $this->get('pdo'), $request->getParams());
        $datatable->addRowId()
            ->addColumn('id')
            ->addColumn('code')
            ->addColumn('description')
            ->addColumn('id', 'action', function ($data, $row) use ($router) {
                $viewUrl = $router->pathFor('product.view', ['product' => $data]);

                return "
                    <div class=\"pull-right\">
                        <div class=\"btn-toolbar\" role=\"toolbar\">
                            <a type=\"button\" class=\"btn btn-default\" href=\"{$viewUrl}\">
                                Mostrar
                            </a>
                        </div>
                    </div>
                ";
            });

        return $response->withJson($datatable->toArray());
    }
}
