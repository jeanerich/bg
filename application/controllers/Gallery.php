<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends CI_Controller {
	
	function __construct(){
        // Call the Model constructor
        parent::__construct();
		
		$this->load->helper('date');
		$this->load->library('functions');
		$this->load->helper(array('form', 'url'));
		$this->load->helper('path');
		$this->load->helper('cookie');
		$this->load->helper('html');
		$this->load->model('User_model');
		$this->load->model('exchange_model');
		$this->load->helper('string');
		//$this->load->model('media_model');
		
		
    }
	
	public function index(){
		redirect("/");	
	}

	
	
	public function image(){
		$userId = (int)$this->User_model->getUserId();
		$data['options'] = $this->User_model->getOptions();
		$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
		if(($this->uri->segment(3) && $this->uri->segment(4))){ 
			
			$data['userId'] = $userId;
			$users[] = $data['userId'];
			
			$data['image_id'] = (int)$this->uri->segment(3);
			$data['token'] = $this->uri->segment(4);
			$users = array();
			 $users[] = $userId;
			$data['gallery_images'] = $this->User_model->getImagesByIds(array($data['image_id']));
			
			$data['image'] = $data['gallery_images'][$data['image_id']];
			
			if($data['image']['token'] == $data['token']){
				$data['memberId'] = $data['image']['user_id'];
				$users[] = $data['memberId'];
			}
			
			$users = array_unique($users);
			
			
			$usersInfo = $this->User_model->getUsersInfo($users); 
			$data['users'] = $usersInfo['users'];
			$data['users']['images'] = $usersInfo['images'];
			if($userId > 0){$data['userInfo'] = $data['users'][$data['userId']]; }
			//$data['users'] = $usersInfo;
			
			if($userId > 0){
				$data['userInfo'] = $usersInfo['users'][$userId];
			}
			$data['memberInfo'] = $usersInfo['users'][$data['memberId']];
			
		
			$data['menu_lists'] = $this->User_model->getLists(array("country_list", "US_states_list", "canadian_provinces_list", "industries", "skill_set", "months"));
			
			$data['editable'] = false;
			if($userId > 0 && $userId == $data['memberId']){
				$data['editable'] = true;	
			}
			
			$data['js'][] = "main.js";
			$data['js'][] = "common/form_validate.js";
			$data['js'][] = "gallery/gallery-images.js";
			
			if($data['editable']){
				$data['js'][] = "gallery/edit-image.js";
			}
			
			$data['css'][] = "gallery/gallery-images.css";
			$data['js'][] = "common/imageviewer.min.js";
			$data['css'][] = "common/imageviewer.css";
			
			
			$data['template'] = "gallery/image.php"; 
			
			$data['opengraph']['title'] = $data['image']['title'] . " - Gallery | " . SITE_NAME;
			$data['opengraph']['description'] = $data['image']['description'];
			$ogimage = $data['image']['sizes']['thumb'];
			if(isset($data['image']['sizes']['card'])){$ogimage = $data['image']['sizes']['card'];}
			$data['opengraph']['image'] = $ogimage;
			
			$ogtitle = $data['image']['title'];
			if(strlen($ogtitle) < 1){$ogtitle = $this->functions->_e("untitled", $data['dictionary']);}
			
			$data['page_title'] = $data['image']['title'] . " - " . $this->functions->_e("gallery", $data['dictionary']) . " | " . SITE_NAME;
			$this->load->view("default_view", $data);
		} else { //redirect(HOME);
		}
		
	
		
	}
	
	public function getThread(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$data['dictionary'] = $this->User_model->getDictionary();
			$data['userId'] = $this->User_model->getUserId();
			$offset = 0; if(isset($_POST['offset'])){$offset = (int)$_POST['offset'];}
			$data['threads'] = $this->exchange_model->getThread($_GET['id'], $_GET['token'], $offset);	
			
			$this->load->view("ajax/list_thread_view", $data);
		}
	}
	
	public function deleteMessage(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->exchange_model->deleteMessage());	
		}
	}
	
}
