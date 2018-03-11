<?php 
class user_model extends CI_Model {

    function __construct(){
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
    }
	
	public function setUserDefault(){
		$userId = $this->getUserId();
		
		if(isset($_COOKIE['user_lang'])){
				
		} else {
			$userData['lang'] = "english";
			
			if(LIVE_SERVER){
				$ip = $_SERVER['REMOTE_ADDR']; // for live server
			} else {
				$ip = "72.55.156.29"; // for development machine
			}
			$target_url = "http://ipinfo.io/{$ip}";
			$html = file_get_contents($target_url);
			$data = json_decode($html);
			
			
			if($data->country == "FR" || $data->region == "Quebec"){$userData['lang'] = "french";}
			if(in_array($data->country, array("MX", "CO","ES","AR","PE","VE","CL","EC","GT","","CU","BO","DO","HN","PY","SV","NI","CR","PR"))){$userData['lang'] = "spanish";}
			if($data->country == "CN"){$userData['lang'] = "mandarin";}
			
			$userData['city'] = $data->city;
			$userData['country'] = $data->country;
			$userData['region'] = $data->region;
			$userData['loc'] = $data->loc;
			
			
			foreach($userData as $key => $value){
				$expire = 31536000;
				if($key == 'user_lang'){$expire = 604800;}
				$cookie = array(
	                   'name'   => $key,
	                   'value'  => $value,
	                   'expire' => $expire,
	                   'path'   => '/',
	                   'prefix' => 'user_',
						);
						
						set_cookie($cookie);
			}
			header("Refresh:0");
		}
	}
	
	public function register_user(){
		$data['success'] = false;
		$data['errors'] = array(); 
		//$options = $this->User_model->getOptions();
		if(isset($_POST['user_first_name']) && isset($_POST['user_last_name']) && isset($_POST['user_email']) && isset($_POST['user_password'])){ 
			$errors = 0;
			$first_name = $_POST['user_first_name'];
			$last_name = $_POST['user_last_name'];
			$email = $_POST['user_email'];
			$country = ""; if(isset($_POST['user_country'])){$country = $_POST['user_country'];}
			$city = ""; if(isset($_POST['user_city'])){$country = $_POST['user_city'];}
			$password = $_POST['user_password'];
			
			$reg = $this->register($first_name, $last_name, $email, $password, $data['errors']);
			$data['success'] = $reg['success'];
			$data['errors'] = $reg['errors'];
			if($data['success']){
				$name = $first_name . " " . $last_name;
				$data['link'] = site_url() . "member/home/{$reg['id']}/" . urlencode($first_name);
				$data['id'] = $reg['id'];
			}
			
			
		}
		
		return $data;	
	}
	
	public function register($first_name, $last_name, $email, $password, $errors, $invited_by = 0){ 
			
			$data['errors'] = $errors;
			$data['success'] = false;
			$salt = random_string('alnum', 16);
			$token = random_string("alnum", 60);
			$salted_password = md5($salt . $password);
			
			$contact['contact_mobile'] = '';
			$contact['contact_work_phone'] = '';
			$contact['contact_user_skype'] = '';
			$contact['contact_fax'] = '';
			$contact['contact_address'] = '';
			if(isset($_COOKIE['user_city'])){$contact['contact_address'] = $_COOKIE['user_city'];}
			if(isset($_POST['user_city'])){$contact['contact_address'] = $_POST['user_city'];}
			if(isset($_POST['contact_work_phone'])){$contact['contact_work_phone'] = $_POST['contact_work_phone'];}
			
			$contactstring = serialize($contact);
			
			$emailValid = $this->validateEmail($email); 
			if(!$emailValid){$data['errors'][] = "Invalid Email.";} else {
				if($this->checkEmailExist($email) > 0){
					$data['errors'][] = "Email already exists";	
				}
			} 
			
			//echo $salt . " : " . $password . " : " . $salted_password;
			
			if(count($data['errors']) < 1){ 
				
				if(false){ //echo $options['pre_register']; // if pre-registration is active.
					
					$sql = "INSERT INTO temp_users(first_name, last_name, email, hash_password, salt, contact_data, token) VALUES(?, ?, ?, ?, ?, ?, ?)";
					$q = $this->db->query($sql, array($first_name, $last_name, $email, $salted_password, $salt, $contactstring, $token));
					
					$temp_id = $this->db->insert_id();
					
					$user['id'] = $temp_id;
					$user['token'] = $token;
					$user['first_name'] = $first_name;
					$user['last_name'] = $last_name;
					$user['email'] = $email;
					
					$this->preregEmailConfirmation($user);
				} else {// if pre registration is not active.
				 
					$sql = "INSERT INTO users(email, first_name, last_name, hash_password, salt, contact_data) VALUES(?, ?, ?, ?, ?, ?)";
					$q = $this->db->query($sql, array($email, $first_name, $last_name, $salted_password, $salt, $contactstring));
					//echo $this->db->last_query();
					$data['id'] = $this->db->insert_id();
					//$data['pre_register'] = $options['pre_register'];
					
					if($invited_by > 0){
						$this->	addReferenceToUser($data['id'], $invited_by);
					}
					$this->createSession($data['id']);
					
					if($invited_by  > 0 && $this->uri->segment(3) && $this->uri->segment(4)){
						
						$inviteId = (int)$this->uri->segment(3);
						$invite_token = $this->uri->segment(4);
						$sql = "UPDATE user_invite SET new_user_id = ?  WHERE invite_id = ? AND token = ? LIMIT 1000";
						$q = $this->db->query($sql, array($data['id'], $inviteId, $invite_token));
					}
					
					
					
				}
				$data['success'] = true;
			}
			
			return $data;
	}
	
	public function user_login(){
		$data['success'] = false;
		if(isset($_POST['user_email']) && isset($_POST['user_password'])){$data['return'] = 1;
			$email = $_POST['user_email'];
			$password = $_POST['user_password'];
			$sql = "SELECT user_id, hash_password, salt FROM " . DB_PREFIX . "users WHERE email = ? LIMIT 1";
			$q = $this->db->query($sql, array($email));
			
			if($q->num_rows() > 0){ $data['return'] = 2;
				foreach($q->result_array() as $row){
					$salt = $row['salt'];
					
					$hash_password = $row['hash_password'];
					
					
					$saltedSubmittedPassword = md5($salt . $password);
					$salted_password = md5($salt . $password);
					
					$data['passwords'] = $hash_password . " : " . $saltedSubmittedPassword;
					
					if($hash_password == $saltedSubmittedPassword){
						$data['return'] = 3;
						$this->createSession($row['user_id']);
						$data['success'] = true;
					}
				}	
			}
		}
		
		return $data;
	}
	
	public function preregEmailConfirmation($data){
		
		$id = $data['id'];
		$token = $data['token'];
		$email = $data['email'];
		$firstname = $data['first_name'];
		
		$url = site_url() . "user/confirmsignup/{$id}/{$token}";
		$siteName = SITE_NAME;
		$bigSiteName = strtoupper($siteName);
		$subject = $siteName . " - Confirm Registration";
		$messageString = "";
		$messageString .= "<p style='color: #333;'>Hello {$firstname},</p>";
		$messageString .= "<p style='color: #333;'>Thank you for your interest in {$siteName}, the Online Social Network for Professionals.</p><p>Follow this link to complete your registration: <a href=\"{$url}\" >Confirm Registration</a> or copy this link into your browser's URL field. {$url}</p><p style='color: #333;'>We look forward meeting you up online!</p><p style='font-weight: bold; color: #333;'>{$bigSiteName} TEAM</p>";
		
		//$this->sendEmail(0, $messageString, $subject, $email);
		
		$this->load->library('email');
		
		$config['mailtype'] = 'html';
	
		$this->email->initialize($config);
		
		$this->email->from('noreply@lacremecreative.com', "Feedback");
		$this->email->to($email); 
		
		
		$this->email->subject(SITE_NAME . ' - Registration');
		$this->email->message($messageString);
		//$this->email->alt_message('Hello World');	
		
		$this->email->send();
		
		/*$this->load->library('email');

		$config['mailtype'] = 'html';
		
		$this->email->initialize($config);

		$this->email->from('noreply@lacremecreative.com', "Feedback");
		$this->email->to($email); 
		
		echo 1;
		$this->email->subject(SITE_NAME . ' - Registration');
		$this->email->message($messageString);
		//$this->email->alt_message('Hello World');	
		echo 2;
		$this->email->send();
		
		echo 3;*/
	}
	
	public function createSession($userId){
		$session_key =  random_string('alnum', 80);
		
		$sql = "INSERT INTO user_sessions (user_id, session_key) VALUES(?, ?)";
		$q = $this->db->query($sql, array($userId, $session_key));
		
		$sessionId = $this->db->insert_id();
		
		$cookie = array(
	                   'name'   => 'session_id',
	                   'value'  => $sessionId,
	                   'expire' => '31536000',
	                   'path'   => '/',
	                   'prefix' => 'user_',
						);
						
						set_cookie($cookie);
						
						$cookie = array(
	                   'name'   => 'session_key',
	                   'value'  => $session_key,
	                   'expire' => '2592000',
	                   'path'   => '/',
	                   'prefix' => 'user_',
						);
						
						set_cookie($cookie);
	}
	
