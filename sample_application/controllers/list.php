<?php

class List_Controller extends Controller_Rest
{

	private $todo_model;

	public function __construct()
	{
		parent::__construct();

		// don't do anything if not called from an active session
		if(!$this->session->get('user', false)) exit();

		$this->todo_model = new Todo_Model();
	}


	/*
		With REST controllers, method names are prefixed by the HTTP action sent by the client.  
		For example, an HTTP GET request sent to /list/items is mapped to the method get_items().
		An HTTP POST sent to /list/create is mapped to post_create(), etc.
	*/

	public function get_items($id)
	{
		// permission check
		if($this->todo_model->user_owns_list($this->session->get('user')->id, $id))
		{
			Output::return_json($this->todo_model->get_list($id)->rows);
		}
	}


	public function post_create()
	{
		$name = Input::post('name');

		if($list_id = $this->todo_model->create_list($this->session->get('user')->id, $name))
		{
			Output::return_json(array(
				'success'	=> true,
				'id'		=> $list_id));
		}
		Output::return_json(array('success' => false));
	}


	public function post_add()
	{
		$id = Input::post('list_id');
		$text = Input::post('text');

		if($this->todo_model->user_owns_list($this->session->get('user')->id, $id))
		{
			if($item_id = $this->todo_model->add_item($id, $text))
			{
				Output::return_json(array(
					'success' 	=> true,
					'id'		=> $item_id)
				);
			}
		}

		Output::return_json(array('success' => false));
	}


	public function get_checked($task_id)
	{
		if($this->todo_model->user_owns_task($this->session->get('user')->id, $task_id))
		{
			Output::return_json(array('success' => $this->todo_model->item_completed($task_id)));
		}
	}
}