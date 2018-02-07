<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class AuthController extends Controller
{
  public function getSignOut($request, $response)
  {
    // sign out
    $this->auth->logout();

    // redirect
    return $response->withRedirect($this->router->pathFor('home'));
  }

  public function getSignIn($request, $response)
  {
    return $this->view->render($response, 'auth/signin.twig');
  }

  public function postSignIn($request, $response)
  {
    $auth = $this->auth->attemp(
      $request->getParam('email'),
      $request->getParam('password')
    );

    if( !$auth ){
      $this->flash->addMessage('error', 'Datos incorrectos.');
      return $response->withRedirect($this->router->pathFor('auth.signin'));
    } else {
      $this->flash->addMessage('info', 'Bienvenido.');
      return $response->withRedirect($this->router->pathFor('home'));
    }
  }

  public function getSignUp($request, $response)
  {
    return $this->view->render($response, 'auth/signup.twig');
  }
  
  public function postSignUp($request, $response)
  {
    // Validar formulario
    $validation = $this->validator->validate($request, [
      'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
      'name' => v::notEmpty()->alpha(),
      'password' => v::noWhitespace()->notEmpty()
    ]);

    if( $validation->failed() ){
      return $response->withRedirect($this->router->pathFor('auth.signup'));
      //var_dump($this->router->pathFor('auth.signup'));
      //die();
    }

    // Obtener los datos enviados
    //var_dump($request->getParams());
    $user = User::create([
      'email' => $request->getParam('email'),
      'name' => $request->getParam('name'),
      // hash password
      'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT)
    ]);

    // Flash message
    $this->flash->addMessage('info', 'Bienvenido.');

    // Autologin
    $this->auth->attemp(
      $user->email, 
      $request->getParam('password')
    );

    // Redirect to homepage
    return $response->withRedirect($this->router->pathFor('home'));
  }
}