	public function checkEmailExist($email, $userId = 0){
		$where = "";
		if($userId > 0){$where = " AND user_id <> " . $userId;}
		
		$data = 0;
		$sql = "SELECT email FROM users WHERE email = ?{$where} LIMIT 1";	
		$q = $this->db->query($sql, array($email));
		return $q->num_rows();
		
		$this->addReferenceToUserAccount($userId);
		
		return $data;
	}
	
	public function validateEmail($email){
		$v = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/";

		return (bool)preg_match($v, $email);	
	}
	
	
	
	public function user_logout(){
		$return['success'] = false;
		
		$userId = (int)$this->User_model->getUserId();
		if($userId > 0){
		
			$sql = "DELETE FROM user_sessions WHERE user_id = ?";
			$q = $this->db->query($sql, $userId);
			
			$cookie = array(
	                   'name'   => 'session_id',
	                   'value'  => '',
	                   'expire' => '0',
	                   'path'   => '/',
	                   'prefix' => 'user_',
						);
						
						set_cookie($cookie);
						
						$cookie = array(
	                   'name'   => 'session_key',
	                   'value'  => '',
	                   'expire' => '0',
	                   'path'   => '/',
	                   'prefix' => 'user_',
						);
						
						set_cookie($cookie);
			
			$return['success'] = true;
		}
		return $return;
	}
	
	public function getUserId(){
	
		if(get_cookie('user_session_id') && get_cookie('user_session_key')){
			$sessionId = (int)get_cookie('user_session_id');
			$session_key = get_cookie('user_session_key');
			
			$sql = "SELECT user_id FROM user_sessions WHERE user_session_id = ? AND session_key = ? LIMIT 1";
			$q = $this->db->query($sql, array($sessionId, $session_key));
			
			if($q->num_rows() > 0) {
				foreach($q->result_array() as $row){
					$userId = (int)$row['user_id'];
				}
				return $userId;
			} else {return 0;}
		} else {return 0;}
		
	}
	
