<?php

use Respect\Validation\Validator as v;

// Iniciar sesión
session_start();

// Autoload
require __DIR__ . '/../vendor/autoload.php';

// Crear una aplicación Slim
$app = new \Slim\App([
  'settings' => [
    'displayErrorDetails' => true,
    'determineRouteBeforeAppMiddleware' => true,
    'db' => [
      'driver' => 'mysql',
      'host' => 'localhost',
      'database' => 'VIP-admin',
      'username' => 'root',
      'password' => 'root',
      'charset' => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix' => ''
    ]
  ],
]);

// Contenedor
$container = $app->getContainer();

// Database
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function($container) use ($capsule){
  return $capsule;
};

// Controladores
$container['validator'] = function($container){
  return new \App\Validation\Validator;
};

$container['ApiController'] = function($container){
  return new \App\Controllers\ApiController($container);
};

$container['HomeController'] = function($container){
  return new \App\Controllers\HomeController($container);
};

$container['AuthController'] = function($container){
  return new \App\Controllers\Auth\AuthController($container);
};

$container['PasswordController'] = function($container){
  return new \App\Controllers\Auth\PasswordController($container);
};

$container['csrf'] = function($container){
  return new \Slim\Csrf\Guard;
};

$container['auth'] = function($container){
  return new \App\Auth\Auth;
};

$container['flash'] = function($container){
  return new \Slim\Flash\Messages();
};

// Vistas
$container['view'] = function($container) {
  $view = new \Slim\Views\Twig(__DIR__ . '/../app/views', [
    'cache' => false, // en producción cambiar por el directorio donde se almacena
  ]);

  $view->addExtension(new \Slim\Views\TwigExtension(
    $container->router,
    $container->request->getUri()
  ));
  
  $view->getEnvironment()->addGlobal('auth', [
    'check' => $container->auth->check(),
    'user' => $container->auth->user()
  ]);
  
  $view->getEnvironment()->addGlobal('flash', $container->flash);

  return $view;
};

// Middleware
$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));

$app->add($container->csrf); 

v::with('App\\Validation\\Rules');

// Cargar rutas
require __DIR__ . '/../app/routes.php';
