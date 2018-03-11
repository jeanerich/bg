<?php 
class admin_model extends CI_Model {

    function __construct(){
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
		//$this->load->library('image_lib');
    }
	
	public function listtranslation(){
		$offset = 0;
		$string = "";
		
		if($this->uri->segment(3)){$offset = (int)$this->uri->segment(3);}
		if(isset($_GET['t']) && strlen($_GET['t']) > 0){$string = $_GET['t'];}
		
		$data['string'] = $string;
		
		$sstring = "";
		if(strlen($data['string']) > 0){$sstring = " WHERE CONCAT(dictionary_key, language_english, language_french, language_spanish, language_mandarin) LIKE '%" . $this->db->escape_like_str($string). "%' ";}
		
		if(!isset($_COOKIE['no_translations']) && strlen($string) < 1){
		$sql = "SELECT dictionary_id FROM site_dictionary {$sstring}";
		$q = $this->db->query($sql);
		
		$data['no_translations'] = $q->num_rows();
		
		$expire = 604800;
				
		$cookie = array(
			   'name'   => 'no_translations',
			   'value'  => $data['no_translations'],
			   'expire' => $expire,
			   'path'   => '/',
			   'prefix' => '',
				);
				
				set_cookie($cookie);
				
		} else {
			$data['no_translations'] = (int)$_COOKIE['no_translations'];
		}
		
		
		
		$sql = "SELECT * FROM site_dictionary {$sstring} ORDER BY dictionary_id DESC LIMIT 30 OFFSET ?";
		$q = $this->db->query($sql, array($offset));
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$translation['id'] = $row['dictionary_id'];
				$translation['dictionary_key'] = $row['dictionary_key'];
				$translation['language_english'] = $row['language_english'];
				$translation['language_french'] = $row['language_french'];
				$translation['language_spanish'] = $row['language_spanish'];
				$translation['language_mandarin'] = $row['language_mandarin'];
				
				$translations[] = $translation;
			}
			
			$data['translations'] = $translations;
			
			return $data;
		}
		
		// WHERE CONCAT(first_name, ' ', last_name) LIKE '%" . $this->db->escape_like_str($string). "%' 
	}
	
	public function editDictionary(){
		$data['success'] = false;
		
		$userId = $this->User_model->getUserId();
		
		if($userId > 0 && isset($_POST['dictionary_id']) && isset($_POST['key']) && isset($_POST['lang_english']) && isset($_POST['lang_french']) && isset($_POST['lang_spanish']) && isset($_POST['lang_mandarin'])){
			$dictionary_id = (int)$_POST['dictionary_id'];
			$dictionary_key = $_POST['key'];
			$language_english = $_POST['lang_english'];
			$language_french = $_POST['lang_french'];
			$language_spanish = $_POST['lang_spanish'];
			$language_mandarin = $_POST['lang_mandarin'];
			
			if($dictionary_id > 0){ // if this is a modification
				$sql = "UPDATE site_dictionary SET dictionary_key = ?, language_english = ?, language_french = ?, language_spanish = ?,language_mandarin = ? WHERE dictionary_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($dictionary_key, $language_english, $language_french, $language_spanish, $language_mandarin, $dictionary_id));
			} else { // if this is a new inscription
				$sql = "INSERT INTO site_dictionary(dictionary_key, language_english, language_french, language_spanish, language_mandarin) VALUES(?, ?, ?, ?, ?)";
				$q = $this->db->query($sql, array($dictionary_key, $language_english, $language_french, $language_spanish, $language_mandarin));
				
				$data['dictionary_id'] = $this->db->insert_id();
			}
			
			$data['success'] = true;
		}
		
		return $data;	
	}
	
}
?>