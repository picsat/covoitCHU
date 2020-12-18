<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

/**
 * AuthController
 *
 * @author    Haven Shen <havenshen@gmail.com>
 * @copyright    Copyright (c) Haven Shen
 */
class AuthController extends Controller
{
    public function getSignOut($request, $response)
    {
        $this->auth->logout();
        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getSignIn($request, $response)
    {
        return $this->view->render($response, 'auth/signin.twig');
    }

    public function postSignIn($request, $response)
    {
        $auth = $this->auth->attempt(
            $request->getParam('email'),
            $request->getParam('password')
        );

        if (! $auth) {
            $this->flash->addMessage('error', 'Impossible de se connecter avec ces informations');
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getSignUp($request, $response)
    {
        return $this->view->render($response, 'auth/signup.twig');
    }

    public function postSignUp($request, $response)
    {

        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
            'password' => v::noWhitespace()->notEmpty()->length(6),
            'nom' => v::notEmpty()->alpha(),
            'prenom' => v::notEmpty()->alpha(),
            'ville' => v::notEmpty()->alpha(),
            'tel' => v::optional(v::noWhitespace()->phone()), // optionnel mais doit etre valide
            'gsm' => v::optional(v::noWhitespace()->phone()), // optionnel mais doit etre valide
        ]);


        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }

        $user = User::create([
            'email' => $request->getParam('email'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
            'nom' => $request->getParam('nom'),
            'prenom' => $request->getParam('prenom'),
            'ville' => $request->getParam('ville'),
            'service' => $request->getParam('service'),
            'color' => $request->getParam('color'),
            'tel' => $request->getParam('tel'),
            'gsm' => $request->getParam('gsm'),
        ]);

        $this->logger->info("Saving user ".$request->getParam('email'));
        $this->flash->addMessage('info', "L'utilisateur a été créé avec succès, vous devrez lui transmettre ses indentifiants (email : <a href=\"mailto:".$request->getParam('email')."\">".$request->getParam('email')."</a>)");

        //$auth = $this->auth->attempt($user->email,$request->getParam('password'));
        /*
        if (! $auth) {
            $this->flash->addMessage('error', 'Impossible de se connecter avec ces informations');
        }
        */

        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getChangeInfos($request, $response)
    {
        return $this->view->render($response, 'auth/card/change_infos.twig');
    }

    public function postChangeInfos($request, $response)
    {
        $validation = $this->validator->validate($request, [
            //'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
            'nom' => v::notEmpty()->alpha(),
            'prenom' => v::notEmpty()->alpha(),
            'ville' => v::notEmpty()->alpha(),
            'tel' => v::optional(v::noWhitespace()->phone()), // optionnel mais doit etre valide
            'gsm' => v::optional(v::noWhitespace()->phone()), // optionnel mais doit etre valide
        ]);


        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('auth.infos.change'));
        }

        $user =  $this->auth->user();
        $majUser = User::where('id', $user->id)->update([
                'nom' => $request->getParam('nom'),
                'prenom' => $request->getParam('prenom'),
                'ville' => $request->getParam('ville'),
                'service' => $request->getParam('service'),
                'color' => $request->getParam('color'),
                'tel' => $request->getParam('tel'),
                'gsm' => $request->getParam('gsm'),
            ]);

        if(!$majUser) {
            $this->flash->addMessage('error', 'Impossible de changer vos informations');
            return $response->withRedirect($this->router->pathFor('auth.infos.change'));
        }
        // on pourrait aussi proprement passer ca au accesseurs ...
        // exemple : $this->auth->user()->setPassword($request->getParam('password'));

        $this->flash->addMessage('info', "Vos données ont été mises à jour avec succès");
        return $response->withRedirect($this->router->pathFor('home'));
    }
}
