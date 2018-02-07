<?php

namespace App\Controllers;

use App\Models\User;
use Slim\Views\Twig as View;

class ApiController extends Controller
{
  public function index($request, $response)
  {
    
    // User::create([
    //   'name' => 'Alex',
    //   'email' => 'demo@demo.com',
    //   'password' => '123'
    // ]);
    // //$user = $this->db->table('users')->find(1);
    // //$user = User::find(1);
    // $user = User::where('email', 'colormono@gmail.com')->first();
    // var_dump($user->email);
    // var_dump($user);
    // die();
    //var_dump($request->getParam('name'));
    return $this->view->render($response, 'home.twig');
  }

  public function getAllUsers()
  {
    //$user = User::where('email', 'colormono@gmail.com')->first();
    $users = User::all();
    return $this->response->withJson($users);
  }

  public function getUser($email)
  {
    $user = User::where('email', $this->email)->first();
    return $this->response->withJson($user);
  }
}