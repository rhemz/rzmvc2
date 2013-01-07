<?php

class Router
{
  private $incoming;
	private $path;
	private $index = 0;
	private $config;
	
	private $controller_base;
	private $controller_name;
	private $controller_obj;
	private $action;

	public function __construct($uri)
	{
		$this->incoming = $uri;

		$this->config =& Config::get_instance();
		$this->config->load(array('paths', 'routes'));
		
		$this->controller_base = APPLICATION_PATH . $this->config->get('paths.controllers') . DIRECTORY_SEPARATOR;
	}


	public function check_route()
	{
		// check for predefined route first.  todo: revise for arguments in URI segments
		if(array_key_exists($this->incoming, ($mapping = $this->config->get('routes.mappings'))))
		{
			$this->incoming = $mapping[$uri];
		}

		// map URI to controller
		$this->path = explode('/', trim(substr($this->incoming, 1), '/'));

		foreach($this->path as $segment)
		{
			$this->index++;
			if(!strlen($segment))
			{
				$segment = $this->config->get('routes.default_controller');
			}
			else if(is_link($this->controller_base . $segment) || is_dir($this->controller_base . $segment))
			{
				$this->controller_base .= $segment . DIRECTORY_SEPARATOR;
			}

			if(is_file($this->controller_base . $segment . PHP_EXT))
			{
				$this->controller_name = sprintf("%s_%s", $segment, $this->config->get('routes.controller_suffix'));
				$this->controller = new $this->controller_name();

				$this->action = $this->index == sizeof($this->path)
					? $this->config->get('routes.default_function') 
					: $this->path[$this->index];

				return (isset($this->controller) && is_object($this->controller) && $this->controller->_has_method($this->action));
			}
		}

		return false;
	}


	public function execute_route()
	{
		sizeof($args = array_slice($this->path, ++$this->index))
			? call_user_func_array(array($this->controller, $this->action), $args)
			: $this->controller->{$this->action}();
	}


	public function show_404()
	{
		header(HTTP_Status_Code::Not_Found);

		// make generic controller and show 404 page
		$controller = new Controller();
		$controller->load_view('common/404');
	}

}
