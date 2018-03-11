<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class app extends CI_Controller {
	
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
		$this->load->model('exchange_model');
		//$this->load->model('exchange_model');
		$this->load->helper('string');
		
    }
	
	public function register(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->User_model->register_user());
		} 
	}
	
	public function user_login(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			
			echo json_encode($this->User_model->user_login());
		} 	
	}
	
	public function reset_password(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->User_model->resetPassword());	
		}
	}
	
	
	public function change_password(){
		//if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->User_model->passwordReset());	
		//}
		
			
	}
	
	public function setMissingUserEmail(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->User_model->setMissingUserEmail());
		} 
	}
	
	public function user_logout(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->User_model->user_logout());
		} 	
	}
	
	public function savebattle(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->Battle_model->savebattle());
		} 
	} 
	
	public function upload(){
		//if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->User_model->upload());	
		//}
	}
	
	public function saveImageSelection(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->User_model->saveImageSelection());
		} 	
	}
	
	public function test_createNewTeam(){
		$siteUrl = site_url();
		$targetUrl = "{$siteUrl}app/createNewTeam/";
		$string = "<form action=\"{$targetUrl}\" method=\"post\">\n";
		$string .="Reset Id:<br/><input type=\"text\" id=\"battle_id\" name=\"battle_id\" value=\"2\" /><br/><br/>\n";
		$string .=" Token:<br/><input type=\"text\" id=\"team_name\" name=\"team_name\" value=\"Gold Squadron\" /><br/><br/>\n";
		$string .="<input type=\"submit\" name=\"submit\" value=\"submit\" />\n";
		$string .= "</form>\n";
		echo $string;
	}
	
	public function createNewTeam(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->Battle_model->createNewTeam());
		} 	
	}
	 
	 
	public function getBattleTeams(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && isset($_GET['battle_id'])){
			$battle_id = (int)$_GET['battle_id'];
			$userId = $this->User_model->getUserId();
			$teams = $this->Battle_model->getBattleTeams($battle_id, $userId);
			?><div class='list'>
            <?php if(count($teams['teams']) > 0): foreach($teams['teams'] as $team): ?>
            	<div class='list_item clearfix' id="team_<?php echo $team['team_id']; ?>">
                <?php $imageSrc = ""; 
					if($team['team_card'] > 0){
						if(isset($teams['images'][$team['team_card']]['sizes']['card'])){
							$imageSrc = 	$teams['images'][$team['team_card']]['sizes']['card'];
						} else {
							$imageSrc = 	$teams['images'][$team['team_card']]['sizes']['thumb'];
						}
						$imageSrc = " style='background: url($imageSrc) no-repeat;'";
					}
					//print_r($teams);
				?>
                	<div class='unit thumb' <?php echo $imageSrc; ?>>
                    	<p class='members'><?php echo count($team['team_members']); ?> members</p>
                        <a href='#' class='button'  onclick="fs_modal('user/add_team_image_card/<?php echo $team['team_id']; ?>?type=team_card'); return false;">Change Image</a>
                    </div>
                    <div class='unit text_wrapper form'>
                    	<input type="hidden" class="team_token" value="<?php echo $team['token']; ?>" />
                    	<div class='formfield'>
                        	<label>Team name</label>
                            <input type="text" class="team_name" maxlength="100" value="<?php echo $team['team_name']; ?>" />
                        </div>
                        <div class='formfield'>
                        	<label>Team description</label>
                            <textarea class="team_description" maxlength="300"><?php echo $team['team_description']; ?></textarea>
                        </div>
                        <div class='formfield'>
                        	<a href='#' class='button' onclick="saveTeam(<?php echo $team['team_id']; ?>); return false;" >Save</a> <a href='#' class='button' onclick="saveTeam(<?php echo $team['team_id']; ?>, '<?php echo $team['token']; ?>'); return false;" >View team</a> <a href='#' class='button' onclick="$(this).next().fadeToggle(250); return false;"  >Delete</a> <a href='#' class='button hidden warning' onclick="deleteTeam(<?php echo $team['team_id']; ?>); return false;" >Confirm delete?</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; else: ?>
            <p>No teams were found.</p>
            <?php endif; ?>
            </div>
            
            <?php
			
			
			
		}
	}
	
	public function saveTeam(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->Battle_model->saveTeam());
		} 
	}
	
	public function deleteTeam(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->Battle_model->deleteTeam());
		} 
	}
	
	
	public function saveMediaToBattleEntry(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->Battle_model->saveMediaToBattleEntry());
		 }	
	}
	
	public function saveProfileBio(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->User_model->saveProfileBio());
		 }
	}
	
	public function addUserProfileSkill(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->User_model->addUserProfileSkill());
		 }
	}
	
	public function removeProfileSkill(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->User_model->removeProfileSkill());
		 }
	}
	
	public function modifyUserTimeLine(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$data['dictionary'] = $this->User_model->getDictionary();
			$data['userId'] = $this->User_model->getUserId();
			if($data['userId'] > 0){
				
				
				$data['type'] = "work";
				if(isset($_GET['type'])){$data['type'] = $_GET['type']; }
				$data['menu_lists'] = $this->User_model->getLists(array("skill_set", "months"));	
				$data['timeline_id'] = 0; // creates the Timeline ID Variable.
				if(isset($_GET['tid'])){$data['timeline_id'] = (int)$_GET['tid'];}
				if($data['timeline_id'] > 0){
					$data['timeline'] = $this->User_model->getBusinessTimelineById($data['userId'], $data['timeline_id']);
				}
				
				$data['template'] = DIR_TEMPLATES . "profile/user_modify_timeline_business.php";
				$this->load->view('ajax_modal_view', $data);	
			}
			
		} 	
	}
	
	public function savePersonalInfo(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->User_model->savePersonalInfo());
		 }
	}
	
	public function saveContactForm(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->User_model->saveContactForm());
		 }
	}
	
	public function saveUserLink(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->User_model->saveUserLink());
		 }
	}
	
	public function deleteUserLink(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->User_model->deleteUserLink());
		 }
	}
	
	public function loadUserLinks(){
		$userId = $this->User_model->getUserId();
		if($userId > 0 && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				
				$dictionary = $this->User_model->getDictionary();
				$links = $this->User_model->getUserLinks($userId);
				$editable = true;
				
				if(count($links) > 0): ?><?php foreach($links as $l): ?><div class='item <?php echo $l['type']; ?>' id="user_link_<?php echo $l['id']; ?>"><a href='#' class='delete' onclick='deleteUserLink(<?php echo $l['id']; ?>); return false;' ></a><?php if($editable){echo "";} ?>
                        	<strong class='social'><?php if($l['type'] == 'link'){$name = $l['name']; if(strlen($l['name']) < 1){$name = $this->functions->_e("link", $dictionary); } echo "<a href='{$l['url']}' target='_blank'>{$name }</a>";} else {echo "<a href='{$l['url']}' target='_blank'>{$l['type']}</a>";} ?></strong>
                        </div><?php endforeach; else: ?>
							<div class='item'><strong><?php echo $this->functions->_e("no links yet", $dictionary); ?></strong></div>
						<?php endif; 
				
		 }
	}
	
	public function modifyBusinessTimeline(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->User_model->modifyBusinessTimeline());
		 }
	}
	
	
	
	public function getTimelineElements(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				$userId = $this->User_model->getUserId();
				$timeline = $this->User_model->getBusinessTimeline($userId);
				$dictionary = $this->User_model->getDictionary();
				$editable = true;
				
				$cat = "work"; 
				if(isset($_GET['c']) && $_GET['c'] == 'education'){$cat = "education";} echo $cat;
				?>
                <ul id="work_list" class="list_timeline">
                            	<?php if(count($timeline[$cat]) > 0): foreach($timeline[$cat] as $item): ?>
                                	<?php $end_year = $item['end_year']; if($end_year == "3000"){$end_year = $this->functions->_e("now", $dictionary);}?>
                                	<li><div class='year_marker'><span><?php echo $end_year; ?></span></div><div class='dot'></div><div class='textwrapper'><h3><?php echo $item['business_name']; ?> (<?php echo $item['start_year'] . " &mdash; " . $end_year; ?>)<?php if($editable): ?> <a href='#' class='' onclick="<?php if($cat == 'work'){echo "addWorkTimeline";} else {echo "addEducationTimeline";} ?>(<?php echo $item['id']; ?>); return false; "><?php echo $this->functions->_e("edit", $dictionary); ?></a><?php endif; ?></h3><h4><?php echo $item['position_name']; ?></h4><?php echo nl2br($item['description']); ?></div></li>
                                <?php endforeach; endif; ?>
                            </ul>
                
                <?php
		 }
	}
	
	public function deleteBusinessTimelineItem(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->User_model->deleteBusinessTimelineItem());
		 }
	}
	
	public function searchTechSkills(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->User_model->searchTechSkills());
		 }
	}
	
	public function addTechSkills(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->User_model->addTechSkills());
		 }
	}
	
	public function deleteTechSkills(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->User_model->deleteTechSkills());
		 }
	}
	
	public function getTechSkillsAJAX(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				$userId = $this->User_model->getUserId();
				
				if($userId > 0){
					$tech_skills = $this->User_model->getTechSkills($userId);	
					$dictionary = $this->User_model->getDictionary();
					$editable = true;
					
					include(DIR_TEMPLATES . "profile/tech_skills.php"); 
				}
		 }
	}
	
	public function startFollowing(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->User_model->startFollowing());	
		}
	}
	
	public function stopFollowing(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->User_model->stopFollowing());	
		}
	}
	
	public function addImageToGallery(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->User_model->addImageToGallery());	
		}
	}
	
	public function saveUserImageTitle(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->User_model->saveUserImageTitle());	
		}
	}
	
	public function reorderUserGallery(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->User_model->reorderUserGallery());	
		}
	}
	
	public function test_postGalleryComment(){/*
		$siteUrl = site_url();
		$targetUrl = "{$siteUrl}app/postGalleryComment/";
		$string = "<form action=\"{$targetUrl}\" method=\"post\">\n";
		$string .="Image Id:<br/><input type=\"text\" id=\"image_id\" name=\"image_id\" value=\"506\" /><br/><br/>\n";
		$string .=" Image Token:<br/><input type=\"text\" id=\"image_token\" name=\"image_token\" value=\"QYMZgxaw\" /><br/><br/>\n";
		$string .="Thread Id:<br/><input type=\"text\" id=\"thread_id\" name=\"thread_id\" value=\"\" /><br/><br/>\n";
		$string .=" Thread Token:<br/><input type=\"text\" id=\"thread_token\" name=\"thread_token\" value=\"\" /><br/><br/>\n";
		$string .=" Thread Token:<br/><input type=\"text\" id=\"message\" name=\"message\" value=\"This is a test\" /><br/><br/>\n";
		
		$string .="<input type=\"submit\" name=\"submit\" value=\"submit\" />\n";
		$string .= "</form>\n";
		echo $string;
	*/}
	
	public function postGalleryComment(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->exchange_model->postGalleryComment());	
		}
	}
	
	public function saveUserImageDescription(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->User_model->saveUserImageDescription());	
		}
	}
	
	public function saveBattleVote(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			echo json_encode($this->Battle_model->saveBattleVote());	
		}
	}
	
	public function reloadUserSkills(){
		$userId = $this->User_model->getUserId();
			
		if($userId > 0 && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			
			$users = $this->User_model->getUsersInfo(array($userId));
			
			$skills = array();
			if(strlen($users['users'][$userId]['skills']) > 0){$skills = unserialize($users['users'][$userId]['skills']);}
			
			$editable = true;
			$menu_lists = $this->User_model->getLists(array("skill_set"));
			?>
            	
					<?php foreach($skills as $key => $value): ?>
                        <?php $pro = $value * 10; $con = 100 - $pro; $deleteString = ""; if($editable){$deleteString = "<div class='edit_chart' onclick='editChart(\"{$key}\");'></div><div class='delete_chart' onclick='deleteChart(\"{$key}\"); return false;'></div>";} if(!empty($key)): ?>
                        
                        <div class='chart' id="skill_chart_<?php echo str_replace(" ", "-", $key); ?>" val="<?php echo $pro . "," . $con; ?>"><canvas id="chart_<?php echo $key; ?>" width="150" height="150"></canvas><div class='percent<?php if($con > $pro){echo " con";} ?>'><strong><?php echo ((int)$pro / 10); ?><span class='small'>/10</span></strong></div><p class='chart_title'><?php echo  $menu_lists['skill_set'][$key]; ?></p><?php echo $deleteString; ?></div>
                    <?php endif; endforeach; ?>
                    
                    
            <?
		}
	}
	
	public function joinBattle(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->Battle_model->joinBattle());
		 }
	}
	
	public function removeTeamMember(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->Battle_model->removeTeamMember());
		 }
	}
	
	public function inviteMember(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->User_model->inviteMember());
		 }
	}
	
	public function deleteInvite(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->User_model->deleteInvite());
		 }
	}
	
	public function confirmInvite(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->User_model->confirmInvite());
		 }
	}
	
	public function viewBattleInvitation(){
		$userId = $this->User_model->getUserId();
		if($userId > 0 && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $this->uri->segment(3)){
				$data['userId'] = $userId;
				$data['dictionary'] = $this->User_model->getDictionary();
				$data['battle_id'] = (int)$this->uri->segment(3);
				$data['battle'] = $this->Battle_model->getBattle($data['battle_id']);
				
				$data['roles'] = $data['role'] = $this->Battle_model->getInvitations($data['userId'], $data['battle_id']);
				
				$data['template'] = "battles/view_invitation.php";
				$data['format'] = 'compact';
				$this->load->view('ajax_view', $data);
		 }
	}
	
	public function respondBattleInvitation(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->Battle_model->respondBattleInvitation());
		 }
	}
	
	public function getUserUpdates(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->User_model->getUserUpdates());
		 }
	}
	
	
	
	public function getUserNotifications(){
		$userId = $this->User_model->getUserId();
		if($userId > 0 && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				$data['userId'] = $userId;
				$data['dictionary'] = $this->User_model->getDictionary();
				$data['notifications'] = $this->User_model->getUserNotifications($userId, 0);
				
				$data['format'] = 'compact';
				if(isset($_GET['format']) && $_GET['format'] == 'full'){$data['format'] = 'full';}
				
				if($data['format'] == 'compact'){
					$data['template'] =  "user/user_notifications_compact.php";
				} else {
					
				}
				$this->load->view('ajax_view', $data);	
		 }
			
	}
	
	public function getUsermessages(){
		$userId = $this->User_model->getUserId();
		if($userId > 0 && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				$data['userId'] = $userId;
				$data['dictionary'] = $this->User_model->getDictionary();
				$data['messages'] = $this->exchange_model->getUserMessages($userId, 0, 5);
				$data['action'] = 'inbox';
				$data['format'] = 'compact';
				if(isset($_GET['format']) && $_GET['format'] == 'full'){$data['format'] = 'full';}
				
				if($data['format'] == 'compact'){
					$data['template'] =  "user/user_messages_compact.php";
				} else {
					
				}
				$this->load->view('ajax_view', $data);	
		 }
			
	}
	
	
	public function newmessage(){
		$data = array();
		$data['userId'] = $this->User_model->getUserId();
		
		if($data['userId'] > 0 && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && isset($_GET['u'])){
			
			$this->User_model->setUserDefault();
			$data['dictionary'] = $this->User_model->getDictionary();
			$data['page_title'] = "Messages | " . SITE_NAME;
			
			$data['memberId'] = (int)$_GET['u'];
			
			$users = array($data['userId'], $data['memberId']);
			$data['users'] = $this->User_model->getUsersInfo($users);
			$data['format'] = 'compact';
				if(isset($_GET['format']) && $_GET['format'] == 'full'){$data['format'] = 'full';}
				
				if($data['format'] == 'compact'){
					$data['template'] =  "user/new_user_messages_compact.php";
				} else {
					
				}
				$this->load->view('ajax_view', $data);	
		} 
		
	}
	
	
	
	public function sendMessage(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->exchange_model->sendMessage());
		 }	
	}
	
	
	/*------------------------- CHAT -------------------------*/
	
	public function opendirectmessage(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->exchange_model->opendirectmessage());
		 }	
	}
	
	public function getDirectChatThreads(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->exchange_model->getDirectChatThreads());
		 }	
	}
	
	public function displayDirectChat(){
		$userId = $this->User_model->getUserId();
		if($userId > 0 && isset($_GET['mid']) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			
			$memberId = (int)$_GET['mid'];
			$data['member_id'] = $memberId;
			$data['user'] = $this->exchange_model->getDirectThreadByUserId($userId, $memberId);
			$data['dictionary'] = $this->User_model->getDictionary();
			
			$this->load->view('direct_chat_view', $data);	
			
		}
	}
	
	public function directPostMessage(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->exchange_model->directPostMessage());
		 }	
	}
	
	public function getDirectMessages(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->exchange_model->getDirectMessages());
		 }	
	}
	
	public function updateLastViewDirectChatThread(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				echo json_encode($this->exchange_model->updateLastViewDirectChatThread());
		 }
	}
	
	
	/*------------------------- END CHAT -------------------------*/
	
	
	
	
}
?>