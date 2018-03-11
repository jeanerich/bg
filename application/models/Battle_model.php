<?php 
class battle_model extends CI_Model {

    function __construct(){
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
		//$this->load->library('image_lib');
    }
	
	public function savebattle(){
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['battle_id']) && isset($_POST['battle_title']) && isset($_POST['short_description']) && isset($_POST['start_date']) && isset($_POST['vote_date']) && isset($_POST['end_date'])){
			$battle_id = (int)$_POST['battle_id'];
			$battle_title = $_POST['battle_title'];
			$short_description = $_POST['short_description'];
			$start_date = date("Y-m-d H:i:s", strtotime($_POST['start_date']));
			$vote_date = date("Y-m-d H:i:s", strtotime($_POST['vote_date']));
			$end_date = date("Y-m-d H:i:s", strtotime($_POST['end_date']));
			$categories = ""; 
			if(isset($_POST['categories'])){
				$categories = $_POST['categories'];	
			}
			
			$vote_option = 0;
			if(isset($_POST['vote_option'])){
				$vote_option = (int)$_POST['vote_option'];	
				if($vote_option < 0 || $vote_option > 1){$vote_option = 0;}
			}
			
			$territory = ""; if(isset($_POST['territory'])){$territory = $_POST['territory'];}
			$long_description = ""; if(isset($_POST['long_description'])){$long_description = $_POST['long_description'];}
			$rules = ""; if(isset($_POST['battle_rules'])){$rules = $_POST['battle_rules'];}
			
			$battle_type = "individual"; 
			if(isset($_POST['battle_type']) && in_array($_POST['battle_type'], array("individual","team"))){$battle_type = $_POST['battle_type'];}
			
			if($battle_id > 0){ // edit battle
				$sql = "UPDATE battles SET battle_name = ?, battle_type = ?, start_date = ?, vote_date = ?, end_date = ?, battle_and_vote = ?, battle_short_description = ?, battle_long_description = ?, battle_rules = ?, battle_categories = ?, territory = ? WHERE battle_id = ? AND user_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($battle_title, $battle_type, $start_date, $vote_date, $end_date, $vote_option, $short_description, $long_description, $rules, $categories, $territory, $battle_id, $userId));
				//$data['query'] = $this->db->last_query();
				$data['success'] = true;
			} else { // create new battle
				$sql = "INSERT INTO battles(battle_name, user_id, battle_type, start_date, vote_date, end_date, battle_and_vote, battle_short_description) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";	
				$q = $this->db->query($sql, array($battle_title, $userId, $battle_type, $start_date, $vote_date, $end_date, $vote_option, $short_description));
				
				$data['battle_id'] = $this->db->insert_id();
				$data['return_link'] = site_url() . "battles/create/{$data['battle_id']}";
				$data['success'] = true;
			}
				
		}
		
		
		return $data;	
	}
	
	public function getBattle($battleId){
		$sql = "SELECT * FROM battles WHERE battle_id = ? LIMIT 1";
		$q = $this->db->query($sql, array($battleId));
		$images = array();
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$battle['battle_name'] = $row['battle_name'];
				$battle['start_date'] = $row['start_date'];
				$battle['s_date'] = $this->User_model->formatTime($battle['start_date']);
				$battle['vote_date'] = $row['vote_date'];
				$battle['v_date'] = $this->User_model->formatTime( $row['vote_date']);
				$battle['end_date'] = $row['end_date'];
				$battle['e_date'] = $this->User_model->formatTime( $row['end_date']);
				$battle['user_id'] = $row['user_id'];
				$battle['battle_short_description'] = $row['battle_short_description'];
				$battle['battle_and_vote'] = $row['battle_and_vote'];
				$battle['battle_type'] = $row['battle_type'];
				$categories = array();
				if(strlen($row['battle_categories']) > 0){
					$categories = explode(",", $row['battle_categories']);	
				}
				$battle['categories'] = $categories;
				$battle['territory'] = $row['territory'];
				$battle['battle_rules'] = $row['battle_rules'];
				$battle['battle_long_description'] = $row['battle_long_description'];
				
				$battle['hero_image'] = $row['battle_hero_image'];
				if($battle['hero_image'] > 0){$images[] = $battle['hero_image'];}
				
				$battle['logo_image'] = $row['battle_logo_image'];
				if($battle['logo_image'] > 0){$images[] = $battle['logo_image'];}
				
				$battle['card_image'] = $row['battle_image_card'];
				if($battle['card_image'] > 0){$images[] = $battle['card_image'];}
			}
			
			if(count($images) > 0){$data['images'] = $this->User_model->getImagesByIds($images); }
			$data['battle'] = $battle;
			
			return $data;
		}
	}
	
	public function getBattlesByIds($battleIds){
		$battles = array();
		if(count($battleIds) > 0){
		$battleIdString = implode(',', $battleIds);
		$sql = "SELECT * FROM battles WHERE battle_id IN ({$battleIdString}) LIMIT 1";
		$q = $this->db->query($sql);
		$images = array();
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$battleId = $row['battle_id'];
				$battle['battle_name'] = $row['battle_name'];
				$battle['start_date'] = $row['start_date'];
				$battle['s_date'] = $this->User_model->formatTime($battle['start_date']);
				$battle['vote_date'] = $row['vote_date'];
				$battle['v_date'] = $this->User_model->formatTime( $row['vote_date']);
				$battle['end_date'] = $row['end_date'];
				$battle['e_date'] = $this->User_model->formatTime( $row['end_date']);
				$battle['user_id'] = $row['user_id'];
				$battle['battle_short_description'] = $row['battle_short_description'];
				$battle['battle_and_vote'] = $row['battle_and_vote'];
				$battle['battle_type'] = $row['battle_type'];
				$categories = array();
				if(strlen($row['battle_categories']) > 0){
					$categories = explode(",", $row['battle_categories']);	
				}
				$battle['categories'] = $categories;
				$battle['territory'] = $row['territory'];
				$battle['battle_rules'] = $row['battle_rules'];
				$battle['battle_long_description'] = $row['battle_long_description'];
				
				$battle['hero_image'] = $row['battle_hero_image'];
				if($battle['hero_image'] > 0){$images[] = $battle['hero_image'];}
				
				$battle['logo_image'] = $row['battle_logo_image'];
				if($battle['logo_image'] > 0){$images[] = $battle['logo_image'];}
				
				$battle['card_image'] = $row['battle_image_card'];
				if($battle['card_image'] > 0){$images[] = $battle['card_image'];}
				$battles[$battleId] = $battle;
			}
			
			if(count($images) > 0){$data['images'] = $this->User_model->getImagesByIds($images); }
			
			}
			$data['battles'] = $battles;
			return $data;
		}
	}
	
	public function getManagedBattles($userId, $offset = 0){
		$sql = "SELECT battle_id FROM battles WHERE user_id = ?";
		$q = $this->db->query($sql, array( $userId));
		
		$data['no_battles'] = $q->num_rows();
		
		$sql = "SELECT * FROM battles WHERE user_id = ? ORDER BY battle_id DESC LIMIT 20 OFFSET ? ";
		$q = $this->db->query($sql, array($userId, $offset));
		
		$images = array();
		$battles = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$battle['battle_id'] = $row['battle_id'];
				$battle['battle_name'] = $row['battle_name'];
				$battle['start_date'] = $row['start_date'];
				$battle['vote_date'] = $row['vote_date'];
				$battle['end_date'] = $row['end_date'];
				$battle['battle_short_description'] = $row['battle_short_description'];
				$battle['battle_type'] = $row['battle_type'];
				$categories = array();
				if(strlen($row['battle_categories']) > 0){
					$battle['categories'] = explode(",", $row['battle_categories']);	
				}
				$battle['territory'] = $row['territory'];
				$battle['battle_rules'] = $row['battle_rules'];
				$battle['battle_long_description'] = $row['battle_long_description'];
				$battle['admin_approved'] = $row['admin_approved'];
				$battle['online_status'] = $row['online_status'];
				$battle['visible_status'] = $row['visible_status'];
				
				$battle['hero_image'] = $row['battle_hero_image'];
				if($battle['hero_image'] > 0){$images[] = $battle['hero_image'];}
				
				$battle['logo_image'] = $row['battle_logo_image'];
				if($battle['logo_image'] > 0){$images[] = $battle['logo_image'];}
				
				$battle['card_image'] = $row['battle_image_card'];
				if($battle['card_image'] > 0){$images[] = $battle['card_image'];}
				
				$battles[] = $battle;
			}
			
			if(count($images) > 0){$images = $this->User_model->getImagesByIds($images);}
		}
		
		$data['images'] = $images;
		$data['battles'] = $battles;
		
		return $data;
	}
	
	public function createNewTeam(){
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['battle_id']) && isset($_POST['team_name'])){
			$battle_id = (int)$_POST['battle_id'];
			$team_name = $_POST['team_name'];
			$token = random_string('alnum', 10);
			
			$sql = "INSERT INTO battle_teams(creator_user_id, battle_id, token, team_name) VALUES(?, ?, ?, ?)";
			$q = $this->db->query($sql, array($userId, $battle_id, $token, $team_name));
			
			$data['team_id'] = $this->db->insert_id();
			$data['success'] = true;
		}
		
		return $data;	
	}
	
	public function getTeam($battleId, $teamId, $offset = 0){
		$data['success'] = false;
		
		$sql = "SELECT * FROM battle_teams WHERE battle_id = ? AND team_id = ? LIMIT 1";
		$q = $this->db->query($sql, array($battleId, $teamId));
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$team['name'] = $row['team_name'];
				$team['description'] = $row['team_description'];
				
			}
			
			$data['team'] = $team;
		}
		
		$sql = "SELECT * FROM battle_warriors WHERE battle_id = ? AND team_id = ? ORDER BY warrior_id DESC LIMIT 50 OFFSET ?";
		$q = $this->db->query($sql, array($battleId, $teamId, $offset));
		
		$team_ids = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$team_ids[] = $row['member_id'];
			}
			
			if(count($team_ids) > 0){
				$t = $this->User_model->getUsersInfo($team_ids);
				$data['users'] = $t['users'];
				$data['images'] = $t['images'];
				
				
			}
		}
		
		$data['team_ids'] = $team_ids;
		
		return $data;
	}
	
	public function getBattleTeams($battleId, $userId){
		$data['success'] = false;
		$sql = "SELECT * FROM battle_teams WHERE battle_id = ?";
		$q = $this->db->query($sql, array($battleId));
		$teams = array();
		$data['teams'] = array();
		$images = array();
		$users = array();
		
		$data['in_team'] = 0;
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$team['team_id'] = $row['team_id'];
				$team['creator_user_id'] = $row['creator_user_id'];
				$users[] = $team['creator_user_id'];
				$team['team_name'] = $row['team_name'];
				$team['team_description'] = $row['team_description'];
				$team['token'] = $row['token'];
				$team['team_card'] = $row['team_card'];
				if($team['team_card'] > 0){$images[] = $team['team_card'];}
				$team['team_members'] = array();
				if(strlen($row['team_members']) > 0){$team['team_members'] = explode(',', $row['team_members']); if(in_array($userId, $team['team_members'])){$data['in_team'] = $team['team_id'];}}
				$team['team_leaders'] = array();
				if(strlen($row['team_leaders']) > 0){$team['team_leaders'] = explode(',', $row['team_leaders']);}
				$team['status'] = $row['team_status'];
				
				$teams[$team['team_id']] = $team;
			}
			
			if(count($images) > 0){$images = $this->User_model->getImagesByIds(array_unique($images));}
			if(count($users) > 0){$users = $this->User_model->getUsersInfo(array_unique($users));}
		}
		
		
		$data['users'] = $users;
		$data['images'] = $images;
		$data['teams'] = $teams;
		
		return $data;
	}
	
	public function saveTeam(){
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['team_id']) && isset($_POST['token']) && isset($_POST['battle_id']) && isset($_POST['team_name']) && isset($_POST['team_description'])){
			$team_id = (int)$_POST['team_id'];
			$token = $_POST['token'];
			$battle_id = (int)$_POST['battle_id'];
			$team_name = $_POST['team_name'];
			$team_description = $_POST['team_description'];
			
			$sql = "UPDATE battle_teams SET team_name = ?, team_description = ? WHERE team_id = ? AND token = ? AND creator_user_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($team_name, $team_description, $team_id, $token, $userId));
			$data['success'] = true;
		}
		
		return $data;	
	}
	
	public function deleteTeam(){
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['team_id']) && isset($_POST['token']) && isset($_POST['battle_id'])){
			$team_id = (int)$_POST['team_id'];
			$token = $_POST['token'];
			$battle_id = (int)$_POST['battle_id'];
			
			$sql = "DELETE FROM battle_teams WHERE team_id = ? AND token = ? AND creator_user_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($team_id, $token, $userId));
			
			$data['success'] = true;
		}
		
		return $data;	
	}
	
	public function getBattleEntries($userId, $limit = 5, $noparties = 2){
		$sql = "SELECT * FROM images WHERE image_type = 'gallery' ORDER BY RAND() LIMIT ?";
		$q = $this->db->query($sql, array($limit * 2));
		
		$images = array();
		$battles = array();
		$users = array();
		
		for($i = 0; $i < $noparties; $i++){
			$battles[$i] = array();	
		}
		
		$current_party = 0;
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$image['id'] = (int)$row['image_id'];
				$image['user_id'] = (int)$row['user_id'];
				$users[] = $image['user_id'];
				$image['source'] = site_url() . "assets/images/users/" . $row['date_path'] . $row['source_file'];	
				$image['image_type'] = $row['image_type'];
				$image['title'] = $row['title'];
				$image['description'] = $row['description'];
				$image['token'] = $row['token'];
				$image['thread_id'] = $row['thread_id'];
				$image['thread_token'] = $row['thread_token'];
				
				$sizes = array();
				if(strlen($row['thumbs']) > 0){
					$thumbs_array = json_decode($row['thumbs']); 
					$thumbs = array();
					foreach($thumbs_array as $key => $value){
						$thumbs[$key] = site_url() . "assets/images/users/" . $row['date_path'] . $value;	
					}
					
					$image['sizes'] =  $thumbs;
					
					//$images_array[$row['image_id']] = $image;
					
				}
				
				$images[] = $image;
				$battles[$current_party][] = $image;
				$current_party++;
				if($current_party >= $noparties){$current_party = 0;}
			}
			
			$users = array_unique($users);
			if(count($users) > 0){
				$data['users'] = $this->User_model->getUsersInfo($users);	
			}
			
		}
		
		$data['battles'] = $battles;
		
		return $data;
		
	}
	
	public function generateVoteBattle($battle_id){
		/*
			For security reasons, each vote generates a token before being submitted to the user to select an image to avoid duplication or cheating. Once the user votes, the token is verified in the  with the database (pre_battle_vote) if confirmed, will submit into the image_battle_vote table.
			
			Users that are not logged in will be able to try the vote system but their votes will not be registered.
		*/
		
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		
	
		$this->clearUserBattles($userId);
		
		$limit = 100;
		if($userId < 1){$limit = 5;}
		$noparties = 2;
		$images = array();
		$battles = array();
		$users = array();
		$battleInfo = array();
		
		if(isset($_POST['noparties'])){$noparties = (int)$_POST['noparties'];}
		
		for($i = 0; $i < $noparties; $i++){ // sets the parties
			$battles[$i] = array();	
		}
		
		if(isset($_POST['battle_id'])){$battle_id = (int)$_POST['battle_id'];}
		
		if($battle_id > 0){ // if this is a specific battle ID
			$counter = 0;
			$sql = "SELECT * FROM battle_warriors WHERE battle_id = ? AND entry_media IS NOT NULL ORDER BY RAND() LIMIT ?";
			$q = $this->db->query($sql, array($battle_id, $limit * $noparties));
			
			$current_party = 0;
			
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					$entry['id'] = $row['warrior_id'];
					$entry['user_id'] = $row['member_id'];
					$users[] = $entry['user_id'];
					
					$media = json_decode($row['entry_media'], true);
					
					$entry['media_key'] = $media[0]['id'];
					$entry['media_type'] = $media[0]['media_type'];
					if($entry['media_type'] == 'image'){$images[] = $entry['media_key'];}
					$entry['team'] = $row['team_id'];
					
					
					$images[] = (int)$entry['media_key'];
					$battles[$current_party][] = (int)$entry['media_key']; //print_r($image);
					$im[$current_party] = (int)$entry['media_key'];
					$current_party++;
					
					if($current_party >= $noparties){ // this section below splits the gallery into the two sides.
						$current_party = 0;
						if($userId > 0){
							$info = $this->registerBattle($userId, $im[0], $im[1], $battle_id);
							
							
							$bi['pre_id'] = $info['pre_id'];
							$bi['token'] =  $info['token'];
						} else {
							$bi['pre_id'] = $counter;
							$bi['token'] = random_string('alnum', 10);
						}
						$bi['image1'] = $im[0];
						$bi['image2'] = $im[1];
						
						$battleInfo[] = $bi;
					}
					
					$counter++;
					
					
					
					$entries[] = $entry;
				}
				
				
			}
			$images = array_unique($images);
			
			if(count($images) > 0){$data['images'] = $this->User_model->getImagesByIds($images); }
			if(count($users) > 0){$data['users'] = $this->User_model->getUsersInfo($users);}
			//print_r($battleInfo);
			
			$data['battle_info'] = $battleInfo;
			$data['battles'] = $battles;
			$data['success'] = true;
		} else { // or if this is an open battle
		
			$counter = 0;
			$sql = "SELECT * FROM images WHERE image_type = 'gallery' ORDER BY RAND() LIMIT ?";
			$q = $this->db->query($sql, array($limit * $noparties));
			
			$current_party = 0;
			
			
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					$image['id'] = (int)$row['image_id'];
					$image['user_id'] = (int)$row['user_id'];
					$users[] = $image['user_id'];
					$image['source'] = site_url() . "assets/images/users/" . $row['date_path'] . $row['source_file'];	
					
					$image['image_type'] = $row['image_type'];
					$image['title'] = $row['title'];
					$image['description'] = $row['description'];
					$image['token'] = $row['token'];
					$image['thread_id'] = $row['thread_id'];
					$image['thread_token'] = $row['thread_token'];
					
					$sizes = array();
					if(strlen($row['thumbs']) > 0){
						$thumbs_array = json_decode($row['thumbs']); 
						$thumbs = array();
						foreach($thumbs_array as $key => $value){
							$thumbs[$key] = site_url() . "assets/images/users/" . $row['date_path'] . $value;	
						}
						
						$image['sizes'] =  $thumbs;
						
						//$images_array[$row['image_id']] = $image;
						
					}
					
					$images[] = $image;
					$battles[$current_party][] = $image; //print_r($image);
					$im[$current_party] = $image['id'];
					$current_party++;
					
					if($current_party >= $noparties){ // this section below splits the gallery into the two sides.
						$current_party = 0;
						if($userId > 0){
							$info = $this->registerBattle($userId, $im[0], $im[1], $battle_id);
							
							
							$bi['pre_id'] = $info['pre_id'];
							$bi['token'] =  $info['token'];
						} else {
							$bi['pre_id'] = $counter;
							$bi['token'] = random_string('alnum', 10);
						}
						$bi['image1'] = $im[0];
						$bi['image2'] = $im[1];
						
						$battleInfo[] = $bi;
					}
					
					$counter++;
				}
				
				$users = array_unique($users);
				if(count($users) > 0){
					$data['users'] = $this->User_model->getBasicUsersInfo($users);
				}
				
			}
			
			$data['battle_info'] = $battleInfo;
			$data['battles'] = $battles;
			
			$data['success'] = true;
		}
	
	
		
		
		return $data;	
	}
	
	public function clearUserBattles($userId){ // deletes all pre_vote entries by user.
		$sql = "DELETE FROM pre_battle_vote WHERE user_id = ?";
		$q = $this->db->query($sql, array($userId));
		
	}
	
	public function registerBattle($userId, $image1, $image2, $battleId){
		$token = random_string('alnum', 10);
		$sql = "INSERT INTO pre_battle_vote(user_id, image_1, image_2, token, battle_id) VALUES(?, ?, ?, ?, ?)";
		$q = $this->db->query($sql, array($userId, $image1, $image2, $token, $battleId));
			
		$data['pre_id'] = $this->db->insert_id();
		$data['token'] = $token;
		
		return $data;
		
	}
	
	public function saveBattleVote(){
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['pre_id']) && isset($_POST['token']) && isset($_POST['image_id'])){
			$pre_id = (int)$_POST['pre_id'];
			$token = $_POST['token'];
			$image_id = $_POST['image_id'];
			$team_id = 0; // if(isset($_POST['team_id'])){$team_id = (int)$_POST['team_id'];}
			
			$sql = "SELECT * FROM pre_battle_vote WHERE pre_id = ? AND token = ? AND user_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($pre_id, $token, $userId));
			
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					$image1 = $row['image_1'];
					$image2 = $row['image_2'];
					$battleId = $row['battle_id'];
					
					if($image_id == $image1 || $image_id == $image2){
						$images = $this->User_model->getImagesByIds(array($image_id)); 
						$user_winner = $images[$image_id]['user_id'];
						
						$sql = "INSERT INTO image_battle_vote(user_id, token, battle_id, team_id, image_1, image_2, image_winner, user_winner) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";	
						$q = $this->db->query($sql, array($userId, $token, $battleId, $team_id, $image1, $image2, $image_id, $user_winner));
						
						$data['vote_id'] = $this->db->insert_id();
						$data['success'] = true;
						
						$sql = "DELETE FROM pre_battle_vote WHERE pre_id = ? AND token = ? AND user_id = ? LIMIT 1";
						$q = $this->db->query($sql, array($pre_id, $token, $userId));
					}
				}
			}
		} else {
			$data['success'] = true;	
		}
		
		return $data;
	}
	
	public function getBattles($userId, $when = 'ongoing', $offset = 0){ // when can be (ongoing, future, past
	
		$where = array();
		$now = date("Y-m-d H:i:s"); 
		switch($when){
			case "ongoing":
				$where[] = " start_date < '{$now}' AND end_date > '{$now}' ";
			break;
			
			case "future":	
				$where[] = " start_date > '{$now}' ";
			break;
				
			case "past":
			$where[] = " end_date < '{$now}' ";
			break;
		}
		
		$where_string = "";
		if(count($when) > 0){$where_string = " WHERE " . implode(" AND ", $where);}
	
		$sql = "SELECT battle_id FROM battles $where_string";
		$q = $this->db->query($sql, array( ));
		
		$data['no_battles'] = $q->num_rows();
		
		$sql = "SELECT * FROM battles $where_string ORDER BY battle_id DESC LIMIT 20 OFFSET ? ";
		$q = $this->db->query($sql, array($offset));
		
		$users = array();
		$images = array();
		$battles = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$battle['battle_id'] = $row['battle_id'];
				$battle['user_id'] = $row['user_id'];
				$users[] = $row['user_id'];
				$battle['battle_name'] = $row['battle_name'];
				$battle['start_date'] = $row['start_date'];
				$battle['vote_date'] = $row['vote_date'];
				$battle['end_date'] = $row['end_date'];
				$battle['battle_short_description'] = $row['battle_short_description'];
				$battle['battle_type'] = $row['battle_type'];
				$categories = array();
				if(strlen($row['battle_categories']) > 0){
					$battle['categories'] = explode(",", $row['battle_categories']);	
				}
				$battle['territory'] = $row['territory'];
				$battle['battle_rules'] = $row['battle_rules'];
				$battle['battle_long_description'] = $row['battle_long_description'];
				$battle['admin_approved'] = $row['admin_approved'];
				$battle['online_status'] = $row['online_status'];
				$battle['visible_status'] = $row['visible_status'];
				
				$battle['hero_image'] = $row['battle_hero_image'];
				if($battle['hero_image'] > 0){$images[] = $battle['hero_image'];}
				
				$battle['logo_image'] = $row['battle_logo_image'];
				if($battle['logo_image'] > 0){$images[] = $battle['logo_image'];}
				
				$battle['card_image'] = $row['battle_image_card'];
				if($battle['card_image'] > 0){$images[] = $battle['card_image'];}
				
				$battles[] = $battle;
			}
			$users = array_unique($users);
			
			if(count($users) > 0){$data['users'] = $this->User_model->getUsersInfo($users);}
			if(count($images) > 0){$images = $this->User_model->getImagesByIds($images);}
		}
		
		$data['images'] = $images;
		$data['battles'] = $battles;
		
		return $data;
	}
	
	public function joinBattle(){
		$data['success'] = false;
		
		$userId = $this->User_model->getUserId();
		if($userId > 0 && isset($_POST['battle_id']) && isset($_POST['team_id'])){
			$dictionary = $this->User_model->getDictionary();
			
			$battleId = (int)$_POST['battle_id'];
			$teamId = (int)$_POST['team_id'];
			
			$errors = array();
			
			$battle = $this->Battle_model->getBattle($battleId);
			if($battleId > 0){
				$teams = $this->Battle_model->getBattleTeams($battleId, $userId);
			}
			$is_teams = false;
			if($battle['battle']['battle_type'] == 'team'){ $is_teams = true; // verifies that the battle format is based on teams.
				if($is_teams && $teamId < 1){$errors[] = "must provide a team id";}
				
				if($is_teams && $teamId > 0 && isset($teams['teams'][$teamId])){  // if this is a team format, and the team does exist in that battle
					if($this->verifyMemberinteam($userId, $battleId) < 1){ // verifies if member is not already in a team
						$time_left = strtotime($battle['battle']['vote_date']) - time();
						$days_left = ceil($time_left / 86400);
						
						if($time_left > 0 || $battle['battle']['battle_and_vote'] == 1){ // verifies if it is not too late to vote or if this is a battle and vote scenario.
							$sql = "INSERT INTO battle_warriors(member_id, battle_id, team_id) VALUES(?, ?, ?)";
							$q = $this->db->query($sql, array($userId, $battleId, $teamId));
							
							$data['battle_id'] = $this->db->insert_id();
							
							$data['team_size'] = $this->addMemberToTeam($teamId, $userId);
							
							$data['joined'] = $this->functions->_e("joined", $dictionary);
							$data['message'] = $this->functions->_e("congrats you have now joined this team", $dictionary);
							$data['success'] = true;
						}
						
					} else {  // if the member is already 
						$errors[] = "already in a team";
					}
				}
			
			} else { // if this is a single battle.
				if($this->verifyMemberinteam($userId, $battleId) > 0){// already in battle.
				$errors[] = "already registered";
				} else { // not yet in battle ACTION: add to battle.
					$sql = "INSERT INTO battle_warriors(member_id, battle_id) VALUES(?, ?)";
					$q = $this->db->query($sql, array($userId, $battleId));
					
					$data['warrior_id'] = $this->db->insert_id();
					$data['joined'] = $this->functions->_e("joined", $dictionary);
					$data['message'] = $this->functions->_e("congrats you have now joined this battle", $dictionary);
					$data['success'] = true;
				}
			}
			
			$data['errors'] = $errors;
		}
		
		
		return $data;	
	}
	
	public function verifyMemberinteam($userId, $battleId){
		$sql = "SELECT warrior_id FROM battle_warriors WHERE member_id = ? AND battle_id = ? LIMIT 1";
		$q = $this->db->query($sql, array($userId, $battleId));
		
		return $q->num_rows();
	}
	
	public function addMemberToTeam($teamId, $memberId, $type = 'team_members'){
		
		$teamsize = 0;
		
		$sql = "SELECT team_members, team_leaders FROM battle_teams WHERE team_id = ? LIMIT 1";
		$q = $this->db->query($sql, array($teamId));
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				
				$members = array();
				$leaders = array();
				
				$team_members = $row['team_members'];
				$team_leaders = $row['team_leaders'];
				
				if(strlen($team_members) > 0){$members = explode(',', $team_members);}
				if(strlen($team_leaders) > 0){$leaders = explode(',', $team_leaders);}	
				
				if($type == 'team_members'){
					$members[] = $memberId;
					
					$members = array_unique($members);	
					$team_string = implode(',', $members);
					
					$teamsize = count($team_members);
					
					$sql = "UPDATE battle_teams SET team_members = ? WHERE team_id = ? LIMIT 1";
					$q = $this->db->query($sql, array($team_string, $teamId));
				}
				
				if($type == 'team_leaders'){
					$leaders[] = $memberId;
					
					$leaders = array_unique($leaders);	
					$team_string = implode(',', $leaders);
					
					$sql = "UPDATE battle_teams SET team_leaders = ? WHERE team_id = ? LIMIT 1";
					$q = $this->db->query($sql, array($team_string, $teamId));
					
					$teamsize = count($team_leaders);
				}
			}	
		}
		
		return $teamsize;
		
	}
	
	public function getTeamMembers($teamId, $type = 'members'){
		$sql = "SELECT team_members, team_leaders FROM battle_teams WHERE team_id = ? LIMIT 1";
		$q = $this->db->query($sql, array($teamId));
		
		$members = array();
		$leaders = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				if(strlen($row['team_members']) > 0){$members = explode(',', $row['team_members']);}
				if(strlen($row['team_leaders']) > 0){$leaders = explode(',', $row['team_leaders']);}
			}
		}
		
		if($type == 'members'){return $members;}
		if($type == 'leaders'){return $leaders;}
	}
	
	public function removeTeamMember(){
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['battle_id']) && isset($_POST['team_id'])){ 
			$dictionary = $this->User_model->getDictionary();
			$battleId = (int)$_POST['battle_id'];
			$teamId = (int)$_POST['team_id'];
			
			$sql = "DELETE FROM battle_warriors WHERE member_id = ? AND battle_id = ? AND team_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($userId, $battleId, $teamId));
			$data['affected_rows'] = $this->db->affected_rows();
			
			$teamMembers = $this->getTeamMembers($teamId);
			
			if(count($teamMembers) > 0 && in_array($userId, $teamMembers)){ 
				$newArray = array();
				
				foreach($teamMembers as $i){ 
					if($i != $userId){$newArray[] = $i;}	
				}	
				
				$teamString = implode(',', $newArray);
				
				$sql = "UPDATE battle_teams SET team_members = ? WHERE team_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($teamString, $teamId));
				$data['team_size'] = count($teamMembers);
				$data['message'] = $this->functions->_e("you left this team", $dictionary);
				$data['success'] = true;
			}
		
		}
		
		return $data;
	}
	
	public function getBattleWarriors($battleId, $offset = 0){
		$data['success'] = false;
		
		$sql = "SELECT * FROM battle_warriors WHERE battle_id = ? ORDER BY warrior_id DESC LIMIT 50 OFFSET ?";
		$q = $this->db->query($sql, array($battleId, $offset));
		
		
		$warriors = array();
		$users = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$users[] = $row['member_id'];
				$warrior['warrior_id'] = $row['warrior_id'];
				
				$warriors[] = $warrior;
			}
			
			//$users = array_unique($users);
		}
		
		if(count($users) > 0){
			$data['users'] = $this->User_model->getUsersInfo($users);
		}
		
		$data['warriors'] = $users;
		
		
		return $data;
	}
	
	public function getAllBattleEntries($battleId, $offset= 0){ // get all entries into a battle
		$data['success'] = false;
		
		$sql = "SELECT * FROM battle_warriors WHERE battle_id = ? ORDER BY warrior_id DESC LIMIT 50 OFFSET ?";
		$q = $this->db->query($sql, array($battleId, $offset));
		
		$images = array();
		$warriors = array();
		$users = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$users[] = $row['member_id'];
				$warrior['member_id'] = $row['member_id'];
				$warrior['warrior_id'] = $row['warrior_id'];
				$warrior['entries'] = array();
				$entries_string = $row['entry_media'];
				if(strlen($entries_string) > 0 && $entries_string != 'null'){
					$entries = json_decode($entries_string, true);
					$warrior['entries'] = $entries;
					foreach($entries as $e){
						$m = array();
						$m['type'] = $e['media_type'];
						$m['id'] = $e['id'];
						
						if($m['type'] == 'image'){$images[] = $m['id'];}	
						
						$media[] = $m;
					}
					$data['media'] = $media;
					
					
					
				}
				
				$warriors[] = $warrior;
			}
			
			//$users = array_unique($users);
		}
		
		if(count($users) > 0){
			$data['users'] = $this->User_model->getUsersInfo($users);
		}
		
		if(count($images) > 0){
			$data['images'] = $this->User_model->getImagesByIds($images);
		}
		
		$data['warriors'] = $warriors;
		
		
		return $data;
		
	}
	
	public function getBattleEntry($userId, $battleId){ // get a user's own etries
		$entry = array();
		$sql = "SELECT * FROM battle_warriors WHERE member_id = ? AND battle_id = ? LIMIT 1";
		$q = $q = $this->db->query($sql, array($userId, $battleId));
		
		$data['media'] = array();
		
		$media = array();
		$images = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){ 
				$data['warrior_id'] = $row['warrior_id'];
				$data['team_id'] = $row['team_id'];
				//$data['is_judge'] = $row['is_judge'];
				//$data['is_mentor'] = $row['is_mentor'];
				$data['team_status'] = $row['team_status'];
				$data['pending_approval'] = false;
				//if(($data['is_mentor'] > 0 && $data['team_status'] < 1) || ($data['is_judge'] > 0 && $data['team_status'] < 1)){$data['pending_approval'] = true;}
				$data['string'] = $row['entry_media'];
				$entries_string = $row['entry_media'];
				if(strlen($entries_string) > 0 && $entries_string != 'null'){
					$entries = json_decode($entries_string);
					
					foreach($entries as $e){
						$m = array();
						$m['type'] = $e->media_type;
						$m['id'] = $e->id;
						
						if($m['type'] == 'image'){$images[] = $m['id'];}	
						
						$media[] = $m;
					}
					$data['media'] = $media;
					if(count($images) > 0){
						$data['images'] = $this->User_model->getImagesByIds($images);
					}
					
					
				}
				
			}
			
		}
		
		return $data;
	}
	
	public function getSingleEntry($warriorId, $battleId){ // get a user's own etries
		$entry = array();
		$sql = "SELECT * FROM battle_warriors WHERE warrior_id = ? AND battle_id = ? LIMIT 1";
		$q = $q = $this->db->query($sql, array($warriorId, $battleId));
		
		$data['media'] = array();
		
		$media = array();
		$images = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){ 
				$data['warrior_id'] = $row['warrior_id'];
				$data['team_id'] = $row['team_id'];
				$data['string'] = $row['entry_media'];
				$entries_string = $row['entry_media'];
				if(strlen($entries_string) > 0 && $entries_string != 'null'){
					$entries = json_decode($entries_string, true);
					$data['entries'] = $entries;
					foreach($entries as $e){
						$m = array();
						$m['type'] = $e['media_type'];
						$m['id'] = $e['id'];
						
						if($m['type'] == 'image'){$images[] = $m['id'];}	
						
						$media[] = $m;
					}
					$data['media'] = $media;
					if(count($images) > 0){
						$data['images'] = $this->User_model->getImagesByIds($images);
					}
					
					
				}
				
			}
			
		}
		
		return $data;
	}
	
	public function saveMediaToBattleEntry(){
		$data['success'] = false;
		
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['battle_id']) && isset($_POST['media_string'])){ 
			$media = json_decode($_POST['media_string']);
			$battleId = (int)$_POST['battle_id'];
			
			$sql = "UPDATE battle_warriors SET entry_media = ? WHERE battle_id = ? AND member_id = ? LIMIT 1";
			$q = $this->db->query($sql, array(json_encode($media), $battleId, $userId));
			
			$data['success'] = true;
		}
		
		return $data;
	}
	
	public function addBattleLeader(){
		$data['success'] = false;
		$data['status'] = 0;
		$userId = $this->User_model->getUserId(); $data['userid'] = $userId;
		if($userId > 0 && isset($_POST['battle_id']) && isset($_POST['member_id'])){ $data['status'] = 1;
			$battleId = (int)$_POST['battle_id'];
			$memberId = (int)$_POST['member_id'];
			$teamId = 0;
			if(isset($_POST['team_id'])){$teamId = (int)$_POST['team_id'];}
			
			$title = "mentor";
			$allowed_titles = array("mentor","judge","battle_leader");
			if(isset($_POST['title']) && in_array($_POST['title'], $allowed_titles)){$title = $_POST['title']; }
			$data['status'] = 2;
			$sql = "SELECT role_id FROM battle_role WHERE battle_id = ? AND member_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($battleId, $memberId));
			$data['no_rows'] = $q->num_rows();
			if($q->num_rows() < 1){ $data['status'] = 3;
				$sql = "INSERT INTO battle_role(battle_id, member_id, team_id, role_type) VALUES(?, ?, ?, ?)";	
				$q = $this->db->query($sql, array($battleId, $memberId, $teamId, $title));
				
				$extra['role_id'] = $this->db->insert_id();
				$data['success'] = true;
				$extra['battle_id'] = $battleId;
				$extra['team_id'] = $teamId;
				$extra['title'] = $title;
				
				$this->User_model->addNotification($userId, $memberId,'battle_leader', $extra);
			} else {
				$sql = "UPDATE battle_role SET team_id = ? WHERE battle_id = ? AND member_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($teamId, $battleId, $memberId));
				
				$data['success'] = true;	
			}
			$data['query'] = $this->db->last_query();
			
			
			
			
			
		}
		
		return $data;
	}
	
	public function getInvitations($userId, $battleId){
		$role = array();
		$role['pending'] = 0;
		$sql = "SELECT * FROM battle_role WHERE battle_id = ? AND member_id = ? LIMIT 1";
		$q = $this->db->query($sql, array($battleId, $userId));
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$role['role_id'] = $row['role_id'];
				$role['pending'] = 1;
				$role['status'] = $row['invitation_status'];
				$role['role_type'] = $row['role_type'];
				$role['invitation_time'] = $this->User_model->formatTime($row['invitation_time']);
			}
		}
		
		return $role;
		
	}
	
	public function respondBattleInvitation(){ // response to request to become judge 
		$data['success'] = false;
		
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['role_id']) && isset($_POST['response']) && isset($_POST['battle_id'])){
			$role_id = (int)$_POST['role_id'];
			$response = (int)$_POST['response'];
			
			if($response	 == 1){ // if response is yes, change invitation_status to 1
				$sql = "UPDATE battle_role SET invitation_status = 1 WHERE role_id = ? AND member_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($role_id, $userId));
			} else { // if response is false, then delete invitation.
				$sql = "DELETE FROM battle_role WHERE role_id = ? AND member_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($role_id, $userId));
			}
			$data['success'] = true;
		}
		
		
		return $data;	
	}
	
	public function getBattleLeaders($battleId, $teamId = 0, $editable = false){
		$users = array();
		$judges = array();
		$mentors = array();
		
		$status_string = "";
		if(!$editable){$status_string = " AND invitation_status = 1";}
		
		if($teamId < 1){
			$sql = "SELECT * FROM battle_role WHERE battle_id = ?" . $status_string; 
			$q = $this->db->query($sql, array($battleId));
		} else {
			$sql = "SELECT * FROM battle_role WHERE battle_id = ? AND team_id = ?" . $status_string;
			$q = $this->db->query($sql, array($battleId, $teamId));

		}
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$leader['member_id'] = $row['member_id'];
				$leader['role_id'] = $row['role_id'];
				$leader['team_id'] = $row['team_id'];
				$leader['role_type'] = $row['role_type'];
				$leader['invitation_tatus'] = $row['invitation_status'];
				$users[] = $row['member_id'];
				if($leader['role_type'] == 'judge'){$judges[] = $leader;}
				if($leader['role_type'] == 'mentor'){$mentors[] = $leader;}
			}
			
			$users = array_unique($users);
		}
		
		$data['judges'] = $judges;
		$data['mentors'] = $mentors;
		if(count($users) > 0){$data['users'] = $this->User_model->getUsersInfo($users);}
		
		
		return $data;
	}
	
	public function getJudges($battleId, $editable){/*
		$data['success'] = false;
		
		$users = array();
		$not_confirmed_string = " AND team_status = 1 ";
		if($editable){$not_confirmed_string = "";}
		$sql = "SELECT * FROM battle_warriors WHERE battle_id = ? AND is_judge = 1 {$not_confirmed_string} ORDER BY warrior_id DESC";
		$q = $this->db->query($sql, array($battleId));
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$judge['member_id'] = $row['member_id'];
				$users[] = $judge['member_id'];
				$judge['status'] = $row['team_status'];
				$judges[] = $judge;
			}
			
			$users = array_unique($users);
			
			$data['judges'] = $judges;
			$data['success'] = true;
			if(count($users) > 0){$data['users'] = $this->User_model->getUsersInfo($users);}
		}
		
		return $data;
	*/}
	
	public function removeBattleLeader(){ 
		$data['success'] = false;
		
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['battle_id']) && isset($_POST['leader_id'])){
			$battleId = (int)$_POST['battle_id'];	
			$roleId = (int)$_POST['leader_id'];
			
			$battle = $this->getBattle($battleId);
			if($battle['battle']['user_id'] == $userId){
				 $sql = "DELETE FROM battle_role WHERE role_id = ? AND battle_id = ? LIMIT 1";
				 $q = $this->db->query($sql, array($roleId, $battleId)); 
				 
				 $data['success'] = true;
			}
			
			
		}
		
		
		return $data;	
	}
	
}
?>