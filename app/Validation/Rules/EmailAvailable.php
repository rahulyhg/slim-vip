<?php

namespace App\Validation\Rules;

use App\Models\User;
use Respect\Validation\Rules\AbstractRule;

class EmailAvailable extends AbstractRule
{
  public function validate($input)
  {
    // must return true or false values
    // revisar si la cantidad de registros con este email es = a 0
    return User::where('email', $input)->count() === 0;
  }
}