<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

use App\Models\User;

// Home
$app->get('/', 'HomeController:index')->setName('home');

// Guest routes
$app->group('', function(){

  // Sign in
  $this->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
  $this->post('/auth/signin', 'AuthController:postSignIn');

  // Sign up
  $this->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
  $this->post('/auth/signup', 'AuthController:postSignUp');

})->add(new GuestMiddleware($container));

// Protected routes
$app->group('', function(){

  // Sign out
  $this->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');

  // Change password
  $this->get('/auth/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');
  $this->post('/auth/password/change', 'PasswordController:postChangePassword');

})->add(new AuthMiddleware($container));

// API
$app->group('/api', function () {
    
  $this->get('/test', function ($request, $response, $args) {
    return $response->getBody()->write('Hello Users3');
  });
  
  $this->get('/users', 'ApiController:getAllUsers')->setName('api.users');

  $this->get('/colormono', function() {
    $user = User::where('email', 'colormono@gmail.com')->first();
    return $this->response->withJson($user); 
  });
  
  $this->get('/user/{email}', function($request, $response, $args) {
    //var_dump($args['email']);
    //die();
    $email = $request->getAttribute('email');
    $user = User::where('email', $email)->first();
    return $this->response->withJson($user); 
  });
  
  /*
  https://www.cloudways.com/blog/simple-rest-api-with-slim-micro-framework/
  https://arjunphp.com/creating-restful-api-slim-framework/

  $this->post('/user/{email}', function($request) {
  });

  $this->put('/user/{email}', function($request) {
  });

  $this->delete('/user/{email}', function($request) {
  });
  */

});

// Tests
$app->get('/volver', function($request, $response){
  return $response->withRedirect('/new-url', 301);
  return $response->withRedirect('/');
});


$app->get('/twig', function($request, $response){
  return $this->view->render($response, 'home.twig');
});

$app->get('/home', function($request, $response){
  return 'Home';
});