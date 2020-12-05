<?php
/*
 * UserController only for controller sample
 * @hilmanrdn 18-01-2017
 */

namespace App\Controllers;
use App\Models\User;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;


class UserController extends Controller
{

    protected $logger;
    protected $view;

    public function __construct(Twig $view, LoggerInterface $logger)
    {
        $this->view = $view;
        $this->logger = $logger;

        // put log message
        $this->logger->info("'/users' route");
    }

    public function allUsers(Request $request, Response $response, array $arg)
    {

        // put log message
        $this->logger->info("getting all users");

        $data  = User::all();


        return $this->view->render($response, 'profile.twig', [
            'data' => $data
        ]);


    }

    public function deleteUser(Request $request, Response $response, array $arg)
    {



        $user =  User::find(isset($_SESSION['user']) ? $_SESSION['user'] : 0);

        $this->logger->info("deleting user");

        $data = User::destroy($user['id']);

        //$this->flash->addMessage('error', 'Kill user '. $user['email']);

        unset($_SESSION['user']);

        return $this->view->render($response, 'home.twig');
        //return $response->withRedirect($this->router->pathFor('home'));



    }
}
