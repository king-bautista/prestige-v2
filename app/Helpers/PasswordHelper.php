<?php

namespace App\Helpers;

class PasswordHelper 
{
    // permited string for salting
    private static $permitedString = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    /* 	gen_salt
	*	params
	*	return random string
    */
    public static function generateSalt() {
    	return substr(str_shuffle(static::$permitedString), 0, 10);
    }

    /* gen_password
    *  params $salt, $password
	*  return $password
    */
    public static function generate($salt, $password) {		
		return bcrypt($salt.env("PEPPER_HASH").$password);
    }

}