	public function getBasicUsersInfo($userIds, $status = true){
		$wherestring = " WHERE user_id IN (" . implode(',', $userIds) . ")";
		if(!$status){$wherestring .= " AND profile_status = 1 ";}
		$limitstring = " LIMIT " . count($userIds);
		
		$sql = "SELECT * FROM users " . $wherestring . $limitstring;
		$q = $this->db->query($sql);
		
		$images = array();
		$users = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$id = $row['user_id'];
				$user['id'] = $id;
				
				$user['name'] = $row['first_name'] . " " . $row['last_name'];
				$user['first_name'] = $row['first_name']; 
				$user['last_name'] = $row['last_name'];
				
				
				$user['hero_id'] = (int)$row['hero_id']; if($user['hero_id'] > 0){$images[] = $user['hero_id']; }
				if($row['account_type'] == 'tribe'){
					$user['avatar_id'] = $row['hero_id']; if($user['hero_id'] > 0){$images[] = $user['hero_id']; }
				} else {
					$user['avatar_id'] = $row['avatar_id']; if($user['avatar_id'] > 0){$images[] = $user['avatar_id']; }
				}
				
				
				$users[$id] = $user;
			}
		}
		
		$data['users'] = $users;
		$data['images'] = array();
		
		if(count($images) > 0){$data['images'] = $this->getImagesByIds($images); }
		
		return $data;
	}
	
	public function getUsersInfo($userIds, $status = true){ 
		$wherestring = " WHERE user_id IN (" . implode(',', $userIds) . ")";
		if(!$status){$wherestring .= " AND profile_status = 1 ";}
		$limitstring = " LIMIT " . count($userIds);
		
		$sql = "SELECT * FROM users " . $wherestring . $limitstring;
		$q = $this->db->query($sql);
		
		$images = array();
		$users = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$id = $row['user_id'];
				$user['id'] = $id;
				
				$user['name'] = $row['first_name'] . " " . $row['last_name'];
				$user['first_name'] = $row['first_name']; 
				$user['last_name'] = $row['last_name'];
				$user['email'] = $row['email'];
				$user['email_verified'] = $row['email_verified'];
				$user['title'] = $row['title'];
				$user['birthday'] = $row['birthday'];
				$user['username'] = $row['username'];
				$user['premium_account'] = false;
				$now = time(); // or your date as well
				$your_date = strtotime($row['premium_account']);
				$datediff = $your_date - $now;
				$user['premium_expiration_date'] = $row['premium_account'];
				$user['premium_expires'] =  floor($datediff / (60 * 60 * 24));
				if($user['premium_expires'] > 0){$user['premium_account'] = true;}
				$user['description'] = $row['biography'];
				$user['contact'] = unserialize($row['contact_data']);
				$user['skills'] = $row['skills'];
				$user['technology_skills'] = $row['technology_skills'];
				$user['link'] = site_url() . "member/home/{$user['id']}/" . urlencode($user['name']);
				$user['master_account'] = array();
				$user['following'] = $row['following'];
				$user['followed_by'] = $row['followed_by'];
				$user['reputation'] = $row['reputation'];
				$user['account_type'] = $row['account_type'];
				$user['nationality'] = $row['user_nationality'];
				$user['is_battle_master'] = $row['is_battle_master'];
				$user['languages'] = $row['user_languages'];
				$user['creation_time'] = $row['creation_time'];
				if($row['account_type'] != 'member'){ // if not employee
					$user['name'] = $row['business_name']; 
					
					if(strlen($row['master_account']) > 0){
					$user['master_account'] = explode(',', $row['master_account']);}
					$user['link'] = site_url() . "business/home/{$user['id']}/" . urlencode($user['name']);
					$user['no_employees'] = $row['no_employees'];
					$user['business_array'] = array();
					$user['business_ids'] = $row['business_ids']; 
				
				} else {
					$user['business_array'] = array();
					$user['business_ids'] = $row['business_ids']; 
					
					if(strlen($row['business_ids']) > 0){
						$user['business_array'] = explode(',', $user['business_ids']);
					}
					
					
					
				}
				
				if($row['account_type'] == 'business'){
					$user['business_array'] = array();
					$user['business_ids'] = $row['business_ids']; 
					
					if(strlen($row['business_ids']) > 0){
						$user['business_array'] = explode(',', $user['business_ids']);
					}
				}
				
				$address = $row['address'];
				if(strlen($address) > 0){
					$geo = json_decode($address);
					if(isset($geo->country)){$user['country'] = $geo->country;}
					if(isset($geo->state)){$user['state'] = $geo->state;}
					if(isset($geo->city)){$user['city'] = $geo->city;}
					if(isset($geo->zip)){$user['zip'] = $geo->zip;}
					if(isset($geo->phone)){$user['phone'] = $geo->phone;}
					if(isset($geo->fax)){$user['fax'] = $geo->fax;}
					if(isset($geo->web)){$user['web'] = $geo->web;}
					
				} else {
					$user['country'] = "";
					$user['state'] = "";
					$user['city'] = "";
					$user['zip'] = "";
					$user['phone'] = "";
					$user['fax'] = "";
					$user['web'] = "";
				}
				
				$user['lat'] = $row['location_lat'];
				$user['lng'] = $row['location_lng'];
				
				$user['admin_level'] = $row['admin_level'];
				
				
				$user['hero_id'] = (int)$row['hero_id']; if($user['hero_id'] > 0){$images[] = $user['hero_id']; }
				if($row['account_type'] == 'tribe'){
					$user['avatar_id'] = $row['hero_id']; if($user['hero_id'] > 0){$images[] = $user['hero_id']; }
				} else {
					$user['avatar_id'] = $row['avatar_id']; if($user['avatar_id'] > 0){$images[] = $user['avatar_id']; }
				}
				//$user['images'] = $row['images'];
				//$user['image_array'] = array();
				//if(strlen($row['images']) > 0){$user['image_array'] = explode(',', $row['images']); 
				
				//$user['status'] = $row['profile_status'];
				
				$users[$id] = $user;
			}
		}
		
		$data['users'] = $users;
		$data['images'] = array();
		
		if(count($images) > 0){$data['images'] = $this->getImagesByIds($images); }
		
		return $data;
		
	}
	

	
	public function getImagesByIds($images){
		$images_array = array();
		if(count($images) > 0){
			$imagesIdString = implode(",", $images);
			$sql = "SELECT * FROM images WHERE image_id IN ({$imagesIdString})";
			$q = $this->db->query($sql);
			
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					$image['id'] = (int)$row['image_id'];
					$image['user_id'] = (int)$row['user_id'];
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
						
						$images_array[$row['image_id']] = $image;	
					}
					
					
				}	
				
				
			}
			
		}
		return $images_array;
	}
	
	public function getUserImages($userId, $type=""){
		$type_string = "";
		if(strlen($type) > 0){ // if a type is specified
			$sql = "SELECT * FROM images WHERE user_id = ? AND image_type = ? ORDER BY image_id DESC";
			$q = $this->db->query($sql, array($userId, $type));
		} else {
			$sql = "SELECT * FROM images WHERE user_id = ? ORDER BY image_id DESC";
			$q = $this->db->query($sql, array($userId));
		}
		$images_array = array();
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
					$image['id'] = (int)$row['image_id'];
					$image['user_id'] = (int)$row['user_id'];
					$image['source'] = site_url() . "assets/images/users/" . $row['date_path'] . $row['source_file'];	
					$image['image_type'] = $row['image_type'];
					$image['token'] = $row['token'];
					$sizes = array();
					if(strlen($row['thumbs']) > 0){
						$thumbs_array = json_decode($row['thumbs']); 
						$thumbs = array();
						foreach($thumbs_array as $key => $value){
							$thumbs[$key] = site_url() . "assets/images/users/" . $row['date_path'] . $value;	
						}
						
						$image['sizes'] =  $thumbs;
						
						$images_array[$row['image_id']] = $image;	
					}
					
					
				}
		}
		
		return $images_array;
	}
	
	public function saveImageSelection(){
		$data['success'] = false;
		
		$userId = $this->getUserId();
		if($userId > 0 && isset($_POST['image_ids']) && isset($_POST['image_type'])){ 
		
		
			$uInfo = $this->getUsersInfo(array($userId)); $userInfo = $uInfo['users'][$userId];
			
			$memberId = $userId;
			if(isset($_POST['memberId'])){
				$memberId = (int)$_POST['memberId']; 
				
			}
			
			if(in_array($_POST['image_type'], array("battle_hero", "battle_logo", "battle_card", "team_card"))){ // if this is a Battle image
				switch($_POST['image_type']){
					case "battle_hero":  // BATTLE HERO IMAGE
						$battle_id = (int)$_POST['memberId'];
						$imageId = (int)$_POST['image_ids'];
						
						$sql = "UPDATE battles SET battle_hero_image = ? WHERE battle_id = ? AND user_id = ? LIMIT 1";
						$q = $this->db->query($sql, array($imageId, $battle_id, $userId));
						$data['image'] = $this->getImagesByIds(array($imageId));
						$data['success'] = true;	
					break;
					case "team_card":
					$imageId = (int)$_POST['image_ids'];
					$memberId = (int)$_POST['memberId'];
						$sql = "UPDATE battle_teams SET team_card = ? WHERE team_id = ? AND creator_user_id = ? LIMIT 1";
						$q = $this->db->query($sql, array($imageId, $memberId, $userId));
						$data['image'] = $this->getImagesByIds(array($imageId));
						$data['success'] = true;
					break;
					case "battle_logo":
					
					break;
					case "battle_card":
						$battle_id = (int)$_POST['memberId'];
						$imageId = (int)$_POST['image_ids'];
						
						$sql = "UPDATE battles SET battle_image_card = ? WHERE battle_id = ? AND user_id = ? LIMIT 1";
						$q = $this->db->query($sql, array($imageId, $battle_id, $userId));
						$data['image'] = $this->getImagesByIds(array($imageId));
						$data['success'] = true;	
					break;
				}
			} else {
				$data['member_id'] = $memberId;
				
				if($userId == $memberId){
					$imageType = $_POST['image_type'];
					
					if($imageType == 'hero'){ $data['status'] = 'hero';
						$imageId = (int)$_POST['image_ids'];
						$sql = "UPDATE users SET hero_id = ? WHERE user_id = ? LIMIT 1";
						$q = $this->db->query($sql, array($imageId, $memberId));
						$data['image'] = $this->getImagesByIds(array($imageId));
						
						$data['success'] = true;
					} elseif($imageType == 'avatar'){ $data['status'] = 'avatar';
						$imageId = (int)$_POST['image_ids'];
						$sql = "UPDATE users SET avatar_id = ? WHERE user_id = ? LIMIT 1";
						$q = $this->db->query($sql, array((int)$_POST['image_ids'], $memberId));
						//$data['query'] = $this->db->last_query();
						$data['image'] = $this->getImagesByIds(array($imageId));
						$data['success'] = true;	
					}
				}
				
			}
			
			
		
		}
		
		return $data;
	}
	
	
	public function getDictionary(){
		$sql = "SELECT * FROM site_dictionary";
		$q = $this->db->query($sql);
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$dict['id'] = $row['dictionary_id'];
				$dict['english'] = $row['language_english'];
				$dict['french'] = $row['language_french'];
				$dict['spanish'] = $row['language_spanish'];
				$dict['mandarin'] = $row['language_mandarin'];
				
				$dictionary[$row['dictionary_key']] = $dict;	
			}	
			
			return $dictionary;
		}	
	}
	
	public function getLists($lists){ // fetches lists for menus like Countries, states, etc...
		$data = array();
		$lang = "lang_en";
		if(isset($_COOKIE['user_lang']) && $_COOKIE['user_lang'] == 'french'){$lang = "lang_fr";}
		if(isset($_COOKIE['user_lang']) && $_COOKIE['user_lang'] == 'spanish'){$lang = "lang_sp";}
		if(isset($_COOKIE['user_lang']) && $_COOKIE['user_lang'] == 'mandarin'){$lang = "lang_ma";}
		if(count($lists) > 0){
			foreach($lists as $l){
				$sql = "SELECT group_key, {$lang} FROM menu_list WHERE group_name = ? ORDER BY {$lang} ASC";
				$q = $this->db->query($sql, array($l));
				$data['lists'][$l] = array();
				if($q->num_rows() > 0){
					$list = array();
					foreach($q->result_array() as $row){
						$list[$row['group_key']] = $row[$lang];
					}
					
					$data[$l] = $list;
				}
			}	
		}
		
		return $data;	
	}
	
	public function getOptions(){
		$sql = "SELECT * FROM " . DB_PREFIX . "options WHERE autoload = 'yes'";
		$q = $this->db->query($sql);
		$options = array();
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$options[$row['option_name']] = $row['option_value'];
			}
			
		}
		
		return $options;
	}
	
	
	/*---------------------------- UPLOAD ------------------------*/
	
	public function upload(){ //echo 1;
		$data['success'] = false;
		$userId = (int)$this->getUserId();

		if($userId > 0){  //echo 2;
			$uInfo = $this->getUsersInfo(array($userId));
			$userInfo = $uInfo['users'][$userId];
			//echo 3;
		 // verifies that the user has permission to upload images
			$date_path = date("Y/m/d/");
			$ext = UPLOAD_PATH_EXTENSION_LOCAL_SERVER; //echo 4;
			$rel_path = site_url() . "assets/images/users/". $date_path; // creates the relative path.
			
			;
			$path = $_SERVER['DOCUMENT_ROOT'] . "{$ext}/assets/images/users/". $date_path; // creates the absolute path
			
			//echo $path;
			$p = $this->makeFolder($path); // verifies if folder exists and creates it if necessary.
			//echo "Z";
			if(!empty($_FILES)){ 
				$allowed_types = array('.jpeg','.jpg','.png');
				$track_name = $_FILES['Filedata']['name'];
				$file_ext = strtolower($this->get_extension($_FILES['Filedata']['name']));
				$tempFile = $_FILES['Filedata']['tmp_name'];
				
				$targetPath = $path;
				$file_name = $userId . "_" . random_string('alnum', 30) . strtolower($file_ext);
				
				$targetFile =  $targetPath . $file_name;
				
				if(in_array($file_ext, $allowed_types)){
					$move = move_uploaded_file($tempFile,$targetFile); 
					if($move){
						$imageInfo =  $this->registerImage($file_name, $userId, $file_ext, $date_path);
						$data['success'] = true;
						
						$data['image_id'] = $imageInfo['image_id'];
						$data['token'] = $imageInfo['token'];
						$data['source']  = $imageInfo['source'];
						$data['thumb'] = site_url() . "assets/images/users/{$date_path}" . $imageInfo['thumb'];
						$data['square'] = site_url() . "assets/images/users/{$date_path}" . $imageInfo['square'];
					}
					
		}
			}
		}
		
		return $data;
	}
	public function registerImage($file_name, $memberId, $file_ext, $date_path){ // REGISTER IMAGE <----------------------------------- < < < < <
		$allowed_types = array("avatar", "battle_hero", "hero", "battle_logo", "logo","card", "team_card", "battle_card","gallery");
		$userId = $this->User_model->getUserId();
		
		if(isset($_GET['type']) && in_array($_GET['type'], $allowed_types)){
			$type = $_GET['type'];
			
			$token = random_string('alnum', 8);
			$data['token'] = $token;
			
			
			
			$abs_path =  $_SERVER['DOCUMENT_ROOT'] . UPLOAD_PATH_EXTENSION_LOCAL_SERVER . "/assets/images/users/{$date_path}";
			$src = site_url() . "assets/images/users/{$date_path}{$file_name}";
			$path = site_url() . "assets/images/users/{$date_path}";
			
			$thumbs['thumb'] = $this->cropThumbnail(600,375, $src, $file_ext, $abs_path, "thumb_" . $memberId . "_" . random_string('alnum', 20) . ".jpg");
			$thumbs['square'] = $this->cropThumbnail(300,300, $src, $file_ext, $abs_path, "square_" . $memberId . "_" . random_string('alnum', 20) . ".jpg");
			
			$data['source'] = site_url() . "assets/images/users/" . $date_path . "/" . $file_name;
			
			$data['thumb'] = $thumbs['thumb'];
			$data['square'] = $thumbs['square'];
			
			switch($type){
				case "avatar":
					$thumbs['square'] = $this->cropThumbnail(400,400, $src, $file_ext, $abs_path, "hero_" . $memberId . "_" . random_string('alnum', 20) . ".jpg");	
					$thumbs['card'] = $this->cropThumbnail(400,250, $src, $file_ext, $abs_path, "card_" . $memberId . "_" . random_string('alnum', 20) . ".jpg");	
					
					$sql = "INSERT INTO images(user_id, token, image_type, date_path, source_file, thumbs) VALUES(?, ?, ?, ?, ?, ?)";
					$q = $this->db->query($sql, array($memberId, $token, $type, $date_path, $file_name, json_encode($thumbs)));
					
					$imageId = $this->db->insert_id();
					$data['image_id'] = $imageId;
				break;
				
				case "battle_hero":
					$thumbs['hero'] = $this->cropThumbnail(1200,400, $src, $file_ext, $abs_path, "hero_" . $memberId . "_" . random_string('alnum', 20) . ".jpg");	
					$thumbs['mini_hero'] = $this->cropThumbnail(1200,400, $src, $file_ext, $abs_path, "minihero_" . $memberId . "_" . random_string('alnum', 20) . ".jpg");	
					$thumbs['card'] = $this->cropThumbnail(400,250, $src, $file_ext, $abs_path, "card_" . $memberId . "_" . random_string('alnum', 20) . ".jpg");	
					
					$sql = "INSERT INTO images(user_id, token, image_type, date_path, source_file, thumbs) VALUES(?, ?, ?, ?, ?, ?)";
					$q = $this->db->query($sql, array($memberId, $token, $type, $date_path, $file_name, json_encode($thumbs)));
					
					$imageId = $this->db->insert_id();
					$data['image_id'] = $imageId;
					
					
				break;
				
				case "hero":
					$thumbs['hero'] = $this->cropThumbnail(1200,400, $src, $file_ext, $abs_path, "hero_" . $memberId . "_" . random_string('alnum', 20) . ".jpg");	
					$thumbs['mini_hero'] = $this->cropThumbnail(1200,400, $src, $file_ext, $abs_path, "minihero_" . $memberId . "_" . random_string('alnum', 20) . ".jpg");	
					$thumbs['card'] = $this->cropThumbnail(400,250, $src, $file_ext, $abs_path, "card_" . $memberId . "_" . random_string('alnum', 20) . ".jpg");	
					
					$sql = "INSERT INTO images(user_id, token, image_type, date_path, source_file, thumbs) VALUES(?, ?, ?, ?, ?, ?)";
					$q = $this->db->query($sql, array($memberId, $token, $type, $date_path, $file_name, json_encode($thumbs)));
					
					$imageId = $this->db->insert_id();
					$data['image_id'] = $imageId;
				break;
				
				case "battle_logo":
				
				break;
				
				case "gallery":
					$thumbs['square'] = $this->cropThumbnail(400,400, $src, $file_ext, $abs_path, "square_" . $memberId . "_" . random_string('alnum', 20) . ".jpg");	
					$sql = "INSERT INTO images(user_id, token, image_type, date_path, source_file, thumbs) VALUES(?, ?, ?, ?, ?, ?)";
					$q = $this->db->query($sql, array($memberId, $token, $type, $date_path, $file_name, json_encode($thumbs)));
					
					$imageId = $this->db->insert_id();
					
					$data['image_id'] = $imageId;
				break;
				
				case "logo":
				
				break;
				
				case "card":
					$thumbs['big_card'] = $this->cropThumbnail(800,500, $src, $file_ext, $abs_path, "big_card_" . $memberId . "_" . random_string('alnum', 20) . ".jpg");	
					$thumbs['card'] = $this->cropThumbnail(400,250, $src, $file_ext, $abs_path, "card_" . $memberId . "_" . random_string('alnum', 20) . ".jpg");
					
					$imageId = $this->db->insert_id();
					$data['image_id'] = $imageId;
				break;
				
				case "team_card":
					$thumbs['big_card'] = $this->cropThumbnail(800,500, $src, $file_ext, $abs_path, "big_card_" . $memberId . "_" . random_string('alnum', 20) . ".jpg");	
					$thumbs['card'] = $this->cropThumbnail(400,250, $src, $file_ext, $abs_path, "card_" . $memberId . "_" . random_string('alnum', 20) . ".jpg");
					
					$imageId = $this->db->insert_id();
					$data['image_id'] = $imageId;
					
				break;
				
				case "battle_card":
					$sql = "INSERT INTO images(user_id, token, image_type, date_path, source_file, thumbs) VALUES(?, ?, ?, ?, ?, ?)";
					$q = $this->db->query($sql, array($memberId, $token, $type, $date_path, $file_name, json_encode($thumbs)));
					
					$imageId = $this->db->insert_id();
					$data['image_id'] = $imageId;
				break;
				
				case "gallery":
					
					$thumbs['card'] = $this->cropThumbnail(400,250, $src, $file_ext, $abs_path, "hero_" . $memberId . "_" . random_string('alnum', 20) . ".jpg");	
					
					$sql = "INSERT INTO images(user_id, token, image_type, date_path, source_file, thumbs) VALUES(?, ?, ?, ?, ?, ?)";
					$q = $this->db->query($sql, array($memberId, $token, $type, $date_path, $file_name, json_encode($thumbs)));
					
					$imageId = $this->db->insert_id();
					
					$data['image_id'] = $imageId;
					
					$this->addGalleryImage($userId, $imageId);
				break;
			}
			
			
			return $data;
		}
		
		
			
		
	}
	
	public function getUserGallery($userId){
		$gallery = array();
		
		$sql = "SELECT gallery FROM users WHERE user_id = ? LIMIT 1";
		$q = $this->db->query($sql, array($userId));
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$gallery_string = $row['gallery'];
				if(strlen($gallery_string) > 0){$gallery = explode(',', $gallery_string);}
			}
		}	
		
		return $gallery;
	}
	
	/*public function addGalleryImage($userId, $imageId){
		$gallery = $this->getUserGallery($userId);
		
		$gallery = array_merge(array($imageId), $gallery);
		$this->updateUserGalleryOrder($userId, $gallery);
	}*/
	
	public function updateUserGalleryOrder($userId, $gallery){
		$gallery_string = "";
		if(count($gallery) > 0){
			$gallery_string = implode(',', $gallery);	
		}
		$sql = "UPDATE users SET gallery = ? WHERE user_id = ? LIMIT 1";
		$q = $this->db->query($sql, array($gallery_string, $userId));
	}
	
	public function addImageToGallery(){
		$data['success'] = false;
		$userId = $this->getUserId();
		
		if($userId > 0 && isset($_POST['image_ids']) && strlen($_POST['image_ids']) > 0){
			
			$imageIds = explode(",", $_POST['image_ids']);
			
			$imageIds = $this->verifyImageOwnership($userId, $imageIds);
			
			$gallery = $this->getUserGallery($userId);
		
			$gallery = array_merge($imageIds, $gallery);
			$this->updateUserGalleryOrder($userId, $gallery);
			$data['success'] = true;
		}
		
		return $data;
	}
	
	public function verifyImageOwnership($userId, $imagesArray){
		$authorizedArray = array();
		
		$newArray = array();
		
		if(count($imagesArray) > 0){
			foreach($imagesArray as $i){
				if((int)$i > 0){$newArray[] = (int)$i;}
			}
			$imagesString = implode(',', $newArray);
			
			$sql = "SELECT image_id FROM images WHERE image_id IN ({$imagesString}) AND user_id = ?";	
			$q = $this->db->query($sql, array($userId));
			
			$verifiedImages = array();
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					$verifiedImages[] = $row['image_id'];	
				}
				
				foreach($imagesArray as $i){
					if(in_array((int)$i, $verifiedImages)){$authorizedArray[] = (int)$i;}
				}
			}
		}
		
		return $authorizedArray;
	}
	
	public function addUserImage($userId, $imageId){
		$sql = "UPDATE users SET images = CONCAT(?, ',', images) WHERE user_id = ? LIMIT 1";
		$q = $this->db->query($sql, array($imageId, $userId));
	}
	
	public function removeUserImage($userId, $imageId){
		$data['success'] = false;
		
		$userInfo = $this->getUserInfo($userId);
		$images = $userInfo['images'];
		
		if(strlen($images) > 0){
			$imagesArray = explode(',', $images);
			$newArray = array();
			
			foreach($imagesArray as $i){
				if($i != $imageId){
					$newArray[] = $i;	
				}	
				
				$newString = implode(',', $newArray);
				
				$sql = "UPDATE users SET images = ? WHERE user_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($newString, $userId));
				$data['success'] = true;
			}
		}
		
		return $data;	
	}
	
	public function reorderUserGallery(){
		$data['success'] = false;
		$userId = $this->getUserId();
		
		if($userId > 0 && isset($_POST['image_ids'])){
			$image_ids = $_POST['image_ids'];
			$imageArray = array();
			if(strlen($image_ids) > 0){
				$imageArray = explode(',', $image_ids);
				
				$imageArray = $this->verifyImageOwnership($userId, $imageArray);
				$imageString = implode(",", $imageArray);
				$sql = "UPDATE users SET gallery = ? WHERE user_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($imageString, $userId));
				
				$data['success'] = true;
			}	
		}
		
		return $data;	
	}
	
	public function makeFolder($path){
		if(!file_exists($path)){
			$newPath = $path;
			mkdir($newPath, 0777, true);
		}
		return 1;
	}
	
	public function get_extension($filename) {
	   $x = explode('.', $filename);
	   return '.'.end($x);
	}
	
	public function cropThumbnail($nw, $nh, $source, $ext, $path, $outputImage) {
			//echo $path . "<br/>";
			$size = getimagesize($source);
          	$w = $size[0];
          	$h = $size[1];
		  
			$ratio = $w / $h;
		  
			if(strtolower($ext) == '.png'){
				$simg = imagecreatefrompng($source); // loads image into a buffer
			} else { //echo $source;
				$simg = imagecreatefromjpeg($source); // loads image into a buffer
			}
		  
          $dimg = imagecreatetruecolor($nw, $nh);
          $wm = $w/$nw;
          $hm = $h/$nh;
          $h_height = $nh/2;
          $w_height = $nw/2;
		  
		  $new_ratio = $nw / $nh;
		  
		  // this is where it must change.
          if($ratio >= $new_ratio) {
              $adjusted_width = $w / $hm;
              $half_width = $adjusted_width / 2;
              $int_width = $half_width - $w_height;
              imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
          } else {
              $adjusted_height = $h / $wm;
              $half_height = $adjusted_height / 2;
              $int_height = $half_height - $h_height;
              imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
          } 
		$output_image = $outputImage;
		$image_path = $path . $output_image;
		//echo "{$dimg}<br/>Saved image path: " . $image_path . "<br/>OutputImage: {$outputImage}<br/>";
		  imagejpeg($dimg,$image_path,100);//saves the thumbnail
		  $image_path = $path . $output_image;
	  	
		
	  return $output_image;
	}
	
	public function resizeImage($src, $abspath, $width, $height, $newname) {
		$config['image_library'] = 'gd2';
		$config['source_image']	= $src;
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = TRUE;
		$config['width']	= $width;
		$config['height']	= $height;
		$config['new_image'] = $abspath . $newname;
		print_r($config);
		$this->load->library('image_lib', $config); 

		//$this->image_lib->resize();
		if (!$this->image_lib->resize())
        {
            echo $this->image_lib->display_errors(); exit;
        }
		echo $this->image_lib->display_errors();
		return $newname;
	}
	
	public function addReferenceToUserAccount($userId){
		if(isset($_COOKIE['user_ref_id'])){
			$uref = (int)$_COOKIE['user_ref_id'];
			
			$sql = "UPDATE users SET user_reference = ? WHERE user_id = ? AND user_reference = 0 LIMIT 1";	
			$q = $this->db->query($sql, array($uref, $userId, $uref));
			
			
		}
	}
	
	/*----------------------- personal resume page --------------------*/
	
	public function getBusinessTimeline($userId){
		$sql = "SELECT * FROM user_pro_timeline WHERE user_id = ? ORDER BY end_date DESC, start_date DESC";
		$q = $this->db->query($sql, array($userId));
		
		$work = array();
		$education = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$t['id'] = (int)$row['timeline_id'];
				$t['business_id'] = (int)$row['company_id'];
				$t['item_type'] = $row['item_type'];
				$t['business_name'] = $row['company_name'];
				$t['position_name'] = $row['position_name'];
				$t['start_date'] = $row['start_date'];
				$t['start_year'] = date("Y", strtotime($t['start_date']));
				$t['start_month'] = date("m", strtotime($t['start_date']));
				$t['end_date'] = $row['end_date'];
				$t['end_year'] = date("Y", strtotime($t['end_date']));
				$t['end_month'] = date("m", strtotime($t['end_date']));
				$t['description'] = $row['description'];
				$t['still_active'] = $row['still_active'];
				
				if($row['item_type'] == 'work'){
					$work[] = $t;
				} else {
					$education[] = $t;
					
				}
			}
		}
		
		$data['work'] = $work;
		$data['education'] = $education;
		
		return $data;	
	}
	
	
	public function getBusinessTimelineById($userId, $timeline_id){
		$sql = "SELECT * FROM user_pro_timeline WHERE timeline_id = ? AND user_id = ? ORDER BY end_date DESC, start_date DESC";
		$q = $this->db->query($sql, array($timeline_id, $userId));
		
		$work = array();
		$education = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$t['id'] = (int)$row['timeline_id'];
				$t['business_id'] = (int)$row['company_id'];
				$t['business_name'] = $row['company_name'];
				$t['position_name'] = $row['position_name'];
				$t['start_date'] = $row['start_date']; $startdate = explode('-', $t['start_date']);
				$t['start_year'] = date("Y", strtotime($t['start_date']));
				$t['start_month'] = $startdate[1];
				$t['end_date'] = $row['end_date']; $enddate = explode('-', $t['end_date']);
				$t['end_year'] = date("Y", strtotime($t['end_date']));
				$t['end_month'] = $enddate[1];
				$t['description'] = $row['description'];
				$t['still_active'] = $row['still_active'];
				
				return $t;
			}
		}
			
	}
	
	
	public function deleteBusinessTimelineItem(){
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['timeline_id'])){
			$timeline_id = (int)$_POST['timeline_id'];
			
			$sql = "DELETE FROM user_pro_timeline WHERE timeline_id = ? AND user_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($timeline_id, $userId));
			
			$data['success'] = true;	
		}	
		
		return $data;
	}
	
	public function searchTechSkills(){
		$data['success'] = false;
		if(isset($_POST['s'])){
			$searchstring = $_POST['s'];	
			
			$sql = "SELECT * FROM menu_list WHERE group_name = 'technology_skill' AND lang_en LIKE '%" . $this->db->escape_like_str($searchstring). "%' ORDER BY lang_en ASC LIMIT 10";
			$q = $this->db->query($sql);
			
			$items = array();
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					$item['id'] = $row['menu_id'];
					$item['key'] = $row['group_key'];
					$item['name'] = $row['lang_en'];
					
					$items[] = $item;
				}
				$data['success'] = true;
			}
			
		}	
		
		$data['items'] = $items;
		
		return $data;
	}
	
	public function addTechSkills(){
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['tech_name']) && isset($_POST['tech_pos']) && isset($_POST['tech_id']) && isset($_POST['tech_level'])){
			$tech_name = $_POST['tech_name'];
			$tech_pos = (int)$_POST['tech_pos'];
			$tech_id = (int)$_POST['tech_id'];
			$tech_level = (int)$_POST['tech_level'];
			
		
			if($tech_pos > 0){
				$sql = "UPDATE user_tech_skills SET skill_name = ?, skill_id = ?, skill_level = ? WHERE tech_id = ? AND user_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($tech_name, $tech_id, $tech_level, $tech_pos, $userId));
				$data['query'] = $this->db->last_query();
				$data['status'] = 'updated';
				$data['success'] = true;
			} else {
				$sql = "INSERT INTO user_tech_skills(skill_name, skill_id, skill_level, user_id) VALUES(?, ?, ?, ?)";
				$q = $this->db->query($sql, array($tech_name, $tech_id, $tech_level, $userId));	
				$data['status'] = 'inserted';
				$data['tech_pos'] = $this->db->insert_id();
				
				$data['success'] = true;
			}
		
			
				
		}
		
		return $data;	
	}
	
	public function getTechSkills($userId){
		$sql = "SELECT * FROM user_tech_skills WHERE user_id = ? LIMIT 10";
		$q = $this->db->query($sql, array($userId));
		
		$skills = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$skill['id'] = $row['tech_id'];
				$skill['name'] = $row['skill_name'];
				$skill['skill_id'] = $row['skill_id'];
				$skill['skill_level'] = $row['skill_level'];
				
				$skills[] = $skill;
			}
		}
		
		return $skills;
	}
	
	public function deleteTechSkills(){
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['tech_id'])){
			$tech_id = (int)$_POST['tech_id'];
			
			$sql = "DELETE FROM user_tech_skills WHERE tech_id = ? AND user_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($tech_id, $userId));
			
			$data['success'] = true;
		}
		
		return $data;	
	}
	
	public function savePersonalInfo(){
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['title']) && isset($_POST['birthday']) && isset($_POST['nationality']) && isset($_POST['languages'])){
			$first_name = $_POST['first_name'];
			$last_name = $_POST['last_name'];
			$title = $_POST['title'];
			$birthday = $_POST['birthday']; $data['birthday_string'] = date("M j", strtotime($birthday));
			$nationality = $_POST['nationality'];
			$languages = $_POST['languages'];
			
			$sql = "UPDATE users SET first_name = ?, last_name = ?, title = ?, user_nationality = ?, user_languages = ?, birthday = ? WHERE user_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($first_name, $last_name, $title, $nationality, $languages, $birthday, $userId));
			
			$data['success'] = true;
		}
		
		return $data;
	}
	
	
	public function saveContactForm(){
		$data['success'] = false;
		
		$userId = $this->User_model->getUserId();
		$memberId = $userId;
		
		if($userId > 0 && isset($_POST['contact_mobile']) && isset($_POST['contact_work_phone']) && isset($_POST['contact_fax']) && isset($_POST['contact_address']) && isset($_POST['contact_user_skype'])){
			$contact['contact_mobile'] = $_POST['contact_mobile'];
			$contact['contact_work_phone'] = $_POST['contact_work_phone'];
			$contact['contact_user_skype'] = $_POST['contact_user_skype'];
			$contact['contact_fax'] = $_POST['contact_fax'];
			$contact['contact_address'] = $_POST['contact_address'];
			
			$contactstring = serialize($contact);
			
			if(isset($_POST['member_id'])){$memberId = (int)$_POST['member_id'];}
			$users = $this->User_model->getUsersInfo(array($userId));
			$userInfo = $users['users'][$userId];
			
			if(in_array($memberId, $userInfo['business_array']) || $userId == $memberId){
				
				$sql = "UPDATE users SET contact_data = ? WHERE user_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($contactstring, $memberId));
				
				
				$data['success'] = true;
			}
		}
		
		
		return $data;	
	}
	
	public function saveUserLink(){
		$data['success'] = false;
		
		$userId = $this->User_model->getUserId();
		$memberId = $userId;
		
		if($userId > 0 && isset($_POST['link_name']) && isset($_POST['link_type']) && isset($_POST['link_url'])){
			$link_name = $_POST['link_name'];
			$link_type = $_POST['link_type'];
			$link_url = $_POST['link_url'];
			
			$allowed_types = array("facebook","linkedin","instagram","twitter","link");
			
			if(isset($_POST['member_id'])){$memberId = (int)$_POST['member_id'];}
			$users = $this->User_model->getUsersInfo(array($userId));
			$userInfo = $users['users'][$userId];
			
			if(in_array($memberId, $userInfo['business_array']) || $userId == $memberId){
			
				if(in_array($link_type, $allowed_types)){
					$sql = "INSERT INTO user_links(user_id, link_name, link_type, link_url) VALUES(?, ?, ?, ?)";
					$q = $this->db->query($sql, array($memberId, $link_name, $link_type, $link_url));
					
					$data['link_id'] = $this->db->insert_id();
					$data['success'] = true;
					
				}
			}
		
		}
		
		return $data;
	}
	
	public function deleteUserLink(){
		$data['success'] = false;
		
		$userId = $this->User_model->getUserId();
		$memberId = $userId;
		
		if($userId > 0 && isset($_POST['link_id'])){
			
			
			if(isset($_POST['member_id'])){$memberId = (int)$_POST['member_id'];}
			$users = $this->User_model->getUsersInfo(array($userId));
			$userInfo = $users['users'][$userId];
			
			if(in_array($memberId, $userInfo['business_array']) || $userId == $memberId){
			
				$link_id = (int)$_POST['link_id'];
				
				$sql = "DELETE FROM user_links WHERE link_id = ? AND user_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($link_id, $memberId));
				
				$data['success'] = true;
			}
		}
		return $data;
	}
	
	public function getUserLinks($userId){
		if(isset($_GET['member_id'])){$userId = (int)$_GET['member_id'];}
		$sql = "SELECT * FROM user_links WHERE user_id = ? ORDER BY link_id ASC";
		$q = $this->db->query($sql, array($userId));
		
		$links = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$link['id'] = $row['link_id'];
				$link['name'] = $row['link_name'];
				$link['type'] = $row['link_type'];
				$link['url'] = $row['link_url'];
				
				$links[] = $link;
			}
			
			
		}
		
		return $links;
		
	}
	
	public function saveProfileBio(){
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		$memberId = $userId;
		
		if($userId > 0 && isset($_POST['description'])){
			$description = strip_tags($_POST['description'], "<br><p><ul><li><ol><strong><h1><h2><h3><h4><h5>");
			$description = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $description);
			
			if(isset($_POST['member_id'])){$memberId = (int)$_POST['member_id'];}
			$users = $this->User_model->getUsersInfo(array($userId));
			$userInfo = $users['users'][$userId];
			
			if(in_array($memberId, $userInfo['business_array']) || $userId == $memberId){
				$sql = "UPDATE users SET biography = ? WHERE user_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($description, $memberId));
				
				$data['description'] = nl2br($description);
				$data['success'] = true;
			}
			
			
			
		}
		
		return $data;	
	}
	
	
	public function removeProfileSkill(){
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['skill_id'])){
			$users = $this->User_model->getUsersInfo(array($userId));
			$skills_string = $users['users'][$userId]['skills'];
			$skill_id = $_POST['skill_id'];
			$skills = array();
				
			if(strlen($skills_string) > 0){
				$skills = unserialize($skills_string);
				
				$newSkills = array();
				foreach($skills as $key => $value){
					if($key != $skill_id){$newSkills[$key] = $value;}
				}
				
				$data['skills'] = serialize($newSkills);
				
				$sql = "UPDATE users SET skills = ? WHERE user_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($data['skills'], $userId));
			} 
			
			
			
			$data['success'] = true;
		}
		
		return $data;	
	}
	
	public function modifyBusinessTimeline(){
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['timeline_type']) && isset($_POST['timeline_id']) && isset($_POST['business_name']) && isset($_POST['business_id']) && isset($_POST['business_start']) && isset($_POST['business_end']) && isset($_POST['business_description'])){
			$type = $_POST['timeline_type'];
			
			if(in_array($type, array("work", "education"))){
				$timeline_id = (int)$_POST['timeline_id'];
				$business_name = $_POST['business_name'];
				$business_id = $_POST['business_id'];
				$position_name = $_POST['position_name'];
				$business_start = $_POST['business_start'];
				$business_end = $_POST['business_end'];
				$still_active = (int)$_POST['still_active'];
				if($still_active < 0 || $still_active > 1){$still_active = 1;}
				if($still_active > 0){$business_end = "3000-01-01";}
				$business_description = $_POST['business_description'];
				
				if($timeline_id > 0){
					$sql = "UPDATE user_pro_timeline SET item_type = ?, company_name = ?, company_id = ?, start_date = ?, end_date = ?, still_active = ?, description = ?, position_name = ? WHERE timeline_id = ? AND user_id = ? LIMIT 1";
					$q = $this->db->query($sql, array($type, $business_name, $business_id, $business_start, $business_end, $still_active, $business_description, $position_name, $timeline_id, $userId));
					
					$data['success'] = true;
				} else {
					$sql = "INSERT INTO user_pro_timeline(item_type, user_id, company_name, company_id, start_date, end_date, still_active, description, position_name) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";	
					$q = $this->db->query($sql, array($type, $userId, $business_name, $business_id, $business_start, $business_end, $still_active, $business_description, $position_name));
					
					$data['timeline_id'] = $this->db->insert_id();
					
					$data['success'] = true;
				}
				
			}
		}
		
		
		
		return $data;	
	}
	
	public function addUserProfileSkill(){
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['skill_id']) && isset($_POST['skill_value'])){
			$users = $this->User_model->getUsersInfo(array($userId)); // this fetches the member profile info and the skills which are serialized in an array
			$skills_string = $users['users'][$userId]['skills'];
			$skill_id = $_POST['skill_id'];
			$skill_value = (int)$_POST['skill_value'];
			if($skill_value > 10){$skill_value = 10;} 
			if($skill_value < 0){$skill_value = 0;}
			
			$skills = array();
				
			if(strlen($skills_string) > 0){
				$skills = unserialize($skills_string);
			} 
			
			$skills[$skill_id] = $skill_value;
			
			$data['skills'] = serialize($skills);
			$sql = "UPDATE users SET skills = ? WHERE user_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($data['skills'], $userId));
			
			$data['success'] = true;
		}
		
		return $data;	
	}
	
	public function getFollowers($userId){
		$data['users'] = array();
		$data['images'] = array();
		
		$sql = "SELECT from_user_id FROM users_following WHERE target_user_id = ? ORDER BY follow_id DESC";
		$q = $this->db->query($sql, array($userId));
		
		$usersArray = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$usersArray[] = $row['from_user_id'];
			}
			
			$users = $this->getUsersInfo($usersArray);
			
			$data['users'] = $users['users'];
			$data['images'] = $users['images'];
		}
		
		return $data;
	}
	
	public function getFollowing($userId){
		$data['users'] = array();
		$data['images'] = array();
		$data['user_string'] = "";
		$sql = "SELECT target_user_id FROM users_following WHERE from_user_id = ? ORDER BY follow_id DESC";
		$q = $this->db->query($sql, array($userId));
		
		$usersArray = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$usersArray[] = $row['target_user_id'];
			}
			
			$data['user_string'] = implode(',', $usersArray);
			$users = $this->getUsersInfo($usersArray);
			
			$data['users'] = $users['users'];
			$data['images'] = $users['images'];
		}
		
		return $data;
	}
	
	public function startFollowing(){
		$data['success'] = false;
		$userId = $this->getUserId();
		
		if(isset($_POST['member_id'])){
			$memberId = (int)$_POST['member_id'];
			
			if($userId != $memberId){
				$sql = "SELECT follow_id FROM users_following WHERE from_user_id = ? AND target_user_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($userId, $memberId));
				
				if($q->num_rows() < 1){
					$sql = "INSERT INTO users_following(from_user_id, target_user_id) VALUES(?, ?)";
					$q = $this->db->query($sql, array($userId, $memberId));
					
					$data['followers'] = $this->countUsersFollowers($memberId);
					$data['following'] = $this->countUsersFollowing($userId);
					
					$data['success'] = true;
				}	
			}
		}
		
		
		return $data;	
	}
	
	public function stopFollowing(){
		$data['success'] = false;
		$userId = $this->getUserId();
		$data['status'] = 0;
		if(isset($_POST['member_id'])){$data['status'] = 1;
			$memberId = (int)$_POST['member_id'];
			
			if($userId != $memberId){ $data['status'] = 2;
				$sql = "DELETE FROM users_following WHERE from_user_id = ? AND target_user_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($userId, $memberId));
				
				$data['followers'] = $this->countUsersFollowers($memberId);
				$data['following'] = $this->countUsersFollowing($userId);
				$data['status'] = 4;
				$data['success'] = true;
			}
		}
		
		
		return $data;
	}
	
	public function countUsersFollowers($userId, $update = true){
		$sql = "SELECT follow_id FROM users_following WHERE target_user_id = ?";
		$q = $this->db->query($sql, array($userId));
		
		$total = $q->num_rows();
		if($update){
			$sql = "UPDATE users SET followed_by = ? WHERE user_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($total, $userId));
		}
		
		return $total;
	}
	
	public function countUsersFollowing($userId, $update = true){
		$sql = "SELECT follow_id FROM users_following WHERE from_user_id = ?";
		$q = $this->db->query($sql, array($userId));
		
		$total = $q->num_rows();
		if($update){
			$sql = "UPDATE users SET following = ? WHERE user_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($total, $userId));
		}
		
		return $total;
	}
	
	public function is_following($userId, $memberId){
		$sql = "SELECT follow_id FROM users_following WHERE from_user_id = ? AND target_user_id = ? LIMIT 1";
		$q = $this->db->query($sql, array($userId, $memberId));	
		
		$is_following = false;
		if($q->num_rows() > 0){$is_following = true;}
		
		return $is_following;
		
	}
	
	public function saveUserImageTitle(){
		$data['success'] = false;
		$userId = $this->getUserId();
		if($userId > 0 && isset($_POST['image_id']) && isset($_POST['title'])){
			$image_id = (int)$_POST['image_id'];
			$title = $_POST['title'];
			
			$sql = "UPDATE images SET title = ? WHERE image_id = ? AND user_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($title, $image_id, $userId));
			
			$data['success'] = true;	
		}
		
		
		
		return $data;	
	}
	
	public function saveUserImageDescription(){
		$data['success'] = false;
		$userId = $this->getUserId();
		if($userId > 0 && isset($_POST['image_id']) && isset($_POST['description'])){
			$image_id = (int)$_POST['image_id'];
			$description = strip_tags($_POST['description'], "<br><p><b><i><u><a>");
			
			$sql = "UPDATE images SET description = ? WHERE image_id = ? AND user_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($description, $image_id, $userId));
			
			$data['description'] = nl2br($description);
			$data['success'] = true;	
		}
		
		
		
		return $data;	
		
	}
	public function inviteMember(){
		$data['success'] = false;
		$data['errors'] = array();
		$userId = $this->getUserId();
		if($userId > 0 && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email'])){
			$dictionary = $this->User_model->getDictionary();
			$first_name = $_POST['first_name'];
			$last_name = $_POST['last_name'];
			$email = strtolower($_POST['email']);
			$lang = 'english';
			if(isset($_COOKIE['user_lang'])){$lang = $_COOKIE['user_lang'];}
			
			$sql = "SELECT invite_id FROM  user_invite WHERE invite_email = ? LIMIT 1";
			$q = $this->db->query($sql, array($email));
			
			if($q->num_rows() < 1){
				if($this->validateEmail($email)){
					$token = random_string("alnum", 50);	
					
					$sql = "INSERT INTO user_invite(from_user_id, invite_email, first_name, last_name, token, language) VALUES(?, ?, ?, ?, ?, ?)";
					$q = $this->db->query($sql, array($userId, $email, $first_name, $last_name, $token, $lang));
					
					$data['invite_id'] = $this->db->insert_id();
					$data['token'] = $token;
					$data['success'] = true;
				}
			} else {
				$data['errors'][] = $this->functions->_e("someone already sent an invitation to this person", $dictionary);	
			}
		
		}
		
		
		
		return $data;	
	}
	
	public function listInvites($userId){
		$offset = 0;
		$invites = array();
		if(isset($_GET['o'])){$offset = (int)$_GET['o'];}
		
		$sql = "SELECT * FROM user_invite WHERE from_user_id = ? ORDER BY invite_id DESC LIMIT 50 OFFSET ?";	
		$q = $this->db->query($sql, array($userId, $offset));
		$newusers = array();
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$invite['invite_id'] = $row['invite_id'];
				$invite['first_name'] = $row['first_name'];
				$invite['last_name'] = $row['last_name'];
				$invite['name'] = $invite['first_name'] . " " . $invite['last_name'];
				$invite['email'] = $row['invite_email'];
				$invite['token'] = $row['token'];
				$invite['new_user_id'] = $row['new_user_id'];
				$newusers[] = $invite['new_user_id'];
				$invite['language'] = 'english';
				$invite['link'] = site_url() . "user/ic/{$row['invite_id']}/{$invite['token']}/";
				
				$invites[] = $invite;
			}
			
			$newusers = array_unique($newusers);
			if(count($newusers) > 0){
				$data['users'] = $this->User_model->getUsersInfo($newusers);
			}
		}
		
		$data['invites'] = $invites;
		return $data;
	}
	
	public function deleteInvite(){
		$data['success'] = false;
		$userId = $this->getUserId();
		if($userId > 0 && isset($_POST['invite_id'])){
			$invite_id = (int)$_POST['invite_id'];
			
			$sql = "DELETE FROM user_invite WHERE invite_id = ? AND from_user_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($invite_id, $userId));
			
			$data['success'] = true;
		}
		
		
		return $data;
	}
	
	public function checkInvite($inviteId, $token){
		$sql = "SELECT * FROM user_invite WHERE invite_id = ? AND token = ? LIMIT 1";
		$q = $this->db->query($sql, array($inviteId, $token));
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$invite['invite_id'] = $row['invite_id'];
				$invite['from_user_id'] = $row['from_user_id'];
				$users[] = $invite['from_user_id'];
				$invite['first_name'] = $row['first_name'];
				$invite['last_name'] = $row['last_name'];
				$invite['name'] = $invite['first_name'] . " " . $invite['last_name'];
				$invite['email'] = $row['invite_email'];
				$invite['new_user_id'] = $row['new_user_id'];
				$invite['token'] = $row['token'];
				$invite['language'] = $row['language'];
				$allowed_lang = array("english", "french", "spanish", "mandarin");
				if(in_array($row['language'], $allowed_lang)){$invite['language'] = $row['language'];
					$key = 'lang';
					$expire = 604800;
					$cookie = array(
	                   'name'   => $key,
	                   'value'  => $invite['language'],
	                   'expire' => $expire,
	                   'path'   => '/',
	                   'prefix' => 'user_',
						);
						
						set_cookie($cookie);
				}
			}
			
			$data['users'] = $this->getUsersInfo($users);
			$data['invite'] = $invite;
			$data['users'] = $this->getUsersInfo($users);
			
			return $data;
		}
			
	}
	
	public function confirmInvite(){
		$data['success'] = false;
		$errors = array();
		$userId = $this->getUserId(); $data['user_id'] = $userId;
		$dictionary = $this->User_model->getDictionary();
		if($userId < 1 && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['invite_id']) && isset($_POST['invite_token'])){ 
			$first_name = $_POST['first_name'];
			$last_name = $_POST['last_name'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$invite_id = $_POST['invite_id'];
			$token = $_POST['invite_token'];
			
			$errors = array();
			
			$emailValid = $this->validateEmail($email); 
			if(!$emailValid){$errors[] = $this->functions->_e("invalid email", $dictionary);;} else {
				if($this->checkEmailExist($email) > 0){
					$errors[] = $this->functions->_e("email already exists", $dictionary);;	
				}
			} 
			
			if(strlen($password) < 8){$errors[] = $this->functions->_e("password is too short", $dictionary);}
			
			if(count($errors) < 1){
				$sql = "SELECT * FROM user_invite WHERE invite_id = ? AND token = ? LIMIT 1"; // verifies that the invite doe sexists 
				$q = $this->db->query($sql, array($invite_id, $token));
				
				if($q->num_rows() > 0){ 
					foreach($q->result_array() as $row){
						if($row['new_user_id'] < 1){ // verifies if the user has not already been created with this token.
						
						
							
						
						
							$reg = $this->register($first_name, $last_name, $email, $password, $errors, $row['from_user_id']);
							$newUserId = (int)$reg['id'];
							
							$sql = "UPDATE user_invite SET new_user_id = ? WHERE invite_id = ? AND token = ? LIMIT 1";
							$q = $this->db->query($sql, array($newUserId, $invite_id, $token));
							
							$data['success'] = true;
							
							
						}	
					}
				}	
			}
		} 
		$data['errors'] = $errors;
		return $data;	
	}
	
	public function addReferenceToUser($userId, $invited_by){
		$sql = "UPDATE users SET user_reference = ? WHERE user_id = ? LIMIT 1";
		$q = $this->db->query($sql, array($invited_by, $userId));
		
		
	}
	
	/*---------------------------- LIST USERS ---------------------------- */
	
	public function listUsers($offset = 0, $searchstring = ""){
		
		if(isset($_GET['o'])){$offset = (int)$_GET['o'];}
		if(isset($_GET['s'])){$searchstring = $_GET['s'];}
		
		$sql = "SELECT * FROM users ORDER BY user_id DESC LIMIT 50 OFFSET ?";
		$q = $this->db->query($sql, array($offset));	
		
		$images = array();
		$users = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$id = $row['user_id'];
				$user['id'] = $id;
				
				$user['name'] = $row['first_name'] . " " . $row['last_name'];
				$user['first_name'] = $row['first_name']; 
				$user['last_name'] = $row['last_name'];
				$user['email'] = $row['email'];
				$user['email_verified'] = $row['email_verified'];
				$user['title'] = $row['title'];
				$user['birthday'] = $row['birthday'];
				$user['username'] = $row['username'];
				$user['premium_account'] = false;
				$now = time(); // or your date as well
				$your_date = strtotime($row['premium_account']);
				$datediff = $your_date - $now;
				$user['premium_expiration_date'] = $row['premium_account'];
				$user['premium_expires'] =  floor($datediff / (60 * 60 * 24));
				if($user['premium_expires'] > 0){$user['premium_account'] = true;}
				$user['description'] = $row['biography'];
				$user['contact'] = unserialize($row['contact_data']);
				$user['skills'] = $row['skills'];
				$user['technology_skills'] = $row['technology_skills'];
				$user['link'] = site_url() . "member/home/{$user['id']}/" . urlencode($user['name']);
				$user['master_account'] = array();
				$user['following'] = $row['following'];
				$user['followed_by'] = $row['followed_by'];
				$user['reputation'] = $row['reputation'];
				$user['account_type'] = $row['account_type'];
				$user['nationality'] = $row['user_nationality'];
				$user['languages'] = $row['user_languages'];
				$user['creation_time'] = $row['creation_time'];
				if($row['account_type'] != 'member'){ // if not employee
					$user['name'] = $row['business_name']; 
					
					if(strlen($row['master_account']) > 0){
					$user['master_account'] = explode(',', $row['master_account']);}
					$user['link'] = site_url() . "business/home/{$user['id']}/" . urlencode($user['name']);
					$user['no_employees'] = $row['no_employees'];
					$user['business_array'] = array();
					$user['business_ids'] = $row['business_ids']; 
				
				} else {
					$user['business_array'] = array();
					$user['business_ids'] = $row['business_ids']; 
					
					if(strlen($row['business_ids']) > 0){
						$user['business_array'] = explode(',', $user['business_ids']);
					}
					
					
					
				}
				
				if($row['account_type'] == 'business'){
					$user['business_array'] = array();
					$user['business_ids'] = $row['business_ids']; 
					
					if(strlen($row['business_ids']) > 0){
						$user['business_array'] = explode(',', $user['business_ids']);
					}
				}
				
				$address = $row['address'];
				if(strlen($address) > 0){
					$geo = json_decode($address);
					if(isset($geo->country)){$user['country'] = $geo->country;}
					if(isset($geo->state)){$user['state'] = $geo->state;}
					if(isset($geo->city)){$user['city'] = $geo->city;}
					if(isset($geo->zip)){$user['zip'] = $geo->zip;}
					if(isset($geo->phone)){$user['phone'] = $geo->phone;}
					if(isset($geo->fax)){$user['fax'] = $geo->fax;}
					if(isset($geo->web)){$user['web'] = $geo->web;}
					
				} else {
					$user['country'] = "";
					$user['state'] = "";
					$user['city'] = "";
					$user['zip'] = "";
					$user['phone'] = "";
					$user['fax'] = "";
					$user['web'] = "";
				}
				
				$user['lat'] = $row['location_lat'];
				$user['lng'] = $row['location_lng'];
				
				$user['admin_level'] = $row['admin_level'];
				
				
				$user['hero_id'] = (int)$row['hero_id']; if($user['hero_id'] > 0){$images[] = $user['hero_id']; }
				if($row['account_type'] == 'tribe'){
					$user['avatar_id'] = $row['hero_id']; if($user['hero_id'] > 0){$images[] = $user['hero_id']; }
				} else {
					$user['avatar_id'] = $row['avatar_id']; if($user['avatar_id'] > 0){$images[] = $user['avatar_id']; }
				}
				//$user['images'] = $row['images'];
				//$user['image_array'] = array();
				//if(strlen($row['images']) > 0){$user['image_array'] = explode(',', $row['images']); 
				
				//$user['status'] = $row['profile_status'];
				
				$users[$id] = $user;
			}
		}
		
		$data['users'] = $users;
		$data['images'] = array();
		
		if(count($images) > 0){$data['images'] = $this->getImagesByIds($images); }
		
		return $data;
		
	}
	
	public function getUserUpdates(){
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		
		if($userId > 0){
			$sql = "SELECT new_messages, new_notifications FROM users WHERE user_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($userId));
			
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					$data['new_notifications'] = $row['new_notifications'];
					$data['new_messages'] = $row['new_messages'];
				}
				$data['success'] = true;
			}	
		}
		
		
		return $data;
	}
	
	public function addNotification($userId, $targetUserId, $notificationtype, $extra = array()){
		switch($notificationtype){
			case "comment":
			
			$sql = "INSERT INTO user_notifications(from_user_id, user_id, notification_type, notification_data) VALUES(?, ?, ?, ?)";
			$q = $this->db->query($sql, array($userId, $targetUserId, 'comment', serialize($extra)));
			
			$sql = "UPDATE users SET new_notifications = new_notifications + 1 WHERE user_id = ? LIMIT 1"; // increases the notification numbers in the user's row.
			$q = $this->db->query($sql, array($targetUserId));
			break;
			case "following":
			
			break;
			case "battle_leader": /// FOR BATTLE LEADERS, JUDGES, MENTORS, ETC...
			$battleId = $extra['battle_id'];
			$teamId = $extra['team_id'];
			$title = $extra['title'];
			
			$sql = "INSERT INTO user_notifications(from_user_id, user_id, notification_type, notification_data) VALUES(?, ?, ?, ?)";
			$q = $this->db->query($sql, array($userId, $targetUserId, 'battle_leader', serialize($extra)));
			
			$sql = "UPDATE users SET new_notifications = new_notifications + 1 WHERE user_id = ? LIMIT 1"; // increases the notification numbers in the user's row.
			$q = $this->db->query($sql, array($targetUserId));
			
			break;
			
		}
	}
	
	public function getUserNotifications($userId, $offset = 0, $limit = 5){
		
		$sql ="SELECT notification_id FROM user_notifications WHERE user_id = ?";
		$q = $this->db->query($sql, $userId);
		
		$data['no_notifications'] = $q->num_rows();
		
		$sql = "SELECT * FROM user_notifications WHERE user_id = ? ORDER BY notification_id DESC LIMIT ? OFFSET ?";
		$q = $this->db->query($sql, array($userId, $limit, $offset));
		
		$notifications = array();
		$users = array();
		$images = array();
		$battles = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$notif['notification_id'] = $row['notification_id'];
				$notif['from_user_id'] = $row['from_user_id'];
					$users[] = $notif['from_user_id'];
				$notif['type'] = $row['notification_type'];
				$notif['time'] = $this->formatTime($row['notification_time']);
				$notif['extra'] = unserialize($row['notification_data']);
				
				if(isset($notif['extra']['image_id'])){$images[] = $notif['extra']['image_id'];}
				if(isset($notif['extra']['link'])){$notif['link'] = $notif['extra']['link'];}
				
				if($notif['type'] == 'battle_leader' && isset($notif['extra']['battle_id'])){$battles[] = $notif['extra']['battle_id']; }
				
				$notifications[] = $notif;
			}
		}
		
		$data['notifications'] = $notifications;
		if(count($images) > 0){$data['images'] = $this->getImagesByIds($images);}
		if(count($users) > 0){$data['users'] = $this->getUsersInfo($users);}
		if(count($battles) > 0){$data['battles'] = $this->Battle_model->getBattlesByIds(array_unique($battles));  }
		
		$sql = "UPDATE users SET new_notifications = 0 WHERE user_id = ? LIMIT 1"; // resets the number of new notifications to 0 in user's row.
		$q = $this->db->query($sql, array($userId));
		
		return $data;
	}
	
	function formatTime($inTime){ 
		$servertime = LOCAL_TIME;
		$localtime = LOCAL_TIME;
		
		$timediff = strtotime("now") - strtotime($inTime);
		
		
		if(isset($_COOKIE['timezone'])){$localtime = $_COOKIE['timezone'];}
		
		$date = new DateTime($inTime, new DateTimeZone($servertime));
		$date->setTimezone(new DateTimeZone($localtime));
		
		$lang = "";
		if(isset($_COOKIE['user_lang'])){$lang = $_COOKIE['user_lang'];}
		
		if($lang == "french"){
			$mois = array("","Janvier", "Février","Mars","Avril", "Mai","Juin", "Juillet", "Août","Septembre","Octobre","Novembre","Décembre");
			$month = (int)$date->format("n");
			if($timediff < 86400 && $timediff > 0){
				$outtime = $date->format('H') . "h" . $date->format('i');
			} else {
				$outtime = $date->format("j") . " " . $mois[$month]  . $date->format(' Y');
				}
			
			
		} else {
			if($timediff < 86400 && $timediff > 0){$outtime = $date->format('H:i');} else {$outtime = $date->format('M jS Y');}
			
		}
		
		
		
		
		
		return $outtime;
	}
	
	function setbeta($options){
		if(isset($_GET['key']) && $_GET['key'] == $options['beta_key']){
			$expire = 604800;
				
				$cookie = array(
	                   'name'   => 'beta_key',
	                   'value'  => $options['beta_key'],
	                   'expire' => $expire,
	                   'path'   => '/',
	                   'prefix' => '',
						);
						
						set_cookie($cookie);
						
						redirect("/");
		}
	}
	
	public function search(){
		$data['success'] = false;
		if(isset($_POST['s']) && strlen($_POST['s']) > 0){
			$string = $_POST['s'];
			
			$sql = "SELECT * FROM users WHERE CONCAT(first_name, ' ', last_name) LIKE '%" . $this->db->escape_like_str($string). "%' ORDER BY last_name DESC LIMIT 20";
			$q = $this->db->query($sql);
			
			$images = array();
			$users = array();
			
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					$user['name'] = $row['first_name'] . " " . $row['last_name'];
					$user['id'] = $row['user_id'];
					$user['avatar_id'] = $row['avatar_id'];
					if($user['avatar_id'] > 0){$images[] = $user['avatar_id'];}
					$users[] = $user;
				}
				$data['users'] = $users;
				if(count($images) > 0){$data['images'] = $this->getImagesByIds($images);}
			}
			$data['success'] = true;
			
		}
		
		return $data;	
	}
	
	
	
	
}
?>