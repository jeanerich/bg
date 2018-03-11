<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin extends CI_Controller {
	
	  function __construct(){
        // Call the Model constructor
        parent::__construct();
		
		$this->load->helper('date');
		$this->load->helper(array('form', 'url'));
		$this->load->helper('path');
		$this->load->helper('cookie');
		$this->load->library('functions');
		$this->load->library('form_validation');
		$this->load->model('User_model');
		//$this->load->model('media_model');
		$this->load->model('red_model');
		$this->load->model('Battle_model');
		$this->load->model('Admin_model');
		$this->load->library('pagination'); 
		//$this->load->model('exchange_model');
		$this->load->helper('string');
		
    }
	
	public function index(){
		$data = array();
		$data['options'] = $this->User_model->getOptions();
		$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['userId'] = $this->User_model->getUserId();
		
		$userIds = array();
		if($data['userId'] > 0){$userIds[] = $data['userId'];}
		if(isset($data['memberId'])){$userIds[] = $data['memberId'];}
		
		$users = array();
		if($data['userId'] > 0){
			$users[] = $data['userId'];
		}
		
		if(count($users) > 0){
			$usersInfo = $this->User_model->getUsersInfo($users); 
			$data['users'] = $usersInfo['users'];
		}
		
		$data['css'] = array("admin/admin.css");
		$data['js'] = array("admin/admin.js");
		
		$data['menu_lists'] = $this->User_model->getLists(array("job_status", "country_list", "battle_categories"));
		
		
		
		$data['page_title'] = $this->functions->_e("battles", $data['dictionary']) . " | " . SITE_NAME;
		$data['template'] = "admin/home.php";
		
		$this->load->view("default_view", $data);
	}
	
	public function translation(){
		
		$data = array();
		$data['options'] = $this->User_model->getOptions();
		$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['userId'] = $this->User_model->getUserId();
		
		$userIds = array();
		if($data['userId'] > 0){$userIds[] = $data['userId'];}
		if(isset($data['memberId'])){$userIds[] = $data['memberId'];}
		
		$users = array();
		if($data['userId'] > 0){
			$users[] = $data['userId'];
		}
		
		if(count($users) > 0){
			$usersInfo = $this->User_model->getUsersInfo($users); 
			$data['users'] = $usersInfo['users'];
		}
		
		$data['css'] = array("admin/admin.css");
		$data['js'] = array("admin/admin.js");
		
		$data['menu_lists'] = $this->User_model->getLists(array("job_status", "country_list", "battle_categories"));
		
		
		$data['translations'] = $this->Admin_model->listtranslation();
		
		$data['page_title'] = $this->functions->_e("battles", $data['dictionary']) . " | " . SITE_NAME;
		$data['template'] = "admin/translation.php";
		
		$this->load->view("default_view", $data);
		
	}
	
	public function editDictionary(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->Admin_model->editDictionary());
		}
	}
	
	
}
?>