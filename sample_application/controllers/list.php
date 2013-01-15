<?php

class List_Controller extends Controller
{

	private $todo_model;

	public function __construct()
	{
		parent::__construct();

		// don't do anything if not called from an active session
		if(!$this->session->get('user', false)) exit();

		$this->todo_model = new Todo_Model();
	}


	public function get($id)
	{
		// permission check
		if($this->todo_model->user_owns_list($this->session->get('user')->id, $id))
		{
			Output::return_json($this->todo_model->get_list($id)->rows);
		}
	}


	public function add()
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


	public function checked($task_id)
	{
		if($this->todo_model->user_owns_task($this->session->get('user')->id, $task_id))
		{
			Output::return_json(array('success' => $this->todo_model->item_completed($task_id)));
		}
	}
}