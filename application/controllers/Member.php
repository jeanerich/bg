<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller {
	
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
		$this->load->helper('string');
		//$this->load->model('media_model');
		
		
    }
	
	public function index(){
		$userId = $this->User_model->getUserId();
		if($userId > 0){
			$users = $this->User_model->getUsersInfo(array($userId));
			$link = $users['users'][$userId]['link'];
			if(isset($_GET['welcome']) && $_GET['welcome']){$link .= "?welcome=true";}
			
			redirect($link);
		} else {
			redirect("/");
		}
	}

	
	/*public function profile(){
		$data = array();
		$data['userId'] = $this->User_model->getUserId();
		if($data['userId'] > 0 || $this->uri->segment(3)){
			$this->User_model->setUserDefault();
			$data['dictionary'] = $this->User_model->getDictionary();
			
			
			$data['memberId'] = $data['userId'];
			$users[] = $data['userId'];
			if($this->uri->segment(3)){
				$data['memberId'] = (int)$this->uri->segment(3);
				$users[] = $data['memberId'];
				
				$users = array_unique($users);
				
			}
			
				$data['users'] = $this->User_model->getUsersInfo($users);
			if($data['userId'] > 0){
				$data['userInfo'] = $data['users']['users'][$data['userId']];
			}
			$data['memberInfo'] = $data['users']['users'][$data['memberId']];
			$data['memberImages'] = $data['users']['images'];
			$data['menu_lists'] = $this->User_model->getLists(array("job_status", "country_list", "battle_categories"));
			$data['template'] = "member/profile.php";
			$data['css'] = array("member/profile.css");
			$data['js'] = array("member/profile.js");
			
			$data['battle_id'] = 0;
			
			$data['page_title'] = $data['memberInfo']['name'] . " | " . SITE_NAME;
			$this->load->view("default_view", $data);
			
		
		} else {
			redirect(HOME);	
		}
		
	}*/
	
	public function home(){
		$data['options'] = $this->User_model->getOptions();
		$userId = (int)$this->User_model->getUserId();
		$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
		if(($this->uri->segment(3) && $this->uri->segment(4)) || $userId > 0){ 
			
			if($this->uri->segment(3)){
				$data['memberId'] = (int)$this->uri->segment(3);
			} else {
				$data['memberId'] = $userId;	
			}
			
			$users = array();
			 
			$data['userId'] = $userId;
			
			if($userId > 0){$users[] = $data['userId'];}
			$users[] = $data['memberId'];
			$users = array_unique($users);
			
			$link = $this->uri->segment(4);
			$userInfo = $this->User_model->getUsersInfo($users);
			$data['users'] = $userInfo['users'];
			$data['images'] = $userInfo['images'];
			if($userId > 0){
			$data['userInfo'] = $data['users'][$data['userId']];
			}
			if(isset($data['users'][$data['memberId']])){
				$data['memberInfo'] = $data['users'][$data['memberId']];
				
				$data['menu_lists'] = $this->User_model->getLists(array("country_list", "US_states_list", "canadian_provinces_list", "industries", "skill_set", "months"));
				
				$data['profile_type'] = 'member';
				$data['editable'] = false;
				
				$data['timeline'] = $this->User_model->getBusinessTimeline($data['memberId']);
				$data['tech_skills'] = $this->User_model->getTechSkills($data['memberId']);
				$data['links'] = $this->User_model->getUserLinks($data['memberId']);
				
				$data['js'][] = "member/profile.js";
				if($data['userId'] == $data['memberId']){
					$data['editable'] = true;
					//$data['js'][] = "common/uploadifive/jquery.uploadifive.min.js";
					$data['js'][] = "member/edit_profile.js";
				}
				
				/*if($data['memberId'] == $data['userId']){$data['member_businesses'] = $data['businesses'];} else {$data['member_businesses'] = $this->User_model->listusercompanies($data['memberId']); */
				$data['is_following'] = $this->User_model->is_following($data['userId'], $data['memberId']);
	
				$data['js'][] = "main.js";
				$data['js'][] = "common/Chart.js";
				$data['js'][] = "common/form_validate.js";
				$data['css'][] = "member/profile.css";
				$data['options'] = $this->User_model->getOptions();
				$data['menu_option'] = 'home';
				
				$data['template'] = "profile/profile.php"; 
				$data['sub_template'] = "member/profile_body.php";
				
				$data['opengraph']['title'] = $data['users'][$data['memberId']]['name'] . " - Profile | " . SITE_NAME;;
				$data['opengraph']['description'] = $data['users'][$data['memberId']]['description'];
				$data['opengraph']['image'] = site_url() . "assets/images/facebook-card-2.jpg";
				if($data['users'][$data['memberId']]['avatar_id'] > 0){$data['opengraph']['image'] = $data['images'][$data['users'][$data['memberId']]['avatar_id']]['sizes']['square'];}
				
				$data['page_title'] = $data['users'][$data['memberId']]['name'] . " - Profile | " . SITE_NAME;
				$this->load->view("default_view", $data);
			} else { //redirect(HOME);
			}
			
		} else {
			//redirect(HOME);
		}
		
	}
	
	public function followers(){
		$data['options'] = $this->User_model->getOptions();
		$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
		if($this->uri->segment(3) && $this->uri->segment(4)){
			$data['memberId'] = (int)$this->uri->segment(3);
			
			$users = array();
			$userId = (int)$this->User_model->getUserId(); 
			$data['userId'] = $userId;
			
			$users[] = $data['userId'];
			$users[] = $data['memberId'];
			$users = array_unique($users);
			
			
			
			$link = $this->uri->segment(4);
			$userInfo = $this->User_model->getUsersInfo($users);
			$data['users'] = $userInfo['users'];
			$data['images'] = $userInfo['images'];
			if($data['userId'] > 0){
			$data['userInfo'] = $data['users'][$data['userId']];}
			$data['memberInfo'] = $data['users'][$data['memberId']];
			
			$data['profile_type'] = 'member';
			$data['sub_template'] = "profile/sub_followers.php";
			$data['editable'] = false;
			
			if($data['userId'] == $data['memberId']){
				$data['editable'] = true;
				//$data['js'][] = "common/uploadifive/jquery.uploadifive.min.js";
			}
			
			
			$data['followers'] = $this->User_model->getFollowers($data['memberId']);
			$data['js'][] = "main.js";
			
			
			$data['css'][] = "member/profile.css";
			$data['options'] = $this->User_model->getOptions();
			
			
			$data['opengraph']['title'] = $data['users'][$data['memberId']]['name'] . " - Profile | " . SITE_NAME;;
			$data['opengraph']['description'] = $data['users'][$data['memberId']]['description'];
			$data['opengraph']['image'] = site_url() . "assets/images/facebook-card-2.jpg";
			if($data['users'][$data['memberId']]['avatar_id'] > 0){$data['opengraph']['image'] = $data['images'][$data['users'][$data['memberId']]['avatar_id']]['sizes']['square'];}
			
			$data['template'] = "profile/profile.php"; 
			$data['menu_option'] = 'followers';
			$data['page_title'] = $data['users'][$data['memberId']]['name'] . " - Followers | " . SITE_NAME;
			$this->load->view("default_view", $data);
		} else {
			redirect("/");
		}
	}
		public function following(){
			$data['options'] = $this->User_model->getOptions();
			$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
		if($this->uri->segment(3) && $this->uri->segment(4)){
			$data['memberId'] = (int)$this->uri->segment(3);
			
			$users = array();
			$userId = (int)$this->User_model->getUserId(); 
			$data['userId'] = $userId;
			
			$users[] = $data['userId'];
			$users[] = $data['memberId'];
			$users = array_unique($users);
			
			
			
			$link = $this->uri->segment(4);
			$userInfo = $this->User_model->getUsersInfo($users);
			$data['users'] = $userInfo['users'];
			$data['images'] = $userInfo['images'];
			if($data['userId'] > 0){
			$data['userInfo'] = $data['users'][$data['userId']];}
			$data['memberInfo'] = $data['users'][$data['memberId']];
			
			$data['profile_type'] = 'member';
			$data['sub_template'] = "profile/sub_following.php";
			$data['editable'] = false;
			
			if($data['userId'] == $data['memberId']){
				$data['editable'] = true;
				//$data['js'][] = "common/uploadifive/jquery.uploadifive.min.js";
			}
			
			$data['following'] = $this->User_model->getFollowing($data['memberId']);
			$data['js'][] = "main.js";
			
			$data['css'][] = "member/profile.css";
			$data['options'] = $this->User_model->getOptions();
			$data['menu_option'] = 'following';
			$data['template'] = "profile/profile.php"; 
			
			
			$data['opengraph']['title'] = $data['users'][$data['memberId']]['name'] . " - Profile | " . SITE_NAME;;
			$data['opengraph']['description'] = $data['users'][$data['memberId']]['description'];
			$data['opengraph']['image'] = site_url() . "assets/images/facebook-card-2.jpg";
			if($data['users'][$data['memberId']]['avatar_id'] > 0){$data['opengraph']['image'] = $data['images'][$data['users'][$data['memberId']]['avatar_id']]['sizes']['square'];}
			
			
			$data['page_title'] = $data['users'][$data['memberId']]['name'] . " - Following | " . SITE_NAME;
			$this->load->view("default_view", $data);
		} else {
			redirect("/");
		}
	}
	
	public function portfolio(){
		$data['options'] = $this->User_model->getOptions();
		$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
		if($this->uri->segment(3) && $this->uri->segment(4)){
			$data['memberId'] = (int)$this->uri->segment(3);
			
			$users = array();
			$userId = (int)$this->User_model->getUserId(); 
			$data['userId'] = $userId;
			
			$users[] = $data['userId'];
			$users[] = $data['memberId'];
			$users = array_unique($users);
			
			
			
			$link = $this->uri->segment(4);
			$userInfo = $this->User_model->getUsersInfo($users);
			$data['users'] = $userInfo['users'];
			$data['images'] = $userInfo['images'];
			if($data['userId'] > 0){
			$data['userInfo'] = $data['users'][$data['userId']];}
			$data['memberInfo'] = $data['users'][$data['memberId']];
			
			$data['profile_type'] = 'member';
			$data['sub_template'] = "profile/portfolio.php";
			$data['editable'] = false;
			
			if($data['userId'] == $data['memberId']){
				$data['editable'] = true;
				//$data['js'][] = "common/uploadifive/jquery.uploadifive.min.js";
			}
			
			
			$data['followers'] = $this->User_model->getFollowers($data['memberId']);
			$data['js'][] = "main.js";
			$data['js'][] = "gallery.js";
			if($data['editable']){
				$data['js'][] = "common/jquery-ui.js";
				$data['js'][] = "member/edit_gallery.js";
			}
			
			//$data['js'][] = "common/form_validate.js";
			$data['css'][] = "member/profile.css";
			$data['options'] = $this->User_model->getOptions();
			
			
			$data['opengraph']['title'] = $data['users'][$data['memberId']]['name'] . " - Profile | " . SITE_NAME;;
			$data['opengraph']['description'] = $data['users'][$data['memberId']]['description'];
			$data['opengraph']['image'] = site_url() . "assets/images/facebook-card-2.jpg";
			if($data['users'][$data['memberId']]['avatar_id'] > 0){$data['opengraph']['image'] = $data['images'][$data['users'][$data['memberId']]['avatar_id']]['sizes']['square'];}
			
			$data['template'] = "profile/profile.php"; 
			$data['menu_option'] = 'portfolio';
			$data['page_title'] = $data['users'][$data['memberId']]['name'] . " - Followers | " . SITE_NAME;
			$this->load->view("default_view", $data);
		} else {
			redirect("/");
		}
	}
	
	public function invites(){
		$data['options'] = $this->User_model->getOptions();
		$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
			
			$users = array();
			$userId = (int)$this->User_model->getUserId(); 
			$data['userId'] = $userId;
			if($userId > 0){
			$users[] = $data['userId'];
			$users = array_unique($users);
			
			$userInfo = $this->User_model->getUsersInfo($users);
			$data['users'] = $userInfo['users'];
			$data['images'] = $userInfo['images'];
			if($data['userId'] > 0){
			$data['userInfo'] = $data['users'][$data['userId']];}
			$data['invites'] = $this->User_model->listInvites($userId);
			
			$data['editable'] = true;
			
			$data['css'][] = "member/profile.css";
			$data['js'][] = "member/manageinvites.js";
			$data['options'] = $this->User_model->getOptions();
			
			$data['template'] = "profile/invites.php"; 
			$data['menu_option'] = 'invites';
			$data['page_title'] = $data['users'][$data['userId']]['name'] . " - Invites | " . SITE_NAME;
			$this->load->view("default_view", $data);
			} else {
				redirect(HOME);	
			}
	}
	
	public function warriors(){
		$data['options'] = $this->User_model->getOptions();
		$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
		
		$users = array();
		$userId = (int)$this->User_model->getUserId(); 
		$data['userId'] = $userId;
		if($userId > 0){
			$users[] = $data['userId'];
			$users = array_unique($users);
			$userInfo = $this->User_model->getUsersInfo($users);
			$data['users'] = $userInfo['users'];
			$data['images'] = $userInfo['images'];
		}
		
		$data['list_users'] = $this->User_model->listUsers(0);
		
		$data['js'][] = "member/warriors.js";
		$data['css'][] = "member/profile.css";
		$data['options'] = $this->User_model->getOptions();
		
		$data['page_title'] = $this->functions->_e("warriors", $data['dictionary']) . " | " . SITE_NAME;
		
		$data['template'] = "member/warriors.php"; 
		
		$this->load->view("default_view", $data);
		
	}
	
	public function includeUserGallery(){
		$userId = $this->User_model->getUserId();
		if($userId > 0 || $this->uri->segment(3)){
			$dictionary = $this->User_model->getDictionary();
			$memberId = $userId; 
			if($this->uri->segment(3)){$memberId = (int)$this->uri->segment(3);}
			$editable = false;
			
			if($memberId == $userId){$editable = true;}
			$users_array[] = $memberId; $users_array[] = $userId; $users_array = array_unique($users_array);
			
			$users = $this->User_model->getUsersInfo($users_array);
			
			$gallery = $this->User_model->getUserGallery($memberId);
			
			$gallery_images = $this->User_model->getImagesByIds($gallery);
			
			 include(DIR_TEMPLATES . "profile/portfolio-gallery.php");
		}
		
	}
	
}
