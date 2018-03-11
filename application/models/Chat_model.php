<?php 
class chat_model extends CI_Model {

    function __construct(){
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
    }
	
	function index(){
		redirect('/');	
	}
	
	public function getGroupDiscussion($userId){
		$data['success'] = false;
		if(isset($_GET['gid'])){
			$group_id = (int)$_GET['gid'];
			$data['thread'] = array();
			$data['group_id'] = $group_id;
			$sql = "SELECT tribe_membership_id, group_name FROM tribe_membership LEFT JOIN tribe_group ON tribe_membership.group_id = tribe_group.group_id WHERE tribe_membership.group_id = ? AND tribe_membership.member_id = ? LIMIT 1"; // verifies that use has permissions to see this group discussion.
			$q = $this->db->query($sql, array($group_id, $userId));
			
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					$data['group_name'] = $row['group_name'];
				}
			}
		}	
		
		return $data;
	}
	
	public function getTribeGroupDiscussion($userId){
		
	}
	
	public function groupPostMessage(){
		$data['success'] = false;
		$userId = (int)$this->User_model->getUserId();
		$data['userId'] = $userId;
		
		if($userId > 0 && isset($_POST['group_id']) && isset($_POST['message'])){ 
			
			$group_id = (int)$_POST['group_id'];
			$message = strip_tags($_POST['message']);
			$extra = "";
			
			$sql = "SELECT * FROM tribe_membership WHERE group_id = ? AND member_id = ? LIMIT 1"; // CHECK DATE TO SEE IF ACCOUNT HAS EXPIRED
			$q = $this->db->query($sql, array($group_id, $userId));
			
			if($q->num_rows() > 0) {
				foreach($q->result_array() as $row){
					$sql = "INSERT INTO user_group_post(group_id, from_user_id, message) VALUES(?, ?, ?)";
					$q = $this->db->query($sql, array($group_id, $userId, $message));
					$data['message'] = $message;
					$data['success'] = true;	
				}	
			}
			
		}
		
		
		
		return $data;	
	}
	
	public function getGroupMessages(){
		$data['success'] = false;
		$userId = (int)$this->User_model->getUserId();
		$data['userId'] = $userId;
		
		$images = array();
		$img = array();
		$messages = array();
		$data['no_messages'] = 0;
		if($userId > 0 && isset($_POST['group_id']) && isset($_POST['action']) && isset($_POST['message_id'])){ 
			
			$group_id = (int)$_POST['group_id'];
			$action = $_POST['action'];
			$message_id = (int)$_POST['message_id'];
			
			$sql = "SELECT * FROM tribe_membership WHERE group_id = ? AND member_id = ? LIMIT 1"; // CHECK DATE TO SEE IF ACCOUNT HAS EXPIRED
			$q = $this->db->query($sql, array($group_id, $userId));
			
			if($q->num_rows() > 0) {
				foreach($q->result_array() as $row){
					switch($action){
						 case 'init':
							$sql = "SELECT * FROM user_group_post LEFT JOIN users ON user_group_post.from_user_id = users.user_id WHERE user_group_post.group_id = ? ORDER BY post_id DESC LIMIT 50";
							$q = $this->db->query($sql, array($group_id));
							
							
						 break;
						 
						 case 'prev':
						 
						 break;
						 
						 case 'new':
						 	$sql = "SELECT * FROM user_group_post LEFT JOIN users ON user_group_post.from_user_id = users.user_id WHERE user_group_post.post_id > ? AND user_group_post.group_id = ? ORDER BY post_id DESC LIMIT 50";
							$q = $this->db->query($sql, array($message_id, $group_id));
						 break;	
					}
					
					if(isset($q) && $q->num_rows() > 0){ $data['no_messages'] = $q->num_rows();
						foreach($q->result_array() as $row){
							$post['message_id'] = $row['post_id'];
							$post['user_id'] = $row['from_user_id'];
							$post['message'] = $row['message'];
							$post['user_name'] = $row['first_name'] . " " . $row['last_name'];
							$post['time'] = date("m.d.Y", strtotime($row['post_time']));
							$post['atime'] = $row['post_time'] . " UTC";
							$post['avatar_id'] = $row['avatar_id'];
							if($post['avatar_id'] > 0){$img[] = $post['avatar_id'];}
							
							$messages[] = $post;
						}
						
						if(count($img) > 0){$images = $this->User_model->getImagesByIds($img);}	
						$data['success'] = true;
					}
				}	
			}
			
		}
		
		$data['messages'] = $messages;
		$data['images'] = $images;
		return $data;	
	}
	
	public function opendirectmessage(){
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['member_id'])){
			$memberId = (int)$_POST['member_id'];
			if($userId != $memberId){
				$sql = "SELECT direct_user_messaging.thread_id, direct_user_thread.thread_token FROM direct_user_messaging LEFT JOIN direct_user_thread ON direct_user_messaging.thread_id = direct_user_thread.thread_id WHERE from_user_id = ? AND to_user_id = ? LIMIT 1";
				//$sql = "SELECT direct_user_messaging.thread_id";
				$q = $this->db->query($sql, array($userId, $memberId));
				
				if($q->num_rows() > 0){
					foreach($q->result_array() as $row){
						$threadId = $row['thread_id'];	
						$data['thread_token'] = $row['thread_token'];
						$data['success'] = true;
					}
				} else {
					$token =  random_string('alnum', 60);
					$sql = "INSERT INTO direct_user_thread(thread_token) VALUES(?)";
					$q = $this->db->query($sql, array($token));
					$data['thread_token'] = $token;
					$threadId = $this->db->insert_id();
					
					
					
					$sql = "INSERT INTO direct_user_messaging(from_user_id, to_user_id, thread_id) VALUES(?,?,?)";
					$q = $this->db->query($sql, array($userId, $memberId, $threadId));
					
					$sql = "INSERT INTO direct_user_messaging(from_user_id, to_user_id, thread_id) VALUES(?,?,?)";
					$q = $this->db->query($sql, array($memberId, $userId, $threadId));
					
					$data['success'] = true;
				}	
				
				$data['thread_id'] = $threadId;
			}
		}
		
		
		return $data;	
	}
	
	public function getDirectChatThreads(){
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		$data['threads'] = array();
		if($userId > 0){
			
			$threads = array();
			$images = array();
			$img = array();
			
			$sql = "SELECT direct_user_messaging.thread_id, direct_user_messaging.to_user_id, users.first_name, users.last_name, users.avatar_id, direct_user_thread.thread_token FROM direct_user_messaging LEFT JOIN users ON direct_user_messaging.to_user_id = users.user_id LEFT JOIN direct_user_thread ON direct_user_messaging.thread_id = direct_user_thread.thread_id WHERE direct_user_messaging.from_user_id = ? ORDER BY direct_user_messaging.post_time DESC LIMIT 50";
			$q = $this->db->query($sql, $userId);
			
			if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$thread['thread_id'] = $row['thread_id'];
				$thread['token'] = $row['thread_token'];
				$thread['to_user_id'] = $row['to_user_id'];
				$thread['to_user_name'] = $row['first_name'] . " " . $row['last_name'];
				$thread['avatar_id'] = $row['avatar_id'];
				if($thread['avatar_id'] > 0){$img[] = $thread['avatar_id'];}
				
				$threads[] = $thread;
			}
			if(count($img) > 0){$images = $this->User_model->getImagesByIds($img);}	
			$data['threads'] = $threads;
			$data['success'] = true;
			
		}
			
			
		}
		$data['images'] = $images;
		return $data;
	}
	
	public function getDirectThreadByUserId($userId, $memberId){
		$sql = "SELECT direct_user_messaging.thread_id, users.first_name, users.last_name, users.user_id, users.avatar_id FROM direct_user_messaging LEFT JOIN users ON direct_user_messaging.to_user_id = users.user_id WHERE direct_user_messaging.from_user_id = ? AND direct_user_messaging.to_user_id = ? LIMIT 1";
		$q = $this->db->query($sql, array($userId, $memberId));
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$user['name'] = $row['first_name'] . " " . $row['last_name'];
				$user['member_id'] = $memberId;
				$user['link'] = site_url() . "member/home/{$memberId}/" . urlencode($user['name']);
				$user['avatar_id'] = $row['avatar_id'];
				if($user['avatar_id'] > 0){$img = $this->User_model->getImagesByIds(array($row['avatar_id'])); $user['thumb'] = $img[$user['avatar_id']]['sizes']['square'];}
			}
			
			return $user;
		}
	}
	
	public function directPostMessage(){
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['member_id']) && isset($_POST['message'])){
			$memberId = (int)$_POST['member_id'];
			$message = $_POST['message'];
			
			$sql = "SELECT thread_id FROM direct_user_messaging WHERE from_user_id = ? AND to_user_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($userId, $memberId));
			
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					$threadId = $row['thread_id'];
					
					$sql = "INSERT INTO direct_messaging_post(thread_id, from_user_id, message) VALUES(?, ?, ?)";
					$q = $this->db->query($sql, array($threadId, $userId, $message));
					
					$data['post_id'] = $this->db->insert_id();
					$this->addNewMessagePost($userId, $memberId);
					$data['success'] = true;
				}
			}
		}
		
		
		return $data;
	}
	
	
	public function getDirectMessages(){
		$data['success'] = false;
		
		$images = array();
		$img = array();
		$messages = array();
		
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['member_id']) && isset($_POST['action']) && isset($_POST['message_id'])){ // verifies that all data has been properly passed from Javascript
			$memberId = (int)$_POST['member_id'];
			$message_id = (int)$_POST['message_id'];
			$action = $_POST['action'];
			
			$this->removeNewMessagePost($userId, $memberId);
			
			$sql = "SELECT thread_id FROM direct_user_messaging WHERE from_user_id = ? AND to_user_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($userId, $memberId));
			
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					$threadId = $row['thread_id'];
					
					switch($action){
						 case 'init':
							$sql = "SELECT direct_messaging_post.message_id, direct_messaging_post.from_user_id, direct_messaging_post.message, direct_messaging_post.post_time, users.first_name, users.last_name, users.avatar_id FROM direct_messaging_post LEFT JOIN users ON direct_messaging_post.from_user_id = users.user_id WHERE direct_messaging_post.thread_id = ? ORDER BY message_id DESC LIMIT 50";
							$q = $this->db->query($sql, array($threadId));
							$data['query'] = $this->db->last_query();
							$sql = "UPDATE direct_user_messaging SET is_notified = 0 WHERE from_user_id = ? AND to_user_id = ?";
							$r = $this->db->query($sql, array($memberId, $userId));
							
							
						 break;
						 
						 case 'prev':
						 
						 break;
						 
						 case 'new':
							$sql = "SELECT direct_messaging_post.message_id, direct_messaging_post.from_user_id, direct_messaging_post.message, direct_messaging_post.post_time, users.first_name, users.last_name, users.avatar_id FROM direct_messaging_post LEFT JOIN users ON direct_messaging_post.from_user_id = users.user_id WHERE direct_messaging_post.thread_id = ? AND direct_messaging_post.message_id > ? ORDER BY message_id DESC LIMIT 50";
							$q = $this->db->query($sql, array($threadId, $message_id));
						 break;	
					}
					$data['query'] = $this->db->last_query();
					if(isset($q) && $q->num_rows() > 0){ $data['no_messages'] = $q->num_rows();
						foreach($q->result_array() as $row){ //print_r($row);
							$post['message_id'] = $row['message_id'];
							$post['user_id'] = $row['from_user_id'];
							$post['message'] = $row['message'];
							$post['user_name'] = $row['first_name'] . " " . $row['last_name'];
							$post['time'] = date("m.d.Y", strtotime($row['post_time']));
							$post['atime'] = $row['post_time'] . " UTC";
							$post['avatar_id'] = $row['avatar_id'];
							if($post['avatar_id'] > 0){$img[] = $post['avatar_id'];}
							
							$messages[] = $post;
						}
						
						if(count($img) > 0){$images = $this->User_model->getImagesByIds($img);}	
						$data['success'] = true;
					}
					$data['images'] = $images;
					$data['messages'] = $messages;
					$data['success'] = true;
				}
			}
			
			
			
		}
		
		
		return $data;
	}
	
	public function updateLastViewDirectChatThread(){
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		
		$cur_time = date("Y-m-d h:i:s");
		
		if($userId > 0 && isset($_POST['member_ids']) && strlen($_POST['member_ids']) > 0){
			$memberIds = explode(',', $_POST['member_ids']);
			
			foreach($memberIds as $id){
				$sql = "UPDATE direct_user_messaging SET last_viewed = ? WHERE from_user_id = ? AND to_user_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($cur_time, $id, $userId));
				
				
			}
			$data['success'] = true;
		}
		return $data;
	}
	
	public function addNewMessagePost($userId, $memberId){
		$data['success'] = false;
		
		$sql = "SELECT new_messages FROM users WHERE user_id = ? LIMIT 1";
		$q = $this->db->query($sql, array($memberId));
		
		
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$newMessagesArray = array();
				
				$new_messages_string = $row['new_messages'];
				if(strlen($new_messages_string) > 0){$newMessagesArray = explode(',', $new_messages_string); }
				
				$newMessagesArray[] = $userId;
				
				$newMessagesArray = array_unique($newMessagesArray);
				
				$new_message_string = implode(',', $newMessagesArray);
				
				$sql = "UPDATE users SET new_messages = ? WHERE user_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($new_message_string, $memberId));
				
				$posttime = date('Y-m-d G:i:s', time());
				
				$sql = "UPDATE direct_user_messaging SET post_time = ?, is_notified = 1 WHERE from_user_id = ? AND to_user_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($posttime, $userId, $memberId));
				
				
				
			}
		}
		
		return $data;
	}

	public function checkNotifications(){
		$data['success'] = false;
		$userId = $this->User_model->getUserId();
		
		if($userId > 0){
			$sql = "SELECT new_notifications, new_messages FROM users WHERE user_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($userId));
			
			$data['new_notifications'] = array();
			$data['new_messages'] = array();
			
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					if(strlen($row['new_notifications']) > 0){$data['new_notifications'] = explode(',', $row['new_notifications']);}
					if(strlen($row['new_messages']) > 0){$data['new_messages'] = explode(',', $row['new_messages']);}
				}
			}
			
			$data['success'] = true;
		}
		
		return $data;
	}
	
	public function removeNewMessagePost($userId, $memberId){
		$sql = "SELECT new_messages FROM users WHERE user_id = ? LIMIT 1";
		$q = $this->db->query($sql, array($userId));
		
		
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$newMessagesArray = array();
				
				$new_messages_string = $row['new_messages'];
				if(strlen($new_messages_string) > 0){$newMessagesArray = explode(',', $new_messages_string); }
				
				if(in_array($memberId, $newMessagesArray)){
					$na = array();
					
					foreach($newMessagesArray as $n){
						if($n != $memberId){
							$na[] = $n;
						}
					}
					
					$newMessagesArray = $na;
				}
				
				$message_string = "";
				if(count($newMessagesArray) > 0){
					$message_string = implode(',', $newMessagesArray);
				}
				
				$sql = "UPDATE users SET new_messages = ? WHERE user_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($message_string, $userId));
			}
		}
		
		
	}
	
	public function viewNewUserMessages(){
		$data['success'] = false;
		
		$userId = $this->User_model->getUserId();
		
		if($userId > 0){
			$sql = "SELECT new_messages FROM users WHERE user_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($userId));
			
			$messages = array();
			
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					if(strlen($row['new_messages']) > 0){
						$m_array = explode(',', $row['new_messages']);
						
						$users = $this->User_model->getUsersInfo($m_array);
						
						foreach($m_array as $m){
							$message['name'] = $users['users'][$m]['name'];	
							$message['avatar_id'] = $users['users'][$m]['avatar_id'];	
							if($message['avatar_id'] > 0){$message['thumb'] = $users['images'][$users['users'][$m]['avatar_id']]['sizes']['square'];	}
							
							$messages[$m] = $message;
						}
						
						
					}
				}
			}	
			$data['messages'] = $messages;
			$data['success'] = true;	
		}
		
		
		return $data;	
	}
}
?>