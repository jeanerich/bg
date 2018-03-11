<?php 
class media_model extends CI_Model {

    function __construct(){
        // Call the Model constructor
        parent::__construct();
		
		$this->load->helper('string');
		$this->load->database();
    }
	
	
	
	public function uploadImage(){
		$userId = (int)$this->User_model->getUserId();

		if($userId > 0){ 
			$userInfo = $this->User_model->getUserInfo($userId);
			
		 // verifies that the user has permission to upload images
			$date_path = date("Y/m/d/");
			$ext = "";
			$rel_path = site_url() . "assets/images/users/". $date_path; // creates the relative path.
			if (strpos($rel_path,'8888') !== false) {
				//$ext = "/cgforward";
			}
			$path = $_SERVER['DOCUMENT_ROOT'] . "{$ext}/assets/images/users/". $date_path; // creates the absolute path
			
			//echo $path;
			$p = $this->makeFolder($path); // verifies if folder exists and creates it if necessary.
			
			if(!empty($_FILES)){ 
				$allowed_types = array('.jpeg','.jpg','.png');
				$track_name = $_FILES['Filedata']['name'];
				$file_ext = strtolower($this->User_model->get_extension($_FILES['Filedata']['name']));
				$tempFile = $_FILES['Filedata']['tmp_name'];
				
				$targetPath = $path;
				$file_name = $userId . "_" . random_string('alnum', 30) . strtolower($file_ext);
				
				$targetFile =  $targetPath . $file_name;
				
				if(in_array($file_ext, $allowed_types)){
					$move = move_uploaded_file($tempFile,$targetFile); 
					if($move){
						$imageId =  $this->registerImage($file_name, $userId, $file_ext, $date_path);
						$return['imageid'] = $imageId;
						
						echo $imageId;
					}
				}
			}
			
		}
	}
	
	
	public function copyImage($userId, $url){ 
		$data['success'] = false;
		if(strlen($url) > 0){
			$allowedTypes = array(".jpg", ".jpeg", ".png");
			$pathInfo = pathinfo($url);
			
			$extension = $pathInfo['extension'];
			$extensionArray = explode("?", $extension);
			$file_ext = "." . strtolower($extensionArray[0]); 
			if(in_array($file_ext, $allowedTypes)){
				$ext = ".jpg";
				if($pathInfo['extension'] == ".png"){$ext = ".png";}
				$newFileName = $userId . "_" . random_string('alnum', 30) . strtolower($ext);
				
				// creates a file directory using today's date
				$date_path = date("Y/m/d/");
				
				$abs_path = $_SERVER['DOCUMENT_ROOT'] . "/assets/images/users/" . $date_path; // creates the absolute path
				$targetFile = $abs_path . $newFileName;
				$p = $this->makeFolder($abs_path);
				
				$move = copy($url,$targetFile); 
				if($move){
					
					$file_name = $userId . "_" . random_string('alnum', 30) . strtolower($file_ext);
					
					$imageId =  $this->registerImage($newFileName, $userId, $file_ext, $date_path);
					//$imageId =  $this->registerImage($newFileName, $userId, $pathInfo['extension'], $date_path, $title);
					$data['imageid'] = $imageId;
					$data['success'] = true;
					
					
				}
			}
		}
		
		return $data;
		
	}
	
	public function registerImage($file_name, $userId, $file_ext, $date_path){
		
		$path = site_url() . "assets/images/users/" . $date_path;
		$ext = "";
		if (strpos($path,'8888') !== false) {
			//$ext = "/cgforward";
		}	
		
		$abs_path = $_SERVER['DOCUMENT_ROOT'] . "/assets/images/users/" . $date_path;
		
		$thumbs['thumb'] = $this->cropThumbnail(1200,300, $path . $file_name, $file_ext, $abs_path, "thumb_" . $userId . "_" . random_string('alnum', 20) . ".jpg");
		$thumbs['square'] = $this->cropThumbnail(400,400, $path . $file_name, $file_ext, $abs_path, "thumb_" . $userId . "_" . random_string('alnum', 20) . ".jpg");
		
		
		$token = random_string('alnum', 8);
		
		$sql = "INSERT INTO images(source_file, user_id, date_path, thumbs, token, status) VALUES(?, ?, ?, ?, ?, 1)";
		$q = $this->db->query($sql, array($file_name, $userId, $date_path, json_encode($thumbs), $token));
		
		$imageId = $this->db->insert_id();
		
		
		return $imageId;
	}
	
