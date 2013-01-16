<?php


class Account_Controller extends Controller
{
	private $user_model;

	public function __construct()
	{
		parent::__construct();
	}


	public function create()
	{
		$val = new Validation();

		$val->register('username', 'Your username')
			->rule('required')
			->rule('min_length', 4)
			->rule('max_length', 20)
			->rule('custom', 'User_Validation::unique_username');

		$val->register('email', 'Your email address')
			->rule('required')
			->rule('max_length', 90)
			->rule('valid_email')
			->rule('custom', 'User_Validation::unique_email');

		$val->register('password1', 'Password')
			->rule('required')
			->rule('min_length', 6)
			->rule('max_length', 40)
			->rule('equals', Input::post('password2'))
			->custom_message('equals', 'The passwords must match');

		$val->register('password2', 'Password confirmation')
			->rule('required')
			->rule('min_length', 6)
			->rule('max_length', 40)
			->rule('equals', Input::post('password1'))
			->custom_message('equals', 'The passwords must match');


		if(!$val->validate())
		{
			$this->load_view('account/create', array('val' => $val));
		}
		else
		{
			$this->user_model = new User_Model();
			
			$username = $val->value('username');
			$email = $val->value('email');
			$password = $val->value('password2');

			if($id = $this->user_model->create_user($username, $email, $password))
			{
				$this->load_view('account/created', array('username' => $username));
			}
			else
			{
				$this->load_view('common/error', array('message' => 'registering your account'));
			}
			
		}
	}


	public function login()
	{
		// if they're already logged in, send them back home
		if($this->session->get('user'))
		{
			Output::redirect('/');
			return;
		}

		$val = new Validation();

		$val->register('email', 'Your email')
			->rule('required')
			->rule('max_length', 90);

		$val->register('password', 'Your password')
			->rule('required')
			->rule('custom', 'User_Validation::try_login');


		if(!$val->validate())
		{
			$this->load_view('login', array('val' => $val));
		}
		else
		{
			Output::redirect('/');
		}
		
	}


	public function logout()
	{
		$this->session->set('user', null);
		Output::redirect('/');
	}
}