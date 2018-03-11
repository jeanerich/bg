<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class functions {

    public function followButton($userId, $memberId, $userInfo){
		$className = "follow_user_" . $memberId;
		$unFollow = "";
		$string = "Add Feed";
		if($userId != $memberId){
		if(in_array($memberId, $userInfo['following_profiles'])){$unFollow = " unfollow"; $string = "Unfollow";}
    	echo "<div class='follow_wrapper {$className} {$unFollow}' onclick=\"followMember({$memberId});\"><div class='text'>{$string}</div><div class='symbol'></div></div>";
		}
	}
	
	
	public function register($form_id = "", $form_class = "", $field_class = "", $formfield_class = "", $button_title = "Register"){ 
		$dictionary = $this->User_model->getDictionary();
		?><div class='form' id="<?php echo $form_id;  ?>">
        	<div class='formhead'>
            	<div class='logo'></div>
            </div>
        	<h2><?php echo $this->_e("register", $dictionary); ?></h2>
        	<form id="red_register" class="<?php echo $form_class; ?>">
            	<div class='formfield' class="<?php echo $formfield_class; ?>">
                	<label><?php echo $this->_e("first name", $dictionary); ?></label>
                    <input type="text" id="first_name" name="first_name" class="required <?php echo $field_class; ?>" placeholder="First name" autocomplete="off"  />
                </div>
                <div class='formfield' class="<?php echo $formfield_class; ?>">
                	<label><?php echo $this->_e("last name", $dictionary); ?></label>
                    <input type="text" id="last_name" name="last_name" class="required <?php echo $field_class; ?>" placeholder="Last name"  autocomplete="off" />
                </div>
                <div class='formfield' class="<?php echo $formfield_class; ?>">
                	<label>Email</label>
                    <input type="text" id="email" name="email" class="required email <?php echo $field_class; ?>" placeholder="Email" autocomplete="off"  />
                </div>
                <div class='formfield' class="<?php echo $formfield_class; ?>">
                	<label>Password</label>
                    <input type="password" id="password" name="password" class="required <?php echo $field_class; ?>" placeholder="Password" autocomplete="off"  />
                </div>
                <div class='form_message'></div>
                <div class='formfield'>
                	<button><?php echo $button_title; ?></button>
                </div>
            </form>
        </div>
        <?php	
	}
	
	public function login($form_id = "", $form_class = "", $field_class = "", $formfield_class = "", $button_title = "Login"){ 
		?><div class='form' id="<?php echo $form_id;  ?>">
        	<div class='formhead'>
            	<div class='logo'></div>
            </div>
        	<h2>Login</h2>
        	<form id="red_login" class="<?php echo $form_class; ?>">
            	<div class='formfield' class="<?php echo $formfield_class; ?>">
                	<label>Email</label>
                    <input type="text" id="email" name="email" class="required email <?php echo $field_class; ?>" placeholder="Email" />
                </div>
                <div class='formfield' class="<?php echo $formfield_class; ?>">
                	<label>Password</label>
                    <input type="password" id="password" name="password" class="required <?php echo $field_class; ?>" placeholder="Password" />
                </div>
                <div class='formfield'>
                	<button>Login</button>
                </div>
            </form>
        </div>
        <?php	
	}
	
	public function mediaPlayer($mediaType, $mediaKey, $width = 320, $height = 240, $autoplay = false){
		$output = "";
		$autoplaystring = "";
		switch($mediaType){
			case "youtube": 	
			if($autoplay){$autoplaystring = "?autoplay=1";}
			$output = '<iframe width="' . $width . '" height="' . $height . '" src="http://www.youtube.com/embed/' . $mediaKey . $autoplaystring . '" frameborder="0" allowfullscreen></iframe>';
			break;
			case "vimeo":
			$autoplaystring = "";
			if($autoplay){$autoplaystring = "&autoplay=1";}
			$output = '<iframe src="http://player.vimeo.com/video/' .$mediaKey . '?portrait=0' . $autoplaystring . '" width="' . $width . '" height="' . $height . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
			break;
			case "spotify":
			
			$output = '<iframe src="https://embed.spotify.com/?uri=' . $mediaKey . '" width="' . $width . '" height="' . $height . '" frameborder="0" allowtransparency="true"></iframe>';
			break;
		}
		
		echo $output;
		
		
	}
	
	public function upload_form($upload_form, $upload_target, $type = "", $function_complete = "registerImage", $multiple = false, $button_text = "Select files", $id = 0){
		$upload_tar = false;
		$upload_title = "file_upload"; 
	
	if(isset($upload_form)){$upload_title = $upload_form;}
	if(isset($upload_target)){$upload_tar = $upload_target;}
	

?><div class="upload_form" id="<?php echo $upload_title; ?>"><?php if($upload_tar): ?><div id='<?php echo $upload_title; ?>_target' class='target'></div><?php endif; ?><input class="file_upload" type="file" name="<?php echo $upload_title; ?>" /></div>
<script>
$(document).ready(function(){
	$(function() {
        $('#<?php echo $upload_title; ?> .file_upload').uploadifive({
			<?php $id_string = ""; if($id > 0){$id_string = "&id={$id}";} ?>
            'uploadScript' : '<?php echo site_url(); ?>app/upload/<?php if(isset($type) && strlen($type) > 0){echo "?type={$type}" . $id_string;} ?>'<?php if($upload_target): ?>,
			'queueID'  : '<?php echo $upload_title; ?>_target'<?php endif; ?>,
			"multi" : <?php if($multiple){echo "true";} else {echo "false";} ?>,
			'buttonText' : '<?php echo $button_text; ?>',
			'onUploadComplete' : function(file, data) { console.log(data);
				var upload_data = $.parseJSON(data);
				if(upload_data.success){
					<?php echo $function_complete; ?>(upload_data);
				}
				
            
        }
           
        });
    });

});


</script><?php 
	}
	
	public function truncate($string,$length=100,$append="&hellip;") {
	  $string = trim($string);
	
	  if(strlen($string) > $length) {
		$string = wordwrap($string, $length);
		$string = explode("\n", $string, 2);
		$string = $string[0] . $append;
	  }
	
	  return $string;
	}
	
	public function _e($key, $dictionary){
		$lang = "english";
		$allowed_lang = array("english", "french", "spanish", "mandarin");
		if(isset($_COOKIE['user_lang']) && in_array($lang, $allowed_lang)){$lang = $_COOKIE['user_lang']; }
		
		$string = $key; 
		if(isset($dictionary[$key])){ 
			$temp_string = $dictionary[$key][$lang];	
			if(strlen($temp_string) > 0){$string = $temp_string;} else {$string = $key;}
		}
		
		return $string;
	}
	
	
	public function listBattles($userId, $battles, $when, $dictionary){ 
		?>
        <p><?php echo $this->_e("number of battles", $dictionary); ?>: <?php echo $battles['no_battles']; ?></p>
        <div class='list_items grid clearfix'>
        <?php  
			$battle_images = $battles['images'];
			if($battles['no_battles'] > 0):
				foreach($battles['battles'] as $b): 
				$battlelink = site_url() . "battles/view/{$b['battle_id']}/" . urlencode($b['battle_name']);
		?>
        	<div class='tile'><?php 
				$thumbstring = "";
				if($b['card_image'] > 0){
					$thumbstring = " style='background: url(" . $battle_images[$b['card_image']]['sizes']['thumb'] . ") no-repeat;'"; 	
				}
			?>
            	<a href='<?php echo $battlelink; ?>'><div class='thumb' <?php echo $thumbstring; ?>></div></a>
                <div class='nav'><?php if($b['user_id'] == $userId){echo "<a href='" . site_url() . "battles/create/{$b['battle_id']}' class='button'>" . $this->_e("edit", $dictionary) . "</a>"; } ?></div>
                <div class='text_wrapper'>
                	<h3><?php echo $b['battle_name']; ?></h3>
                    <p class='time'><?php 
						switch($when){
							case "ongoing": 
								$days_vote = ceil((strtotime(date($b['vote_date'])) -  strtotime(date("Y-m-d H:i:s")) ) / 86400); 
								$days_end = ceil((strtotime(date($b['end_date'])) -  strtotime(date("Y-m-d H:i:s")) ) / 86400); 
								if($days_vote > 0){
									echo $this->_e("vote begins in", $dictionary) . " " . $days_vote  . " " . $this->_e("days", $dictionary) . "<br/>(" . date("Y-m-d", strtotime($b['vote_date'])) . ")";	
								} else {
									echo $this->_e("battle ends in", $dictionary) . " " . $days_end . " " . $this->_e("days", $dictionary) . "<br/>(" . date("Y-m-d", strtotime($b['end_date'])) . ")";	
								} 
							break;
							
							case "future":	
								$days_start = ceil((strtotime(date($b['start_date'])) -  strtotime(date("Y-m-d H:i:s")) ) / 86400); 
								echo $this->_e("battle begins in", $dictionary) . " " . $days_start  . " " . $this->_e("days", $dictionary) . "<br/>(" . date("Y-m-d", strtotime($b['start_date'])) . ")";	
							break;
								
							case "past":
							
							break;	
						}
					?></p>
                    <?php  //print_r($b); ?>
                </div>
                <div class='tile_nav'>
                	<a href='<?php echo $battlelink; ?>' class='button darker'><?php echo $this->_e("view", $dictionary); ?></a><a href='<?php echo site_url() . "battles/vote/{$b['battle_id']}/" . urlencode($b['battle_name']); ?>' class='button '><?php echo $this->_e("vote", $dictionary); ?></a>
                </div>
            </div>
		 <?php endforeach; 
		 	else: 
			echo "<div class='line centeralign'>" . $this->_e("no battles listed", $dictionary) . "</div>";
		 ?>
         <?php endif; ?>
        
        </div>
        <?
	}
	
	
}
?>