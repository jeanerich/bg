<?php 
class exchange_model extends CI_Model {

    function __construct(){
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
		//$this->load->library('image_lib');
    }
	
	public function postGalleryComment(){
		$data['success'] = false;
		$type = "image";
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['image_id']) && isset($_POST['image_token']) && isset($_POST['thread_id']) && isset($_POST['thread_token']) && isset($_POST['message'])){ 
				$image_id = (int)$_POST['image_id'];
				$image_token = $_POST['image_token'];
				$thread_id = (int)$_POST['thread_id'];
				$thread_token = $_POST['thread_token'];
				$message = strip_tags($_POST['message'], "<br><p><b><i><u><a>");
				$media = array();
				
				$extra['image_id'] = $image_id;
				$extra['image_token'] = $image_token;
				$extra['thread_id'] = $thread_id;
				$extra['thraed_token'] = $thread_token;
				$extra['link'] = site_url() . "gallery/image/{$image_id}/{$image_token}/";
				
				if($thread_id > 0 && strlen($thread_token) > 0){   // If a thread ID and Token are provided, will proceed to proceed to Adding the comment to the thread.
					$data = $this->addCommentToThread($thread_id, $thread_token, $userId, $message, $media, $extra, $type);
					if($data['message_id'] > 0){ 
						$data['message_id'] = $data['message_id'];
						$data['success'] = true;	
					}
				} else {     // otherwise, the thread will be created.
					$sql = "SELECT * FROM images WHERE image_id = ? AND token = ? LIMIT 1"; // verifies that the image ID and token are valid and that a thread hasn't already been created.
					$q = $this->db->query($sql, array($image_id, $image_token));	
					
					if($q->num_rows() > 0){  
						foreach($q->result_array() as $row){
							$thread_creator = $row['user_id'];
							if($row['thread_id'] > 0){ // thread already exists
								$thread_id = $row['thread_id'];
								$thread_token = $row['thread_token'];
								
								$data = $this->addCommentToThread($thread_id, $thread_token, $userId, $message, $media, $extra, $type);
								
								if($data['message_id'] > 0){
									$data['thread_id'] = $thread_id;
									$data['thread_token'] = $thread_token;
									$data['message_id'] = $data['message_id'];
									$data['success'] = true;	
								}
								
							} else { // thread doesn't exists
								$thread_token = random_string('alnum', 50);
								$sql = "INSERT INTO comment_threads(thread_owner, thread_type, ref_id, thread_token) VALUES(?, ?, ?, ?)";
								$q = $this->db->query($sql, array($thread_creator, $type, $image_id, $thread_token));
								
								$thread_id = $this->db->insert_id();
								
								$sql = "UPDATE images SET thread_id = ?, thread_token = ? WHERE image_id = ? LIMIT 1";
								$q = $this->db->query($sql, array($thread_id, $thread_token, $image_id));
								
								$data = $this->addCommentToThread($thread_id, $thread_token, $userId, $message, $media, $extra, $type);
								if($data['message_id'] > 0){
									$data['thread_token'] = $thread_token;
									$data['thread_id'] = $thread_id;
									$data['message_id'] = $data['message_id'];
									$data['success'] = true;	
								}
								
							}
						}
					}
				}
			
			}
		
		return $data;
	}
	
	
	public function addCommentToThread($threadId, $threadToken, $userId, $message, $media, $extra, $type = 'image'){
		$data['message_id'] = 0;
		$sql = "SELECT thread_owner FROM comment_threads WHERE thread_id = ? AND thread_token = ? LIMIT 1"; // verifies that thread ID and Token are valid and matching.
		$q = $this->db->query($sql, array($threadId, $threadToken));
		
		if($q->num_rows() > 0){ 
			foreach($q->result_array() as $row){
				$sql = "INSERT INTO thread_messages(thread_id, from_user, message) VALUES(?, ?, ?)";
				$q = $this->db->query($sql, array($threadId, $userId, $message));
				
				$data['message_id'] = $this->db->insert_id();
				
			}
			
			$this->notifyThreadMembers($userId, $threadId, $extra); 
		}
		
		return $data;
		
	}
	
	
	function notifyThreadMembers($userId, $threadId, $extra){
		$sql = "SELECT thread_owner, thread_type, thread_status, do_not_disturb FROM comment_threads WHERE thread_id = ? LIMIT 1";
		$q = $this->db->query($sql, array($threadId));
		
		
		$users = array(); // this array will store the IDs of users involved in the thread.
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$thread['thread_owner'] = $row['thread_owner']; if($thread['thread_owner'] != $userId){$users[] = $thread['thread_owner'];} //  this will exclude the thread owner if this is the current owner of the thread.
				$thread['thread_type'] = $row['thread_type'];
				$thread['status'] = $row['thread_status'];
				$thread['do_not_disturb'] = $row['do_not_disturb'];
				$donotdisturb = array();
				if(strlen($thread['do_not_disturb']) > 0){$donotdisturb = explode(',', $thread['do_not_disturb']);} // Do not disturbs are members that requested not to receive notifications from this thread anymore.
				
				$sql = "SELECT * FROM thread_messages WHERE thread_id = ?";
				$q = $this->db->query($sql, array($threadId));
				
				if($q->num_rows() > 0){
					foreach($q->result_array() as $row){
						$from_user = $row['from_user'];
						$users[] = $from_user;
					}
				}
				
				$users = array_unique($users);
				
				if(count($users) > 0){
					foreach($users as $u){ 
						$this->User_model->addNotification($userId, $u,'comment', $extra);	
					}	
				}
			}
			
			
			if($thread['status'] == 1){
				
			}
		}
		
		
	}
	
	
	public function getThread($threadId, $threadToken, $offset = 0){
		$userId = $this->User_model->getUserId();
		$messages = array();
		$users = array();
		$sql = "SELECT thread_owner FROM comment_threads WHERE thread_id = ? AND thread_token = ? LIMIT 1";
		$q = $this->db->query($sql, array($threadId, $threadToken));
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$thread_owner = $row['thread_owner'];
				
				$is_owner = false;
				if($thread_owner == $userId){$is_owner = true;}
				
				$sql = "SELECT * FROM thread_messages WHERE thread_id = ? AND post_status = 1 ORDER BY post_time ASC LIMIT 50 OFFSET ?";
				$q = $this->db->query($sql, array($threadId, $offset));
				
				if($q->num_rows() > 0){
					foreach($q->result_array() as $row){
						$message['message_id'] = $row['message_id'];
						$message['user_id'] = $row['from_user'];
					$users[] = $message['user_id'];
						$message['message'] = $row['message'];
						$message['post_time'] = $this->User_model->formatTime($row['post_time']); 
						$message['editable'] = false;
						if($is_owner || $message['user_id'] == $userId){$message['editable'] = true;}
						
						$messages[] = $message;
					}
					
					$users = array_unique($users);
					
					$data['users'] = $this->User_model->getUsersInfo($users);
					
				}
				
				
			}
		}
		$data['messages'] = $messages;
		
		return $data;
	}
	
	public function deleteMessage(){
		$data['success'] = false;
		
		$userId = $this->User_model->getUserId();
		if(isset($_POST['thread_id']) && isset($_POST['message_id'])){
			$thread_id = (int)$_POST['thread_id'];
			$message_id = (int)$_POST['message_id'];	
			
			$sql = "SELECT thread_owner FROM comment_threads WHERE thread_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($thread_id));
			
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					$thread_owner = $row['thread_owner'];
					
					$sql = "SELECT from_user FROM thread_messages WHERE message_id = ? AND thread_id = ? LIMIT 1";
					$q = $this->db->query($sql, array($message_id, $thread_id));
					
					if($q->num_rows() > 0){
						foreach($q->result_array() as $row){
							$from_user = $row['from_user'];
							
							if($from_user == $thread_owner || $from_user == $userId){
								$sql = "DELETE FROM thread_messages WHERE message_id = ? LIMIT 1";
								$q = $this->db->query($sql, array($message_id));
								
								$data['success'] = true;	
							}
						}
					}
				}
			}
					
		}
		
		
		return $data;	
	}
	
	public function sendMessage(){
		
		$data['success'] = false;
		$userId = $this->User_model->getUserId();	
		if($userId > 0 && isset($_POST['new_recipient_ids']) && isset($_POST['new_message_subject']) && isset($_POST['new_message_body'])){
			$recipientids = $_POST['new_recipient_ids'];
			$subject = $_POST['new_message_subject'];
			$message = $_POST['new_message_body'];
			
			$thread_token = random_string('alnum', 20);
			
			$recipients = explode(',', $recipientids);
			
			foreach($recipients as $memberId){
				$message_token = random_string('alnum', 50);
				$sql = "INSERT INTO user_messages(from_user_id, to_user_id, subject, message_body, thread_token, message_token) VALUES(?, ?, ?, ?, ?, ?)";	
				$q = $this->db->query($sql, array($userId, $memberId, $subject, $message, $thread_token, $message_token));
				
				$sql = "UPDATE users SET new_messages = new_messages + 1 WHERE user_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($memberId));
			}
			
			$data['success'] = true;
		}
		
		return $data;
	}
	
	public function getUserMessages($userId, $offset = 0, $limit = 5, $action = "inbox"){
		$data['success'] = false;
		
		$users = array();
		$messages = array();
		
		if($userId > 0){
			
			
			if(isset($_GET['type']) && $_GET['type'] == "sent"){$action = "sent";}
			
			if($action == "inbox"){ // get the Inbox
				$sql = "SELECT message_id FROM user_messages WHERE to_user_id = ? AND visible_to = 1 ";
				$q = $this->db->query($sql, array($userId));
				
				$data['no_messages'] = $q->num_rows();
			
				$sql = "SELECT * FROM user_messages WHERE to_user_id = ? AND visible_to = 1 ORDER BY message_id DESC LIMIT ? OFFSET ? ";
				$q = $this->db->query($sql, array($userId, $limit, $offset));
			} else { // this is the sent box
			$sql = "SELECT message_id FROM user_messages WHERE from_user_id = ? AND visible_from = 1 ";
				$q = $this->db->query($sql, array($userId));
				
				$data['no_messages'] = $q->num_rows();
				
				$sql = "SELECT * FROM user_messages WHERE from_user_id = ? AND visible_from = 1 ORDER BY message_id DESC LIMIT ? OFFSET ?";
				$q = $this->db->query($sql, array($userId, $limit, $offset));
			}
			
			
			
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					$message['message_id'] = $row['message_id'];
					$message['from_user_id'] = $row['from_user_id'];
					$message['to_user_id'] = $row['to_user_id'];
					
					$users[] = $message['from_user_id']; $users[] = $message['to_user_id'];
					
					$message['subject'] = $row['subject'];
					$message['message_body'] = $row['message_body'];
					$message['thread_token'] = $row['thread_token'];
					$message['message_time'] = $this->User_model->formatTime($row['message_time']);
					$message['message_token'] = $row['message_token'];
					
					$messages[] = $message;
				}
			}
			if(count($users) > 0){$data['users'] = $this->User_model->getUsersInfo($users);}
			
			$sql = "UPDATE users SET new_messages = 0 WHERE user_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($userId));
			
			$data['success'] = true;
			
		}
		
		$data['messages'] = $messages;
		
		return $data;	
	}
	
	public function readMessage($userId, $messageId, $messageToken, $action = 'inbox'){ echo $action;
		if($action == 'inbox'){
			$sql = "SELECT * FROM user_messages WHERE message_id = ? AND message_token = ? AND to_user_id = ? LIMIT 1";	
			$q = $this->db->query($sql, array($messageId, $messageToken, $userId));
		} else { // if is sent
			$sql = "SELECT * FROM user_messages WHERE message_id = ? AND message_token = ? AND from_user_id = ? LIMIT 1";	
			$q = $this->db->query($sql, array($messageId, $messageToken, $userId));	
		}
		
		$message = array();
		$users = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$message['message_id'] = $row['message_id'];
				$message['from_user_id'] = $row['from_user_id'];
				$message['to_user_id'] = $row['to_user_id'];
				
				$users[] = $message['from_user_id']; $users[] = $message['to_user_id'];
				
				$message['subject'] = $row['subject'];
				$message['message_body'] = $row['message_body'];
				$message['thread_token'] = $row['thread_token'];
				$message['message_time'] = $this->User_model->formatTime($row['message_time']);
				$message['message_token'] = $row['message_token'];
				
				if(count($users) > 0){$data['users'] = $this->User_model->getUsersInfo($users);}
			}
		}
		
		$data['message'] = $message;
		
		return $data;
	}
	
	
	/*------------------------------ CHAT -------------------------*/
	
	
	public function opendirectmessage(){/* This function will look for a thread ID and Token associated to a thread between two users. */
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
				
				$users = $this->User_model->getUsersInfo(array($memberId));
				$data['member_name'] = $users['users'][$memberId]['name'];
				$data['member_link'] = $users['users'][$memberId]['link'];
				$data['member_avatar_id'] = $users['users'][$memberId]['avatar_id'];
				if($data['member_avatar_id'] > 0){$data['member_avatar'] = $users['images'][$users['users'][$memberId]['avatar_id']]['sizes']['square'];}
				
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
					
					$data['message_id'] = $this->db->insert_id();
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
					
					if(isset($q) && $q->num_rows() > 0){ $data['no_messages'] = $q->num_rows();
						foreach($q->result_array() as $row){ //print_r($row);
							$post['message_id'] = $row['message_id'];
							$post['user_id'] = $row['from_user_id'];
							$post['message'] = $row['message'];
							$post['user_name'] = $row['first_name'] . " " . $row['last_name'];
							$post['time'] = $this->User_model->formatTime($row['post_time']);
							$post['atime'] = $this->User_model->formatTime($row['post_time']);
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
		//$sql = "UPDATE users SET new_messages = new_messages + 1 WHERE user_id = ? LIMIT 1";
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
		$sql = "UPDATE users SET new_messages = '' WHERE user_id = ? LIMIT 1";
		$q = $this->db->query($sql, array($userId));
		/*
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
		
		
	*/}
	
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
	
	function getMessages(){
		
	}

	
}
?>