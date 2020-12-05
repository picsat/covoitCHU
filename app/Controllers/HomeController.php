<?php

namespace App\Controllers;

use App\Models\User;

/**
 * HomeController
 *
 * @author    Haven Shen <havenshen@gmail.com>
 * @copyright    Copyright (c) Haven Shen
 */
class HomeController extends Controller
{
    public function index($request, $response, array $args)
    {
        return $this->view->render($response,'home.twig', $args);
    }
}