	public function copyExternalImage($userId, $imgSource){
		$data['success']  = false;
		
		$date_path = date("Y/m/d/");
		$ext = "";
		$rel_path = site_url() . "assets/images/users/". $date_path; // creates the relative path.
		$ext = $this->extractFileExtension($imgSource);
		$data['file_ext'] = $ext;
		
		return $data;	
	}
	
	public function extractFileExtension($filename){
		$extensions = pathinfo($filename, PATHINFO_EXTENSION);
		$extensions_array = explode("?", $extensions);
		return "." . $extensions_array[0];
			
	}
	
	
	public function addUserImage($imageId, $userId){ // will update the image directory on member profile.
		
		if($userId > 0){
			$sql = "SELECT photo_index FROM users WHERE id = ? LIMIT 1";
			$q = $this->db->query($sql, $userId);
			
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					$photoIndex = $row['photo_index'];
					
					if(empty($photoIndex)){
						$indexString = $imageId;
					}	else {
						$indexArray = explode(",", $photoIndex);
						$indexArray[] = $imageId;
						
						$indexString = implode(",", $indexArray);
					}
					
					$sql = "UPDATE users SET photo_index = ? WHERE id = ? LIMIT 1";
					$q = $this->db->query($sql, array($indexString, $userId));
				}	
			}
		}
	}
	
	public function cropThumbnail($nw, $nh, $source, $ext, $path, $outputImage) {
			
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
		//echo "<br/>Saved image path: " . $image_path;
		  imagejpeg($dimg,$image_path,100);//saves the thumbnail
		  $image_path = $path . $output_image;
	  	
		
	  return $output_image;
	}
	
	public function makeFolder($path){
		if(!file_exists($path)){
			$newPath = $path;
			mkdir($newPath, 0777, true);
		}
		return 1;
	}
	
	public function getUserImage(){ // gets image by id owned by currently logged in user.
		$return['success'] = 0;
		$userId = (int)$this->User_model->getUserId();
		if(isset($_POST['image_id']) && $userId > 0){
			$image_id = (int)$_POST['image_id'];
			
			$return['image_id'] = $image_id;
			
			$sql = "SELECT * FROM photos WHERE id = ? AND user_id = ? AND image_type = 'user' LIMIT 1";
			$q = $this->db->query($sql, array($image_id, $userId));
			
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					$image['id'] = $row['id'];
					$image['src'] = site_url() . "assets/images/users/" . $row['date_path'] . $row['file'];
					$image['thumb'] = site_url() . "assets/images/users/" . $row['date_path'] . $row['thumb'];
					$image['token'] = $row['token'];
					$image['title'] = $row['title'];
					$image['views'] = $row['views'];
					
					$return['image'] = $image;
					
					$return['success'] = 1;
				}	
			}
		}
		
		echo json_encode($return);	
	}
	
	public function getUserPhotoIndex($userId){
		if($userId > 0){
			$sql = "SELECT photo_index FROM users WHERE id = ? LIMIT 1";
			$q = $this->db->query($sql, $userId);
			
			if($q->num_rows > 0){
				foreach($q->result_array() as $row){
					return explode(',', $row['photo_index']);	
				}	
			}	
		}
	}
	
	public function selectMemberAvatar(){
		$return['success'] = 0;
		$userId = (int)$this->User_model->getUserId();
		
		$image_array = $this->getUserPhotoIndex($userId);
		
		if(isset($_POST['image_id']) && $userId > 0){
			$image_id = (int)$_POST['image_id'];
			
			if(in_array($image_id, $image_array)){
				$sql = "UPDATE users SET profile_image = ? WHERE id = ? LIMIT 1";
				$q = $this->db->query($sql, array($image_id, $userId));
				
				$return['success'] = 1;
			}
		}
		
		echo json_encode($return);
	}
	
	public function getUserImages($userId){
		$images = array();
		if($userId > 0){
			$sql = "SELECT photo_index FROM users WHERE id = ? LIMIT 1";
			$q = $this->db->query($sql, $userId);
			
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					$photo_index = $row['photo_index'];
					
					$photo_index_array = explode(',', $row['photo_index']);
					
					$images = $this->getImagesByIds($photo_index_array);
					
				}
			}
			
			
		}
		
		return $images;
	}
	
	public function saveAlbumName($albumname, $albumid, $albumtoken, $userId){
		$data['success'] = false;
		
		$sql = "UPDATE photo_albums SET title = ? WHERE id = ? AND token = ? AND user_id = ? LIMIT 1";
		$q = $this->db->query($sql, array($albumname, $albumid, $albumtoken, $userId));
		$data['query'] = $this->db->last_query();
		$data['success'] = true;
		
		return $data;
	}
	
	public function deleteAlbum($userId, $albumId, $albumToken, $deleteimages){
		$userInfo = $this->User_model->getUserInfo($userId);
		$data['success'] = false;
		
		$data['return_url'] = site_url() . "member/albums/{$userId}/{$userInfo['token']}/"; 
		$sql = "SELECT * FROM photo_albums WHERE id = ? AND token = ? AND user_id = ? LIMIT 1";
		$q = $this->db->query($sql, array($albumId, $albumToken, $userId));
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$index = $row['photo_index'];
				
				$images = array();
				if(strlen($index)){
					$images = explode(',', $index);
				}
				
				$sql = "DELETE FROM photo_albums WHERE id = ? AND token = ? AND user_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($albumId, $albumToken, $userId));
				
				if($deleteimages > 0 && count($images) > 0){
					foreach($images as $i){
						$sql = "UPDATE photos SET status = 0 WHERE id = ? AND user_id = ? LIMIT 1";
						$q = $this->db->query($sql, array($i, $userId));	
					}
				}
				
				$data['success'] = true;
			}
		}
		
		return $data;
	}
	
	
	
	public function getImagesByIds($photo_index_array){ // fetches images from database from id Array.
		$images = array();
		
		if(count($photo_index_array) > 0){
				$photo_index = implode(',', array_filter($photo_index_array));
				$sql = "SELECT photos.id, photos.thumb, photos.file, photos.date_path, photos.title, photos.description, photos.token, photos.comment_id FROM photos WHERE photos.id IN ({$photo_index}) AND photos.image_type = 'user' ORDER BY photos.id DESC";
				$q = $this->db->query($sql);	
				
				if($q->num_rows() > 0){
					foreach($q->result_array() as $image){ 
						$img['id'] = $image['id'];
						$img['thumb'] = site_url() . "assets/images/users/" . $image['date_path'] . $image['thumb'];
						$img['src'] = site_url() . "assets/images/users/" . $image['date_path'] . $image['file'];
						$img['title'] = $image['title'];
						$img['description'] = $image['description'];
						$img['token'] = $image['token'];
						$comment_id = $image['comment_id'];
						
						
						$sql = "SELECT user_comments_index.token, user_comments_index.comment_index, user_comments_index.stars FROM user_comments_index WHERE id = ? LIMIT 1";
						$q = $this->db->query($sql, $comment_id);
						
						if($q->num_rows() > 0){
							foreach($q->result_array() as $comment){
								$img['comment_id'] = $comment_id;
								$img['comment_token'] = $comment['token'];
								$img['no_comments'] = explode(',', $comment['comment_index']);
								$img['stars'] = $comment['stars'];
							}
						}
						
						
						$images[$img['id']] = $img;
						
						
					}
					
					
				}
				
			}
			return $images;
	}
	
	public function getImageByIdsSecure($photo_index_array, $userId){ // fetches images from database from id Array.
		$images = array();
		
		if(count($photo_index_array) > 0){
				$photo_index = implode(',', $photo_index_array);
				$sql = "SELECT * FROM photos WHERE id IN ({$photo_index}) AND user_id = ?  AND image_type = 'user' ORDER BY id DESC";
				$q = $this->db->query($sql, $userId);	
				
				if($q->num_rows() > 0){
					foreach($q->result_array() as $image){
						$img['id'] = $image['id'];
						$img['thumb'] = site_url() . "assets/images/users/" . $image['date_path'] . $image['thumb'];
						$img['src'] = site_url() . "assets/images/users/" . $image['date_path'] . $image['file'];
						$img['title'] = $image['title'];
						$img['description'] = $image['description'];
						$img['token'] = $image['token'];
						
						
						$images[$img['id']] = $img;
						
						
					}
					
					
				}
				
			}
			return $images;
	}
	
	public function setProfileImage($userId, $imageId){
		$return = false;
		$sql = "SELECT * FROM photos WHERE id = ? LIMIT 1";
		$q = $this->db->query($sql, array($imageId));
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				if($row['user_id'] == $userId){
					$sql = "UPDATE users SET profile_image = ? WHERE id = ? LIMIT 1";
					$q = $this->db->query($sql, array($imageId, $userId));	
					$return = true;
				}
			}	
		}
		
		return $return;
	}
	
	public function createUserAlbums(){
		
	}
	
	public function editUserAlbums(){
		
	}
	
	public function getAllUserImages($userId){
		$sql = "SELECT id, title, thumb, date_path, file, token FROM photos WHERE user_id = ? AND status = 1 AND image_type = 'user' ORDER BY id DESC";
		$q = $this->db->query($sql, $userId);
		
		$images = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				
				$id = $row['id'];
				$images[$id] = array();
				$images[$id]['thumb'] = site_url() . "assets/images/users/" . $row['date_path'] . $row['thumb'];
				$images[$id]['src'] = site_url() . "assets/images/users/" . $row['date_path'] . $row['file'];
				$images[$id]['title'] = $row['title'];
				$images[$id]['token'] = $row['token'];
					
			}	
		}
		
		return $images;
	}
	
	public function getUserAlbums($userId){
		$albums = array();
		$imageArray = array();
		$sql = "SELECT * FROM photo_albums WHERE user_id = ?";
		$q = $this->db->query($sql, $userId);
		
		if($q->num_rows() > 0){
			
			foreach($q->result_array() as $row){
				$album['id'] = $row['id'];
				$album['title'] = $row['title'];
				$album['token'] = $row['token'];
				$album['url'] = site_url() . "media/album/{$row['id']}/{$row['token']}/";
				$album['index'] = $row['photo_index'];
				if($row['photo_index'] != NULL){
					$imageArray = array_merge($imageArray, explode(',', $row['photo_index']));	
				}
				
				$albums[] = $album;
			}	
			
		}
		
		$data['images_array'] = $imageArray;
		$data['albums'] = $albums;
		
		return $data;
	}
	
	public function createAlbum($userId, $albumName){
		$return['success'] = 0;
		$token = random_string('alnum', 30);
		$sql = "INSERT INTO photo_albums(user_id, title, token) VALUES(?, ?, ?)";
		$q = $this->db->query($sql, array($userId, $albumName, $token));
		$return['success'] = 1;
		$return['token'] = $token;
		$return['album_id'] = $this->db->insert_id();
		
		return $return;
	}
	
	public function getAlbum($albumId, $albumToken, $userId){
		$return['success'] = false;
		
		$sql = "SELECT user_id, title, photo_index FROM photo_albums WHERE id = ? AND token = ? LIMIT 1";
		$q = $this->db->query($sql, array($albumId, $albumToken));
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$return['member_id'] = (int)$row['user_id'];
				$return['title'] = $row['title'];
				
				
				$images = array();
				
				if(strlen($row['photo_index']) > 0){
					$return['photo_index'] = array();
					if(strlen($row['photo_index']) > 0){$return['photo_index'] = explode(',', $row['photo_index']);}
					$sql = "SELECT * FROM photos WHERE id IN ({$row['photo_index']})";
					$q = $this->db->query($sql);
					
					if($q->num_rows() > 0){
						foreach($q->result_array() as $row){
							$image['title'] = $row['title'];
							$image['token'] = $row['token'];
							$image['thumb'] = site_url() . "assets/images/users/" . $row['date_path'] . $row['thumb'];
							$image['src'] = site_url() . "assets/images/users/" . $row['date_path'] . $row['file'];
							$image['views'] = $row['views'];
							
							$images[$row['id']] = $image;
						}
					}
				}
				
				$return['images'] = $images;
				
				$return['success'] = true;
			}
		}
	return $return;
	
	}
	
	public function getAlbums($userId){
		$sql = "SELECT id, token, title FROM photo_albums WHERE user_id = ?";
		$q = $this->db->query($sql, $userId);
		
		$albums = array();
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$album['id'] = $row['id'];
				$album['token'] = $row['token'];
				$album['title'] = $row['title'];
				
				$albums[] = $album;
			}
		}
		
		return $albums;
	}
	
	public function changeAlbum($userId, $sourceAlbum, $albumId, $albumToken, $imagesIds){
		$data['success'] = false;
		$imageArray = array_filter(explode(',', $imagesIds));
		$imagestring = implode(',', $imageArray);
		
		if(strlen($imagestring) > 0){ 
			$sql = "SELECT id FROM photos WHERE id IN ({$imagestring}) AND user_id = ?";
			$q = $this->db->query($sql, $userId);

			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					$iArray[] = $row['id'];
				}
				
				$newImageString = implode(',', $iArray);
				
				
				$sql = "SELECT photo_index FROM photo_albums WHERE id = ? AND token = ? LIMIT 1";
				$q = $this->db->query($sql, array($albumId, $albumToken));
				
				if($q->num_rows() > 0){
					foreach($q->result_array() as $row){
						$photoIndex = $row['photo_index'];
						
						if(strlen($photoIndex) < 1){
							$newIndex = $newImageString;
						} else {
							$newIndex = $photoIndex . ',' . $newImageString;	
						}
						
						$newIndexArray = array_unique(explode(',', $newIndex));
						$newIndex = implode(',', $newIndexArray);
						
						$sql = "UPDATE photo_albums SET photo_index = ? WHERE id = ? LIMIT 1";
						$q = $this->db->query($sql, array($newIndex, $albumId));
						
						$data['images'] = explode(',', $newIndex);
						$data['success'] = true;
						
						// REMOVE IMAGES FROM SOURCE ALBUM IF ALBUM ISN'T UNSORTED
						if($sourceAlbum > 0){
							$sql = "SELECT photo_index FROM photo_albums WHERE id= ? AND user_id = ? LIMIT 1";
							$q = $this->db->query($sql, array($sourceAlbum, $userId));
							
							if($q->num_rows() > 0){
								foreach($q->result_array() as $row){
									$nIndex = array();
									$photo_index = explode(',',$row['photo_index']);
									foreach($photo_index as $photo){
										if(!in_array($photo, explode(',', $newIndex))){
											$nIndex[] = $photo;
										}
									}
									$photoIndex = "";
									if(count($nIndex) > 0){
										$photoIndex = implode(',', $nIndex);
									}
									
									$sql = "UPDATE photo_albums SET photo_index = ? WHERE id = ? LIMIT 1";
									$q = $this->db->query($sql, array($photoIndex, $sourceAlbum));
									
								}
							}
						}
					}
				}
			}
		
		}
		
		
		
		
		
		return $data;
	}
	
	public function deleteImageFromAlbum($userId, $albumId, $albumToken, $imageIds){
		$data['success'] = false;
		$imageIdsArray = $this->filterNumericArray(explode(',', $imageIds));
		$data['images'] = $imageIdsArray;
		if($albumId > 0){
			
			
			$sql = "SELECT photo_index FROM photo_albums WHERE id = ? AND token = ? AND user_id = ? LIMIT 1";
			$q = $this->db->query($sql, array($albumId, $albumToken, $userId));
			
			
			
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					$index = $row['photo_index'];
					
					if(strlen($index) > 0){
						$indexArray = explode(',', $index);
						
						$newArray = array();
						
						foreach($indexArray as $i){
							if(!in_array($i, $imageIdsArray)){$newArray[] = $i;}
						}
						
						$output = "";
						
						if(count($newArray) > 0){
							$output = implode(',', $newArray);
						}
						
						$sql = "UPDATE photo_albums SET photo_index = ? WHERE id = ? LIMIT 1";
						$q = $this->db->query($sql, array($output, $albumId));
						
						$data['success'] = true;
					}
				}
			}
		}
		
		$imgIds = implode(',', $imageIdsArray);
		$limit = count($imageIdsArray);
		
		$sql = "UPDATE photos SET status = 0 WHERE id IN ({$imgIds}) AND user_id = ? LIMIT ?";
		$q = $this->db->Query($sql, array($userId, $limit));
		
		$data['success'] = true;
		
		return $data;	
	}
	
	public function sortImagesInAlbum($userId, $albumId, $albumToken, $imageIds){
		$data['success'] = false;
		$imageArray = $this->filterNumericArray(explode(',', $imageIds));
		$imageString = implode(',', $imageArray);
		$limit = count($imageArray);
		
		
		
		$string = "";
		
		if(strlen($imageIds) > 0){
			$sql = "SELECT id FROM photos WHERE id IN ({$imageString}) AND user_id = ? LIMIT ?";
			$q = $this->db->query($sql, array($userId, $limit));
			
			if($q->num_rows() >= $limit){
				$sql = "UPDATE photo_albums SET photo_index = ? WHERE id = ? AND token = ? AND user_id = ? LIMIT 1";
				$q = $this->db->query($sql, array($imageString, $albumId, $albumToken, $userId));
				
				$data['success'] = true;
			}
		}
		
		return $data;
	}
	
	public function filterNumericArray($array){
		$newArray = array();
		
		foreach($array as $a){
			$newArray[] = (int)$a;	
		}
		
		return array_filter($newArray);
	}
	
	public function saveImageInfo($userId, $imageId, $token, $title, $description){
		$data['success'] = false;
		
		$sql = "UPDATE photos SET title = ?, description = ? WHERE id = ? AND token = ? AND user_id = ? LIMIT 1";
		$q = $this->db->query($sql, array($title, $description, $imageId, $token, $userId));
		$data['query'] = $this->db->last_query();
		$data['success'] = true;
		
		return $data;
	}
	
	public function getImage($imageId, $imageToken, $userId, $getMessageData = false){ 
		$data['success'] = false;
		
		$sql = "SELECT * FROM photos WHERE id = ? AND token = ? LIMIT 1";
		$q = $this->db->query($sql, array($imageId, $imageToken));
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$image['title'] = $row['title'];
				$image['token'] = $row['token'];
				$image['member_id'] = $row['user_id'];
				$image['description'] = $row['description'];
				$image['thumb'] = site_url() . "assets/images/users/" . $row['date_path'] . $row['thumb'];
				$image['src'] = site_url() . "assets/images/users/" . $row['date_path'] . $row['file'];
				$image['views'] = $row['views'];
				$commentId = $row['comment_id'];
				
				if(!$commentId){
					$threadtoken = random_string('alnum', 30);
					$data['token'] = $threadtoken;
					
					$sql = "INSERT INTO user_comments_index(thread_owner, token) VALUES(?, ?)";
					$q = $this->db->query($sql, array($image['member_id'], $threadtoken));
					
					$commentId = $this->db->insert_id();
					
					
					$sql = "UPDATE photos SET comment_id = ? WHERE id = ? LIMIT 1";
					$q = $this->db->query($sql, array($commentId, (int)$imageId));
				} else {
					$sql = "SELECT token FROM user_comments_index WHERE id = ? LIMIT 1";
					$q = $this->db->query($sql, $commentId);
					
					if($q->num_rows() > 0){
						foreach($q->result_array() as $row){
							$threadtoken = $row['token'];
						}
					}
				}
				
				$image['comment_id'] = $commentId;
				$image['comment_token'] = $threadtoken;
				
				$data['image'] = $image;
				$data['success'] = true;
				
				if($getMessageData){
					$sql = "SELECT * FROM user_comments_index WHERE id = ? AND token = ? LIMIT 1";
					$q = $this->db->query($sql, array($commentId, $threadtoken));
					
					if($q->num_rows() > 0){
						foreach($q->result_array() as $row){
							$data['no_comments'] = count(explode(',', $row['comment_index']));
							$data['no_stars'] = $row['stars'];
						}
					}
				}
			}
		}
		
		return $data;
	}
	
	public function setCompanionImage($userId, $imageId){
		$data['success'] = false;
		$sql = "UPDATE photos SET image_type = 'companion' WHERE id = ? AND user_id = ? LIMIT 1";
		$q = $this->db->query($sql, array($imageId, $userId));
		
		$data['success'] = true;
		return $data;
	}
	
	public function addVideo(){
		$data['success'] = false;
		$userId = (int)$this->User_model->getUserId();
		if($userId > 0 && isset($_POST['video_title']) && isset($_POST['video_type']) && isset($_POST['video_key'])  && isset($_POST['video_image'])){
			$videoTitle = $_POST['video_title'];
			$videoType = $_POST['video_type'];
			$videoKey = $_POST['video_key'];
			$videoImage = $_POST['video_image'];
			
			$token = random_string('alnum', 30);
			
			$sql = "INSERT INTO user_comments_index(thread_owner, token) VALUES(?, ?)";
			$q = $this->db->query($sql, array($userId, $token));
			
			$threadId = $this->db->insert_id();
			
			$data['thread_id'] = $threadId;
			
			$sql = "INSERT INTO user_videos (title, video_type, video_key, image_url, user_id, comment_id) VALUES(?, ?, ?, ?, ?, ?)";
			$q = $this->db->query($sql, array($videoTitle, $videoType, $videoKey, $videoImage, $userId, $threadId));
			
			$videoId = $this->db->insert_id();
			
			$sql = "SELECT video_index FROM users WHERE id = ? LIMIT 1";
			$q = $this->db->query($sql, $userId);
			
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){
					$video_index_string = $row['video_index'];
					$video_index_array = explode(',', $video_index_string);
					
					if(strlen($video_index_string) > 0){
						$video_index_array[] = $videoId;
						$new_video_string = implode(',', $video_index_array);
					} else {
							$new_video_string = $videoId;
					}
					
					$sql = "UPDATE users SET video_index = ? WHERE id = ? LIMIT 1";
					$q = $this->db->query($sql, array($new_video_string, $userId));
				}
			}
			
			$data['success'] = true;
		}
		return $data;	
	}
	
	public function getUserMedia($userId){
		$sql = "SELECT video_index FROM users WHERE id = ? LIMIT 1";
		$q = $this->query($sql, $userId);
		
		
	}
	
	public function getUserVideos($memberId){
		$data = array();
		$data['videos'] = array();
		$sql = "SELECT user_videos.id, user_videos.title, user_videos.video_type, user_videos.video_key, user_videos.image_url, user_videos.views, user_videos.user_like, user_comments_index.comment_index FROM user_videos RIGHT JOIN user_comments_index ON (user_videos.comment_id = user_comments_index.id) WHERE user_id = ? ORDER BY user_videos.id DESC";
		$q = $this->db->query($sql, $memberId);
		
		if($q->num_rows() > 0){
			foreach($q->result_array() as $row){
				$video['id'] = $row['id'];
				$video['title'] = $row['title'];
				$video['type'] = $row['video_type'];
				$video['key'] = $row['video_key'];
				$video['img'] = $row['image_url'];
				$video['no_comments'] = 0;
				$video['no_likes'] = 0;
				if(strlen($row['comment_index']) > 0){$video['no_comments'] = count(explode(',', $row['comment_index']));}
				if(strlen($row['user_like']) > 0){$video['no_likes'] = count(explode(',', $row['user_like']));}
				$video['no_views'] = $row['views'];
				
				
				$videos[] = $video;
				
			}
			
			$data['videos'] = $videos;
		}
		
		
		return $data;
	}
	
	public function getVideoById(){
		$data['success'] = false; 
		$userId = (int)$this->User_model->getUserId(); 
		if(isset($_POST['member_id']) && isset($_POST['video_id'])){ 
			$memberId = (int)$_POST['member_id'];
			$videoId = (int)$_POST['video_id'];
			
			$sql = "SELECT user_videos.id, user_videos.title, user_videos.video_type, user_videos.video_key, user_videos.image_url, user_videos.views, user_videos.user_like, user_comments_index.comment_index, user_comments_index.token, user_videos.comment_id FROM user_videos RIGHT JOIN user_comments_index ON (user_videos.comment_id = user_comments_index.id) WHERE user_videos.id = ? AND user_videos.user_id = ? LIMIT 1";	
			$q = $this->db->query($sql, array($videoId, $memberId));
			
			if($q->num_rows() > 0){
				foreach($q->result_array() as $row){ 
					$video['title'] = $row['title'];
					$video['type'] = $row['video_type'];
					$video['key'] = $row['video_key'];
					$video['img'] = $row['image_url'];
					$video['no_comments'] = 0;
					$video['no_likes'] = 0;
					if(strlen($row['comment_index']) > 0){$video['no_comments'] = count(explode(',', $row['comment_index']));}
					if(strlen($row['user_like']) > 0){$video['no_likes'] = count(explode(',', $row['user_like']));}
					$video['no_views'] = $row['views'];
					
					$data['thread_id'] = $row['comment_id'];
					$data['thread_token'] = $row['token'];
					
					$data['video'] = $video;
					$userInfo = $this->User_model->getUserInfo($memberId); //print_r($userInfo);
					$user['user_id'] = $memberId;
					$user['user_name'] = $userInfo['name'];
					$user['user_title'] = $userInfo['user_title'];
					$user['user_link'] = $userInfo['user_link'];
					$user['user_mentor'] = $userInfo['mentor_status'];
					$user['user_thumb'] = $userInfo['thumb'];
					$data['user'] = $user;
					$data['success'] = true;
				}
			}
			
			return $data;
		}	
	}
		
}
?>