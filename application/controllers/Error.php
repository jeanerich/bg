<?php 
class Error extends CI_Controller {

	public function __construct(){
		parent::__construct(); 
	} 
	
	 public function error_404()
		{
			$this->output->set_status_header('404');
			echo "404 - not found";
		}
	} 

?>