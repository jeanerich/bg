<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	
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
		$this->load->model('Battle_model');
		$this->load->model('exchange_model');
		$this->load->helper('string');
		$this->load->library('pagination');
		
		
    }
	
	public function index(){
		redirect("/");	
	}

	
	public function user_register(){
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['userId'] = $this->User_model->getUserId();
		if($data['userId'] > 0){
			redirect("/"); 	
		}
		$data['template'] = DIR_TEMPLATES . "user/user_register.php";
		$this->load->view('ajax_modal_view', $data);	
	}
	
	
	
	public function user_reset_password(){
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['userId'] = $this->User_model->getUserId();
		if($data['userId'] > 0){
			redirect("/"); 	
		}
		$data['template'] = DIR_TEMPLATES . "user/user_reset_password.php";
		$this->load->view('ajax_modal_view', $data);	
	}
	
	public function user_login(){
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['userId'] = $this->User_model->getUserId();
		if($data['userId'] > 0){
			redirect("/"); 	
		}
		$data['template'] = DIR_TEMPLATES . "user/user_login.php";
		$this->load->view('ajax_modal_view', $data);	
	}
	
	public function user_request_email(){
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['userId'] = $this->User_model->getUserId();
		if($data['userId']  > 0){
			$data['template'] = DIR_TEMPLATES . "user/user_request_email.php";
			$this->load->view('ajax_modal_view', $data);	
		}
	}
	
	public function add_battle_hero_image(){
		$data['userId'] = $this->User_model->getUserId();
		$data['dictionary'] = $this->User_model->getDictionary();
		if($data['userId'] > 0){
			$data['template'] = DIR_TEMPLATES . "user/upload_image.php";
			$data['image_type'] = 'battle_hero';
			$data['oncomplete'] = "registerHeroImage";
			if($this->uri->segment(3)){$data['user_id'] = (int)$this->uri->segment(3);}
			
			$data['image_library'] = $this->User_model->getUserImages($data['userId']);
			$data['multiple_images'] = false;
			$data['page_title'] = $this->functions->_e("Modify Hero Image", $data['dictionary']);
			$this->load->view('ajax_modal_view', $data);	
		}
		
	}
	
	public function add_battle_card_image(){
		$data['userId'] = $this->User_model->getUserId();
		$data['dictionary'] = $this->User_model->getDictionary();
		if($data['userId'] > 0){
			$data['template'] = DIR_TEMPLATES . "user/upload_image.php";
			$data['image_type'] = 'battle_card';
			$data['oncomplete'] = "registerBattleImage";
			if($this->uri->segment(3)){$data['user_id'] = (int)$this->uri->segment(3);}
			
			$data['image_library'] = $this->User_model->getUserImages($data['userId']);
			$data['multiple_images'] = false;
			$data['page_title'] = $this->functions->_e("Modify Battle Card Image", $data['dictionary']);
			$this->load->view('ajax_modal_view', $data);	
		}
		
	}
	
	public function add_hero_image(){
		$data['userId'] = $this->User_model->getUserId();
		$data['dictionary'] = $this->User_model->getDictionary();
		if($data['userId'] > 0){
			$data['template'] = DIR_TEMPLATES . "user/upload_image.php";
			$data['image_type'] = 'hero';
			$data['oncomplete'] = "registerHeroImage";
			$data['user_id'] = 0;
			if($this->uri->segment(3)){$data['user_id'] = (int)$this->uri->segment(3);}
			$data['image_library'] = $this->User_model->getUserImages($data['user_id']);
			$data['multiple_images'] = false;
			$data['page_title'] = $this->functions->_e("Modify Hero Image", $data['dictionary']);
			$this->load->view('ajax_modal_view', $data);	
		}
		
	}
	
	public function add_team_image_card(){
		$data['userId'] = $this->User_model->getUserId();
		$data['dictionary'] = $this->User_model->getDictionary();
		if($data['userId'] > 0){
			$data['template'] = DIR_TEMPLATES . "user/upload_image.php";
			$data['image_type'] = 'team_card';
			$data['oncomplete'] = "registerTeamImage";
			$data['user_id'] = 0;
			if($this->uri->segment(3)){$data['user_id'] = (int)$this->uri->segment(3);}
			$data['image_library'] = $this->User_model->getUserImages($data['userId']);
			$data['multiple_images'] = false;
			$data['page_title'] = $this->functions->_e("modify team image", $data['dictionary']);
			$this->load->view('ajax_modal_view', $data);	
		}
		
	}
	
	public function modify_profile_image(){
		$data['userId'] = $this->User_model->getUserId();
		$data['dictionary'] = $this->User_model->getDictionary();
		if($data['userId'] > 0){
			$data['template'] = DIR_TEMPLATES . "user/upload_image.php";
			$data['image_type'] = 'avatar';
			$data['oncomplete'] = "registerProfileImage";
			$data['user_id'] = 0;
			if($this->uri->segment(3)){$data['user_id'] = (int)$this->uri->segment(3);}
			$data['image_library'] = $this->User_model->getUserImages($data['user_id']);
			$data['multiple_images'] = false;
			$data['page_title'] = $this->functions->_e("Modify Profile Image", $data['dictionary']); 
			$this->load->view('ajax_modal_view', $data);	
		}
	}
	
	public function add_gallery_image(){
		$data['userId'] = $this->User_model->getUserId();
		$data['dictionary'] = $this->User_model->getDictionary();
		if($data['userId'] > 0){
			$data['template'] = DIR_TEMPLATES . "user/upload_image.php";
			$data['image_type'] = 'gallery';
			$data['oncomplete'] = "registerGalleryImage";
			$data['user_id'] = 0;
			if($this->uri->segment(3)){$data['user_id'] = (int)$this->uri->segment(3);}
			$data['image_library'] = $this->User_model->getUserImages($data['userId'], 'gallery'); 
			$data['multiple_images'] = true;
			$data['page_title'] = $this->functions->_e("Add Gallery Image", $data['dictionary']);
			$this->load->view('ajax_modal_view', $data);	
		}
		
	}
	
	public function add_battle_image(){
		$data['userId'] = $this->User_model->getUserId();
		$data['dictionary'] = $this->User_model->getDictionary();
		if($data['userId'] > 0){
			$data['template'] = DIR_TEMPLATES . "battles/upload_battle_image.php";
			$data['image_type'] = 'gallery';
			$data['oncomplete'] = "registerBattleImage";
			$data['user_id'] = 0;
			if($this->uri->segment(3)){$data['user_id'] = (int)$this->uri->segment(3);}
			$data['image_library'] = $this->User_model->getUserImages($data['userId'], 'gallery'); 
			$data['multiple_images'] = false;
			$data['page_title'] = $this->functions->_e("submit image", $data['dictionary']);
			$this->load->view('ajax_modal_view', $data);	
		}
		
	}
	
	public function userinvite(){
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['userId'] = $this->User_model->getUserId();
		if($data['userId'] > 0){
			$data['template'] = DIR_TEMPLATES . "user/user_invite.php";
			$this->load->view('ajax_modal_view', $data);	
		} else {
			redirect("/"); 	
		}
		
	}
	
	public function ic(){
		$this->User_model->setUserDefault();
		
			
			$users = array();
			$userId = (int)$this->User_model->getUserId(); 
			
			$data['userId'] = $userId;
			if($this->uri->segment(3) && $this->uri->segment(4)){
				$users[] = $data['userId'];
				$users = array_unique($users);
				
				$inviteId = (int)$this->uri->segment(3);
				$token = $this->uri->segment(4);
				
				$data['is_invite'] = true;
				$data['invite'] = $this->User_model->checkInvite($inviteId, $token);
				
				$data['dictionary'] = $this->User_model->getDictionary();
				
				$data['editable'] = true;
				
				$data['css'][] = "member/profile.css";
				$data['js'][] = "member/manageinvites.js";
				$data['options'] = $this->User_model->getOptions();
				
				$data['opengraph']['image'] = site_url() . "assets/temp_images/battle-gallery-card.jpg";
				$data['opengraph']['title'] = "VIP invitation to " . $data['invite']['invite']['first_name'] . " to join the Battle Gallery";
				$data['opengraph']['description'] = "This is a private VIP invitation to " . $data['invite']['invite']['first_name'] . " " .  $data['invite']['invite']['last_name'] . ".";
				$data['opengraph']['url'] = site_url() . "user/ic/{$inviteId}/{$token}/";
				
				$data['template'] = "profile/pre_approve_invites.php"; 
				$data['page_title'] = "Invitation | " . SITE_NAME;
				$data['targetUrl'] = site_url() . "user/ic2/{$inviteId}/{$token}/"; 
				
				$this->load->view("default_view", $data);
				
				
			} else {
				redirect(HOME);	
			}	
	}
	
	public function ic2(){
		$this->User_model->setUserDefault();
		
			
			$users = array();
			$userId = (int)$this->User_model->getUserId(); 
			
			$data['userId'] = $userId;
			if($this->uri->segment(3) && $this->uri->segment(4)){
				$users[] = $data['userId'];
				$users = array_unique($users);
				
				$inviteId = (int)$this->uri->segment(3);
				$token = $this->uri->segment(4);
				
				
				if($data['userId'] > 0){
					redirect(HOME);
				}
				
				$data['is_invite'] = 1;
				$data['invite'] = $this->User_model->checkInvite($inviteId, $token);
				
				$data['dictionary'] = $this->User_model->getDictionary();
				
				$data['editable'] = true;
				
				$data['options'] = $this->User_model->getOptions();
				$this->User_model->setbeta($data['options']);
				
				$data['css'][] = "common/darkform.css";
				$data['css'][] = "member/profile.css";
				$data['css'][] = "member/invitation.css";
				//$data['js'][] = "member/manageinvites.js";
				$data['js'][] = "member/invitation.js";
				$data['options'] = $this->User_model->getOptions();
				
				$data['template'] = "profile/confirm_invites.php"; 
				$data['page_title'] = "Invitation | " . SITE_NAME;
				$this->load->view("default_view", $data);
				
				if(!isset($_COOKIE['user_lang'])){$targetUrl = site_url() . "user/ic/{$inviteId}/{$token}/"; redirect($targetUrl);}
			} else {
				redirect(HOME);	
			}	
	}
	
	public function notifications(){
		
		
		$data = array();
		$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['page_title'] = SITE_NAME;
		$data['userId'] = (int)$this->User_model->getUserId();
		
		$users[] = $data['userId'];
		if($data['userId'] > 0){
			$data['editable'] = false;
			
			if(count($users) > 0){
				$usersInfo = $this->User_model->getUsersInfo($users); 
				$data['users'] = $usersInfo['users'];
			}
			
			$data['menu_lists'] = $this->User_model->getLists(array("job_status", "country_list"));
			$data['template'] = "user/view_notifications.php";
			$data['css'] = array("users/listing.css");
			$data['js'] = array("battle/battle.js");
			
			$offset = 0;
			if($this->uri->segment(3)){$offset = (int)$this->uri->segment(3);}
			
			$data['notifications'] = $this->User_model->getUserNotifications($data['userId'], $offset, 20);
			
			
			$this->load->view("default_view", $data);
		} else {
			redirect(HOME);	
		}
	
	}
	
	public function messages(){
		
		
		$data = array();
		$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['page_title'] = "Notifications | " . SITE_NAME;
		$data['userId'] = $this->User_model->getUserId();
		
		$users[] = $data['userId'];
		if($data['userId'] > 0){
			$data['editable'] = false;
			
			if(count($users) > 0){
				$usersInfo = $this->User_model->getUsersInfo($users); 
				$data['users'] = $usersInfo['users'];
			}
			
			$data['menu_lists'] = $this->User_model->getLists(array("job_status", "country_list"));
			$data['template'] = "user/view_messages.php";
			$data['css'] = array("users/listing.css");
			//$data['js'] = array("battle/battle.js");
			
			$data['action'] = "inbox";
			if(isset($_GET['action']) && $_GET['action'] == 'sent'){$data['action'] = "sent";}
			
			$offset = 0;
			if($this->uri->segment(3)){$offset = (int)$this->uri->segment(3);}
			$data['per_page'] = 20;
			
			$data['messages'] = $this->exchange_model->getUserMessages($data['userId'], $offset, $data['per_page'], $data['action']);
			
			
			$this->load->view("default_view", $data);
		} else {
			redirect(HOME);	
		}
	
	}
	
	public function message(){
		
		
		$data = array();
		
		$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['page_title'] = "Notifications | " . SITE_NAME;
		$data['userId'] = $this->User_model->getUserId();
		
		$users[] = $data['userId'];
		
		if(count($users) > 0){
				$usersInfo = $this->User_model->getUsersInfo($users); 
				$data['users'] = $usersInfo['users'];
			}
		
		if($data['userId'] > 0 && $this->uri->segment(3) && $this->uri->segment(4) && $this->uri->segment(5)){
			
			$data['action'] = "inbox"; 
			if($this->uri->segment(3) && in_array($this->uri->segment(3), array("inbox","sent"))){
				$data['action'] = 	$this->uri->segment(3);
			}
			
			$data['messageId'] = (int)$this->uri->segment(4);
			$data['messageToken'] = (int)$this->uri->segment(5);
			
			
			$data['menu_lists'] = $this->User_model->getLists(array("job_status", "country_list"));
			$data['template'] = "user/single_message.php";
			$data['css'] = array("users/listing.css");
			//$data['js'] = array("battle/battle.js");
			
			
			
			
			
			$data['message'] = $this->exchange_model->readMessage($data['userId'], $data['messageId'], $data['messageToken'], $data['action']);
			
			
			$this->load->view("default_view", $data);
		} else {
			redirect(HOME);	
		}
	
	}
	
	public function splash(){
		$data = array();
		
		$data['opengraph']['image'] = site_url() . "assets/temp_images/battle-gallery-card.jpg";
		$data['opengraph']['title'] = "Battle Gallery";
		$data['opengraph']['description'] = "Launching soon...";
		$data['opengraph']['url'] = site_url();
		
		$this->load->view("splash_view", $data);
	}
	
	public function setbeta(){
		$data['options'] = $this->User_model->getOptions();
		$this->User_model->setbeta($data['options']);
		//echo "Set Beta URL: " . site_url() . "user/setbeta/?key=" . $data['options']['beta_key'];
	}
	
	public function welcome(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$userId = $this->User_model->getUserId();
			if($userId > 0){
				$data['userId'] = $userId;
				$users[] = $userId;
				$data['users'] = $this->User_model->getUsersInfo($users);
				$data['dictionary'] = $this->User_model->getDictionary();
				$data['template'] = DIR_TEMPLATES . "special/welcome1.php";
				$this->load->view('ajax_modal_view', $data);		
			}
			
		}
	}
	
	public function search(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->User_model->search());
		
		}
	}
	
}
