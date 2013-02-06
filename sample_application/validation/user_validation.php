<?php

class User_Validation
{


	public static function try_login($value, &$message)
	{
		$message = "Invalid username/password combination";
		$model = new User_Model();

		if(sha1($value) == $model->get_password($email = Input::post('email')))
		{
			// everything's kosher, log them in
			$app =& get_mvc();
			$app->session->set('user', $model->get_user_by_email($email));
			return true;
		}

		return false;
	}


	public static function unique_username($value, &$message)
	{
		$message = sprintf("Sorry, but '%s' is already taken.  Try another name.", $value);
		$model = new User_Model();

		return !$model->user_exists($value);
	}


	public static function unique_email($value, &$message)
	{
		$message = sprintf("The email address '%s' is already in use.", $value);
		$model = new User_Model();

		return $model->get_user_by_email($value) === false;
	}
}