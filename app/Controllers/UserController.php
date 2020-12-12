<?php
/*
 * UserController only for controller sample
 * @hilmanrdn 18-01-2017
 */

namespace App\Controllers;
use App\Models\User;
use App\Auth\Auth;

use Slim\Http\Request;
use Slim\Http\Response;



class UserController extends Controller
{


    public function allUsers(Request $request, Response $response, array $arg)
    {

        // put log message
        $this->logger->info("getting all users");

        $data  = User::all();


        return $this->view->render($response, 'profile.twig', [
            'data' => $data
        ]);


    }

    public function deleteUser(Request $request, Response $response, array $args)
    {

        $user_id = $args['id'];
        $user =  User::find($user_id ? $user_id : 0);

        $this->logger->info("Deleting User {\"id\" : \"$user_id\"}");

        $data = User::destroy($user_id);
        if($data)
        {
            $this->flash->addMessage('error', 'Vous venez de supprimer le compte '. $user->nom . ' '. $user->prenom . ' (' . $user->email .')' );
            $this->auth->logout();
            unset($_SESSION['user']);

            return $response->withRedirect($this->router->pathFor('home'));
        }

        //return $this->view->render($response, 'home.twig');
        return $response->withRedirect($this->router->pathFor('home'));



    }


}
