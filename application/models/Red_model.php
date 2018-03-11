<?php 
class red_model extends CI_Model {

    function __construct(){
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
		//$this->load->library('image_lib');
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
	
	public function login($email, $password){
		$data['success'] = false;
		$sql = "SELECT user_id, hash_password, salt FROM " . DB_PREFIX . "users WHERE email = ? LIMIT 1";
		$q = $this->db->query($sql, array($email));
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$salt = $row['salt'];
				
				$hash_password = $row['hash_password'];
				
				
				$saltedSubmittedPassword = md5($salt . $password);
				if($hash_password == $saltedSubmittedPassword){
					$this->createSession($row['user_id']);
					$data['success'] = true;
				}
			}	
		}
		
		return $data;
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
	
	
	
	public function register($firstname, $lastname, $email, $password){ 
		$data['success'] = false;
		$data['reg_type'] = "registration";
		$errors = array();
		
		if(strlen($firstname) < 1){$errors[] = "First name is a required field.";}
		if(strlen($lastname) < 1){$errors[] = "Last name is a required field.";}
		
		if($this->emailExists($email)){ // verifies if email is already taken.
			$errors[] = "Email already exists.";
		}
		
		if(strlen($email) > 0){
			if(!$this->validateEmail($email)){
				$errors[] = "Email is invalid.";	
			}
		} else {
			$errors[] = "Email is a required field.";	
		}
		
		if(strlen($password) < 8){$errors[] = "Password must have a tleast 8 characters.";}
		
		if(count($errors) < 1){
			$salt = random_string('alnum', 16);
			$saltedPassword = md5($salt . $password);
			$sql = "INSERT INTO " . DB_PREFIX . "users(email, first_name, last_name, hash_password, salt) VALUES(?, ?, ?, ?, ?)";
			$q = $this->db->query($sql, array($email, $firstname, $lastname, $saltedPassword, $salt));
			
			$userId = (int)$this->db->insert_id();
			
			$this->createSession($userId);
			
			$data['user_id'] = $userId;
			$data['success'] = true;
		
		}																																																																																		
		$data['errors'] = $errors;
		return $data;
	}
	
}
?>