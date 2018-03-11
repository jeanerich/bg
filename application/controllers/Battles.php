<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Battles extends CI_Controller {
	
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
		$this->load->library('pagination'); 
		$this->load->helper('string');
		//$this->load->model('media_model');
		
		
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
		
		$data['css'] = array("battles/battles-cover.css", "battles/battles.css");
		$data['js'] = array("battle/single-battle.js");
		
		$data['menu_lists'] = $this->User_model->getLists(array("job_status", "country_list", "battle_categories"));
		
		$data['ongoing_battles'] = $this->Battle_model->getBattles($data['userId'], "ongoing");
		$data['past_battles'] = $this->Battle_model->getBattles($data['userId'], "past");
		$data['future_battles'] = $this->Battle_model->getBattles($data['userId'], "future");
		
		$data['page_title'] = $this->functions->_e("battles", $data['dictionary']) . " | " . SITE_NAME;
		$data['template'] = "battles/listbattles.php";
		
		$this->load->view("default_view", $data);
	}
	
	public function create(){
		$data = array();
		$data['options'] = $this->User_model->getOptions();
		$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['page_title'] = SITE_NAME;
		$data['userId'] = $this->User_model->getUserId();
		if($data['userId'] > 0){$data['userInfo'] = $this->User_model->getUsersInfo(array($data['userId']));
			$data['menu_lists'] = $this->User_model->getLists(array("job_status", "country_list", "battle_categories"));
			$data['template'] = "battles/createbattle.php";
			$data['css'] = array("battles/battles.css");
			$data['js'] = array("battle/editbattle.js");
			
			$data['battle_id'] = 0;
			if($this->uri->segment(3)){
				$data['battle_id'] = (int)$this->uri->segment(3);
				$data['battle'] = $this->Battle_model->getBattle($data['battle_id']);
			}
			
			$users = array();
			if($data['userId'] > 0){
				$users[] = $data['userId'];
			}
			
			if(count($users) > 0){
				$usersInfo = $this->User_model->getUsersInfo($users); 
				$data['users'] = $usersInfo['users'];
			}
			
			$this->load->view("default_view", $data);
		} else {
			redirect(HOME);	
		}
		
	}
	
	public function view(){
		
		$data = array();
		$data['options'] = $this->User_model->getOptions();
		$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['page_title'] = SITE_NAME;
		$data['userId'] = $this->User_model->getUserId();
		if($this->uri->segment(3)){
			$data['editable'] = false;
			
			$users = array();
			if($data['userId'] > 0){
				$users[] = $data['userId'];
			}
			
			if(count($users) > 0){
				$usersInfo = $this->User_model->getUsersInfo($users); 
				$data['users'] = $usersInfo['users'];
			}
			$data['current_menu'] = "battle";
			$data['menu_lists'] = $this->User_model->getLists(array("job_status", "country_list", "battle_categories"));
			$data['template'] = "battles/viewbattle.php";
			$data['sub_template'] = "battles/battle-home.php";
			$data['css'] = array("battles/battles.css", "battles/viewbattles.css", "common/imageviewer.css","member/profile.css");
			//$data['js'] = array("battle/editbattle.js");
			$data['js'] = array("battle/battle.js", "common/imageviewer.min.js");
			
			
			$data['battle_id'] = 0;
		
			$data['battle_id'] = (int)$this->uri->segment(3);
			$data['battle'] = $this->Battle_model->getBattle($data['battle_id']);
			if($data['battle_id'] > 0){
				
				$data['teams'] = $this->Battle_model->getBattleTeams($data['battle_id'], $data['userId']);
				if($data['userId'] > 0){
					$data['entry'] = $this->Battle_model->getBattleEntry($data['userId'], $data['battle_id']);	
					$data['role'] = $this->Battle_model->getInvitations($data['userId'], $data['battle_id'], false); 
					
				}
			}
			
			if($data['battle']['battle']['user_id'] == $data['userId']){$data['editable'] = true;}
			
			$data['leaders'] = $this->Battle_model->getBattleLeaders($data['battle_id'], 0, $data['editable']);
			
			$this->load->view("default_view", $data);
		} else {
			redirect(HOME);	
		}
		
	}
	
	public function showBattleTeams(){ // is an Ajax call to refresh current battles inside the "battles->view" above.
		$data = array();
		$data['options'] = $this->User_model->getOptions();
		$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['userId'] = $this->User_model->getUserId();
		if($this->uri->segment(3)){
			$data['editable'] = false;
			
			$users = array();
			if($data['userId'] > 0){
				$users[] = $data['userId'];
			}
			
			if(count($users) > 0){
				$usersInfo = $this->User_model->getUsersInfo($users); 
				$data['users'] = $usersInfo['users'];
			}
			
			$data['template'] = "battles/listteams.php";
			$data['css'] = array("battles/battles.css", "battles/viewbattles.css");
			//$data['js'] = array("battle/editbattle.js");
			$data['js'] = array("battle/battle.js");
			
			$data['battle_id'] = 0;
		
			$data['battle_id'] = (int)$this->uri->segment(3);
			$data['battle'] = $this->Battle_model->getBattle($data['battle_id']);
			if($data['battle_id'] > 0){
				$data['teams'] = $this->Battle_model->getBattleTeams($data['battle_id'], $data['userId']);
			}
		
			if($data['battle']['battle']['user_id'] == $data['userId']){$data['editable'] = true;}
			
			$this->load->view("ajax_view", $data);
		} else {
			redirect(HOME);	
		}	
	}
	
	
	public function manage(){
		$data = array();
		$data['options'] = $this->User_model->getOptions();
		$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['page_title'] = SITE_NAME;
		$data['userId'] = $this->User_model->getUserId();
		if($data['userId'] > 0){$data['userInfo'] = $this->User_model->getUsersInfo(array($data['userId']));
			$data['menu_lists'] = $this->User_model->getLists(array("job_status", "country_list", "battle_categories"));
			$data['template'] = "battles/managebattles.php";
			$data['css'] = array("battles/battles.css");
			$data['js'] = array("battle/editbattle.js");
			
			$users = array();
			if($data['userId'] > 0){
				$users[] = $data['userId'];
			}
			
			if(count($users) > 0){
				$usersInfo = $this->User_model->getUsersInfo($users); 
				$data['users'] = $usersInfo['users'];
			}
			
			$offset = 0; 
			if($this->uri->segment(3)){
				$offset = (int)$this->uri->segment(3);
			}
			
			$data['battles'] = $this->Battle_model->getManagedBattles($data['userId'], $offset);
			
			$this->load->view("default_view", $data);
		} else {
			redirect(HOME);	
		}
	}
	
	public function newteam(){
		
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['userId'] = $this->User_model->getUserId();
		
		$data['template'] = DIR_TEMPLATES . "battles/newteam.php";
		$this->load->view('ajax_modal_view', $data);	
	}
	
	public function newjudge(){
		
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['userId'] = $this->User_model->getUserId();
		
		$data['template'] = DIR_TEMPLATES . "battles/newjudge.php";
		$this->load->view('ajax_modal_view', $data);	
		
	}
	
	public function newmentor(){
		
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['userId'] = $this->User_model->getUserId();
		$data['battle_id'] = 0;
		if($this->uri->segment(3)){
				$data['battle_id'] = (int)$this->uri->segment(3);
				$data['battle'] = $this->Battle_model->getBattle($data['battle_id']);
				
				if($data['battle']['battle']['battle_type'] == 'team'){$data['teams'] = $this->Battle_model->getBattleTeams($data['battle_id'], $data['userId']);}
				
				
			}
		
		$data['template'] = DIR_TEMPLATES . "battles/newmentor.php";
		$this->load->view('ajax_modal_view', $data);	
		
	}
	
	public function vote(){
		$data = array();
		$data['options'] = $this->User_model->getOptions();
		$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['page_title'] = SITE_NAME;
		$data['userId'] = $this->User_model->getUserId();
		
		$data['battle_id'] = 0;
		
		if($this->uri->segment(3)){
			$data['battle_id'] = $this->uri->segment(3);
			
		}
		
		$data['battles'] = $this->Battle_model->generateVoteBattle($data['battle_id']);
			
		$users = array();
		if($data['userId'] > 0){
			$users[] = $data['userId'];
		}
		
		if(count($users) > 0){
			$usersInfo = $this->User_model->getUsersInfo($users); 
			$data['users'] = $usersInfo['users'];
		}
		
		$data['menu_lists'] = $this->User_model->getLists(array("job_status", "country_list", "battle_categories"));
		$data['template'] = "battles/battle-vote.php";
		$data['css'] = array("special/intro1.css", "common/imageviewer.css");
		$data['js'] = array("common/imageviewer.min.js", "battle/battle-vote.js");
		
		
		$this->load->view("default_view", $data);
	}
	
	
	
	public function previewImage(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$data = array();
			
			if($this->uri->segment(3) && $this->uri->segment(4)){
				$data['image_id'] = (int)$this->uri->segment(3);
				$data['token'] = $this->uri->segment(4);
				$data['images'] = $this->User_model->getImagesByIds(array($this->uri->segment(3)));
				if(isset($data['images'][$data['image_id']]) && $data['token'] == $data['images'][$data['image_id']]['token']){
				$data['image'] = $data['images'][$data['image_id']];
				$data['template'] = DIR_TEMPLATES . "battles/image_preview.php";
				$this->load->view('ajax_modal_view', $data);	
				}
			}
		}
	}
	
	public function team(){
		
		$data = array();
		$data['options'] = $this->User_model->getOptions();
		$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['page_title'] = SITE_NAME;
		$data['userId'] = $this->User_model->getUserId();
		$users[] = $data['userId'];
		if($this->uri->segment(3) && $this->uri->segment(4)){
			$data['editable'] = false;
			$data['battle_id'] = (int)$this->uri->segment(3); 
			$data['team_id'] = $this->uri->segment(4);
			
			
			if(count($users) > 0){
				$usersInfo = $this->User_model->getUsersInfo($users); 
				$data['users'] = $usersInfo['users'];
			}
			
			$data['menu_lists'] = $this->User_model->getLists(array("job_status", "country_list", "battle_categories"));
			$data['template'] = "battles/viewteam.php";
			$data['css'] = array("battles/battles.css", "battles/viewbattles.css","member/profile.css");
			//$data['js'] = array("battle/editbattle.js");
			$data['js'] = array("battle/battle.js");
			
			$offset = 0;
			if(isset($_GET['o'])){$offset = (int)$_GET['o'];}
			
			$data['battle'] = $this->Battle_model->getBattle($data['battle_id']);
			$data['team'] = $this->Battle_model->getTeam($data['battle_id'], $data['team_id'], $offset);
			
			if($data['battle']['battle']['user_id'] == $data['userId']){$data['editable'] = true;}
			
			$this->load->view("default_view", $data);
		} else {
			redirect(HOME);	
		}
	}
	
	public function warriors(){
		$data = array();
		$data['options'] = $this->User_model->getOptions();
		$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['page_title'] = SITE_NAME;
		$data['userId'] = $this->User_model->getUserId();
		$users[] = $data['userId'];
		if($this->uri->segment(3) && $this->uri->segment(4)){
			$data['editable'] = false;
			$data['battle_id'] = (int)$this->uri->segment(3); 
			$data['team_id'] = $this->uri->segment(4);
			
			
			if(count($users) > 0){
				$usersInfo = $this->User_model->getUsersInfo($users); 
				$data['users'] = $usersInfo['users'];
			}
			$data['current_menu'] = "warriors";
			$data['menu_lists'] = $this->User_model->getLists(array("job_status", "country_list", "battle_categories"));
			$data['template'] = "battles/viewbattle.php";
			$data['sub_template'] = "battles/viewwarriors.php";
			$data['css'] = array("battles/battles.css", "battles/viewbattles.css","member/profile.css");
			//$data['js'] = array("battle/editbattle.js");
			//$data['js'] = array("battle/battle.js");
			
			$offset = 0;
			if(isset($_GET['o'])){$offset = (int)$_GET['o'];}
			
			$data['warriors'] = $this->Battle_model->getBattleWarriors($data['battle_id']);
			
			$data['battle_id'] = 0;
		
			$data['battle_id'] = (int)$this->uri->segment(3);
			$data['battle'] = $this->Battle_model->getBattle($data['battle_id']);
			$data['editable'] = false;
			if($data['battle']['battle']['user_id'] == $data['userId']){$data['editable'] = true;}
			
			
			$this->load->view("default_view", $data);
		} else {
			redirect(HOME);	
		}
	}
	
	public function entries(){
		$data = array();
		$data['options'] = $this->User_model->getOptions();
		$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['page_title'] = SITE_NAME;
		$data['userId'] = $this->User_model->getUserId();
		$users[] = $data['userId'];
		if($this->uri->segment(3) && $this->uri->segment(4)){
			$data['editable'] = false;
			$data['battle_id'] = (int)$this->uri->segment(3); 
			$data['team_id'] = $this->uri->segment(4);
			
			
			if(count($users) > 0){
				$usersInfo = $this->User_model->getUsersInfo($users); 
				$data['users'] = $usersInfo['users'];
			}
			$data['current_menu'] = "entries";
			$data['menu_lists'] = $this->User_model->getLists(array("job_status", "country_list", "battle_categories"));
			$data['template'] = "battles/viewbattle.php";
			$data['sub_template'] = "battles/list-entries.php";
			$data['css'] = array("battles/battles.css", "battles/viewbattles.css","member/profile.css");
			
			$data['js'][] = "common/imageviewer.min.js";
			$data['css'][] = "common/imageviewer.css";
			//$data['js'] = array("battle/editbattle.js");
			//$data['js'] = array("battle/battle.js");
			
			$offset = 0;
			if(isset($_GET['o'])){$offset = (int)$_GET['o'];}
			
			$data['warriors'] = $this->Battle_model->getAllBattleEntries($data['battle_id']);
			
			$data['battle_id'] = 0;
		
			$data['battle_id'] = (int)$this->uri->segment(3);
			$data['battle'] = $this->Battle_model->getBattle($data['battle_id']);
			$data['editable'] = false;
			if($data['battle']['battle']['user_id'] == $data['userId']){$data['editable'] = true;}
			
			
			$this->load->view("default_view", $data);
		} else {
			redirect(HOME);	
		}
	}
	
	public function entry(){
		$data = array();
		$data['options'] = $this->User_model->getOptions();
		$this->User_model->setUserDefault();
		$data['dictionary'] = $this->User_model->getDictionary();
		$data['page_title'] = SITE_NAME;
		$data['userId'] = $this->User_model->getUserId();
		$users[] = $data['userId'];
		if($this->uri->segment(3) && $this->uri->segment(4)){
			$data['editable'] = false;
			$data['battle_id'] = (int)$this->uri->segment(3); 
			$data['entry_id'] = $this->uri->segment(4);
			
			
			if(count($users) > 0){
				$usersInfo = $this->User_model->getUsersInfo($users); 
				$data['users'] = $usersInfo['users'];
			}
			$data['current_menu'] = "entries";
			$data['menu_lists'] = $this->User_model->getLists(array("job_status", "country_list", "battle_categories"));
			$data['template'] = "battles/single-entry.php";
			$data['css'] = array("battles/battles.css", "battles/viewbattles.css","member/profile.css");
			
			$data['js'][] = "common/imageviewer.min.js";
			$data['js'][] = "battle/single-entry.js";
			$data['css'][] = "common/imageviewer.css";
			$data['css'][] = "battles/single-entries.css";
			
			$data['entry'] = $this->Battle_model->getSingleEntry($data['entry_id'], $data['battle_id']);
			
			$data['battle_id'] = 0;
		
			$data['battle_id'] = (int)$this->uri->segment(3);
			$data['battle'] = $this->Battle_model->getBattle($data['battle_id']);
			$data['editable'] = false;
			if($data['battle']['battle']['user_id'] == $data['userId']){$data['editable'] = true;}
			
			
			$this->load->view("default_view", $data);
		} else {
			redirect(HOME);	
		}
	}
	
	public function teamjoin(){
		
		$data['userId'] = $this->User_model->getUserId();
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $data['userId'] > 0){
			if($this->uri->segment(3) && $this->uri->segment(4)){
				
				$data['dictionary'] = $this->User_model->getDictionary();
				$data['battle_id'] = (int)$this->uri->segment(3);
				$data['team_id'] = (int)$this->uri->segment(4);
				
				
				$data['battle'] = $this->Battle_model->getBattle($data['battle_id']);
				if($data['battle_id'] > 0){
					$data['teams'] = $this->Battle_model->getBattleTeams($data['battle_id'], $data['userId']);
				}
				
				$data['template'] = DIR_TEMPLATES . "battles/jointeam.php";
				$this->load->view('ajax_modal_view', $data);	
			}
		}
		
	}
	
	public function teamquit(){
		$data['userId'] = $this->User_model->getUserId();
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $data['userId'] > 0){
			if($this->uri->segment(3) && $this->uri->segment(4)){
				
				$data['dictionary'] = $this->User_model->getDictionary();
				$data['battle_id'] = (int)$this->uri->segment(3);
				$data['team_id'] = (int)$this->uri->segment(4);
				
				
				$data['battle'] = $this->Battle_model->getBattle($data['battle_id']);
				if($data['battle_id'] > 0){
					$data['teams'] = $this->Battle_model->getBattleTeams($data['battle_id'], $data['userId']);
				}
				
				$data['template'] = DIR_TEMPLATES . "battles/quitteam.php";
				$this->load->view('ajax_modal_view', $data);	
			}
		}
	}
	
	public function addBattleLeader(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->Battle_model->addBattleLeader());
		}
	}
	
	public function test_removeBattleLeader(){
		$siteUrl = site_url();
		$targetUrl = "{$siteUrl}battles/removeBattleLeader/";
		$string = "<form action=\"{$targetUrl}\" method=\"post\">\n";
		$string .="Battle Id:<br/><input type=\"text\" id=\"battle_id\" name=\"battle_id\" value=\"2\" /><br/><br/>\n";
		$string .="Role Id:<br/><input type=\"text\" id=\"role_id\" name=\"role_id\" value=\"4\" /><br/><br/>\n";
		
		$string .="<input type=\"submit\" name=\"submit\" value=\"submit\" />\n";
		$string .= "</form>\n";
		echo $string;
	}
	
	public function removeBattleLeader(){
		//if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->Battle_model->removeBattleLeader());
		//}
	}
	
	public function getJudges(){
		//if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $this->uri->segment(3)){
			$data['dictionary'] = $this->User_model->getDictionary();
			$data['userId'] = $this->User_model->getUserId();
			$data['battle_id'] = (int)$this->uri->segment(3);
			$data['battle'] = $this->Battle_model->getBattle($data['battle_id']);
			$editable = false;
			if($data['battle']['battle']['user_id'] == $data['userId']){$editable = true;}
			$data['editable'] = $editable;
			$data['leaders'] = $this->Battle_model->getBattleLeaders($data['battle_id'], 0, true);
			
			
			$data['template'] = "battles/judges_list.php";
				$this->load->view('ajax_view', $data);	
		//}	
	}
	
	public function getMentors(){
		//if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $this->uri->segment(3)){
			$data['dictionary'] = $this->User_model->getDictionary();
			$data['userId'] = $this->User_model->getUserId();
			$data['battle_id'] = (int)$this->uri->segment(3);
			$data['battle'] = $this->Battle_model->getBattle($data['battle_id']);
			if($data['battle']['battle']['battle_type'] == 'team'){$data['teams'] = $this->Battle_model->getBattleTeams($data['battle_id'], $data['userId']);}
			$editable = false;
			if($data['battle']['battle']['user_id'] == $data['userId']){$editable = true;}
			$data['editable'] = $editable;
			
			$data['leaders'] = $this->Battle_model->getBattleLeaders($data['battle_id'], 0, true);
			
			
			$data['template'] = "battles/mentors_list.php";
				$this->load->view('ajax_view', $data);	
		//}	
	}
	
	public function recruitmentor(){
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
		
		$data['css'] = array("battles/battles-cover.css", "special/recruit-mentor.css");
		$data['js'] = array("battle/single-battle.js");
		
		$data['menu_lists'] = $this->User_model->getLists(array("job_status", "country_list", "battle_categories"));
		
		$data['ongoing_battles'] = $this->Battle_model->getBattles($data['userId'], "ongoing");
		$data['past_battles'] = $this->Battle_model->getBattles($data['userId'], "past");
		$data['future_battles'] = $this->Battle_model->getBattles($data['userId'], "future");
		
		$data['page_title'] = $this->functions->_e("become a mentor", $data['dictionary']) . " | " . SITE_NAME;
		$data['template'] = "battles/recruit_mentor.php";
		
		$this->load->view("default_view", $data);	
	}
	
	public function listmentors(){//
		echo "List mentors";
		
	}
}
