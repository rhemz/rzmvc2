<?php

class Home_Controller extends Controller
{

	private $todo_model;

	public function __construct()
	{
		parent::__construct();
	}


	public function index()
	{
		if(!$this->session->get('user', false))
		{
			Output::redirect('/login');
		}


		// load the dealie
		$this->todo_model = new Todo_Model();

		$this->load_view('home', array(
			'lists' => $this->todo_model->get_lists($this->session->get('user')->id)
		));
	}

}
