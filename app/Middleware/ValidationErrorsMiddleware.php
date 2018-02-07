<?php

namespace App\Middleware;

class ValidationErrorsMiddleware extends Middleware
{
  public function __invoke($request, $response, $next)
  {
    /**
    * To prevent Notice: Undefined index: old in app/Middleware/OldInputMiddleware.php on line 16
    */
    if(empty($_SESSION['errors'])){
      $_SESSION['errors'] = true;
    }

    $this->container->view->getEnvironment()->addGlobal('errors', $_SESSION['errors']);
    unset($_SESSION['errors']);

    $response = $next($request, $response);
    return $response;
  }
}