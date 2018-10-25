<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\User;
use App\Models\Role;
use App\Validators\UserValidator;
use App\Validators\UserPasswordValidator;

class UserController extends Controller
{
    
    /**
     * Index
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     *
     * @return \Slim\Http\Response
     */
    public function index(Request $request, Response $response): Response
    {
        $users = User::filter($request)
            ->orderBy('name')
            ->paginateFilter(ITEMS_PER_PAGE);

        return $this->render(
            $response,
            'user/index.twig',
            [
                'users' => $users,
                'params' => $users->params(),
            ]
        );
    }

    /**
     * Create
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     *
     * @return \Slim\Http\Response
     */
    public function create(Request $request, Response $response): Response
    {
        $this->authorize('create', User::class);

        return $this->createOrEdit($response);
    }

    /**
     * Store
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     *
     * @return \Slim\Http\Response
     */
    public function store(Request $request, Response $response): Response
    {
        $this->authorize('create', User::class);

        return $this->storeOrUpdate($request, $response);
    }

    /**
     * Edit
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param  array              $args
     *
     * @return \Slim\Http\Response
     */
    public function edit(Request $request, Response $response, array $args): Response
    {
        $user = User::findOrFail($args['id']);

        $this->authorize('edit', $user);

        return $this->createOrEdit($response, $user);
    }

    /**
     * Update
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param  array              $args
     *
     * @return \Slim\Http\Response
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $user = User::findOrFail($args['id']);

        $this->authorize('edit', $user);

        return $this->storeOrUpdate($request, $response, $user);
    }

    /**
     * Create or edit
     *
     * @param \Slim\Http\Response $response
     * @param  App\Models\User $user
     *
     * @return \Slim\Http\Response
     */
    private function createOrEdit(Response $response, User $user = null): Response
    {
        $view = 'create';
        $userRoles = [];

        if ($user) {
            $view = 'edit';
            $userRoles = $user->roles()
                ->pluck('id')
                ->toArray();
        }

        $roles = Role::orderBy('name')
            ->get()
            ->pluck('name', 'id')
            ->toArray();
            
        return $this->render(
            $response,
            "user/{$view}.twig",
            [
                'user' => $user,
                'roles' => $roles,
                'user_roles' => $userRoles,
            ]
        );
    }

    /**
     * Store or update
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param  App\Models\User $user
     *
     * @return \Slim\Http\Response
     */
    private function storeOrUpdate(Request $request, Response $response, User $user = null): Response
    {
        $validation = UserValidator::make($request, $this->get('translator'));

        if ($validation->fails()) {
            return $response->withJson($validation->getErrors(), 400);
        }

        $input = [
            'email'     => $request->getParam('email'),
            'name'      => $request->getParam('name'),
            'is_active' => (bool)$request->getParam('is_active', true),
            'is_admin'  => (bool)$request->getParam('is_admin', false),
        ];

        if (is_null($user)) {
            $user = new User;
        }

        $uploadedFiles = $request->getUploadedFiles();

        if (isset($uploadedFiles['avatar'])) {
            $uploadedFile = $uploadedFiles['avatar'];

            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {

                // Unlink old avatar
                if ($user->avatar) {
                    $path = $user->avatar_path;

                    if (file_exists($path)) {
                        unlink($path);
                    }
                }

                $user->avatar = sprintf('%s.png', uniqid());

                $this->image
                    ->make($uploadedFile->file)
                    ->encode('png')
                    ->fit(120, 120)
                    ->save($user->avatar_path);
            }
        }

        $user->fill($input);
        $user->save();

        // Sync user roles

        $roles = (array)$request->getParam('roles');
        $user->roles()->sync($roles);

        return $response->withJson([
            'redirect' => $this->pathFor('user.index'),
        ]);
    }
}
