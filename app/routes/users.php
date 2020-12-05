<?php
use App\Models\User;


    $app->group('/api', function () use ($app){
        /*$app->get('/users', function ($request, $response) {
            // put log message
            $this->logger->info("getting all users");

            $data  = User::all();


            return $this->view->render($response, 'profile.twig', [
                'data' => $data,
            ]);

        })->setName('profiler');
*/
        $app->get('/usersall', 'UserController:allUsers')->setName('profiler');


        $app->get('/destroy/[{id}]', 'UserController:deleteUser')->setName('user.detroy');



        $app->get('/users/[{id}]', function($request, $response, $args){
            // put log message
            $this->logger->info("getting user by id");

            $data = User::find($args['id']);
            //return $this->response->withJson($data, 200);
              return $this->view->render($response, 'profile.twig', [
                'data' => $data,
            ]);

        })->setName('detail');

        $app->post('/user', function ($request, $response) {
            // put log message
            $this->logger->info("saving user");

            $user = $request->getParsedBody();
            //$data = User::create($user);
            $data = User::create([
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'email' => $user['email']
            ]);
            return $this->response->withJson($data, 200);
        });
        $app->put('/user/[{id}]', function ($request, $response, $args) {
            // put log message
            $this->logger->info("updating user");

            $user = $request->getParsedBody();
            $data = User::where('id', $args['id'])->update([
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'email' => $user['email']
            ]);
            return $this->response->withJson($data, 200);
        });
        $app->delete('/user/[{id}]', function ($request, $response, $args) {
            // put log message
            $this->logger->info("deleting user");

            $data = User::destroy($args['id']);
            return $this->response->withJson($data, 200);
        });
    });

