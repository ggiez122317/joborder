<?php

namespace App\Libraries;

class PasswordHelper
{

    public static function _isValidPassword($password) 
    {
        $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d\W_]{8,}$/';
        return preg_match($pattern, $password);
    }

}
