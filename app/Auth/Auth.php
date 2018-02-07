<?php

namespace App\Auth;

use App\Models\User;

class Auth {
  public function user()
  {
    /**
    * To prevent Notice: Undefined index
    */
    if( isset($_SESSION['user']) ){
      return User::find($_SESSION['user']);
    }
  }

  public function check()
  {
    return isset($_SESSION['user']);
  }

  public function attemp($email, $password)
  {
    // grab the user
    $user = User::where('email', $email)->first();

    // if !user return false
    if( !$user ){
      return false;
    }

    // verify password
    if( password_verify($password, $user->password)){
      // set into session
      $_SESSION['user'] = $user->id;
      return true;
    }

    return false;
  }

  public function logout()
  {
    if( isset($_SESSION['user']) ){
      unset($_SESSION['user']);
    }
  }
}
