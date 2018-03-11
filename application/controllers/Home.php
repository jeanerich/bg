<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	
	function __construct(){
        // Call the Model constructor
        parent::__construct();
		
		$this->load->helper('date');
		$this->load->library('Functions');
		$this->load->helper(array('form', 'url'));
		$this->load->helper('path');
		$this->load->helper('cookie');
		$this->load->helper('html');
		$this->load->model('User_model');
		$this->load->model('Battle_model');
		//$this->load->model('media_model');
		
		
    }
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
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
		
		$data['opengraph']['image'] = site_url() . "assets/temp_images/battle-gallery-card.jpg";
		$data['opengraph']['title'] = "Battle Gallery";
		$data['opengraph']['description'] = "Launching soon...";
		$data['opengraph']['url'] = site_url();
		
		$data['css'] = array("battles/battles-cover.css", "common/home.css");
		$data['js'] = array("battle/single-battle.js");
		
		$data['menu_lists'] = $this->User_model->getLists(array("job_status", "country_list", "battle_categories"));
		
		$data['ongoing_battles'] = $this->Battle_model->getBattles($data['userId'], "ongoing");
		$data['past_battles'] = $this->Battle_model->getBattles($data['userId'], "past");
		$data['future_battles'] = $this->Battle_model->getBattles($data['userId'], "future");
		
		$data['page_title'] = $this->functions->_e("battles", $data['dictionary']) . " | " . SITE_NAME;
		$data['template'] = "home/home.php";
		
		$this->load->view("default_view", $data);
	}
}


