<div id="main_hero" style=" <?php if($battle['battle']['hero_image'] > 0){echo "background: url(" . $battle['images'][$battle['battle']['hero_image']]['source'] . ") no-repeat;";} ?>">
	<div class='hero_mask'>
    </div>
    <div class="title_card">
    	<div class="icon softblink"></div>
        <h1><?php echo $battle['battle']['battle_name']; ?></h1><h2><?php if($battle['battle']['battle_type'] == 'team'){ echo $this->functions->_e("team battle", $dictionary); } else {echo $this->functions->_e("single battle", $dictionary);} ?></h2><div class="bar"></div>
    </div>
    <div class='nav'><?php if($editable): ?><a href='<?php echo site_url() . "battles/create/{$battle_id}/"; ?>' class='button'><?php echo $this->functions->_e("edit", $dictionary); ?></a><?php endif; ?></div>
</div>
<?php if(strtotime($battle['battle']['start_date']) > time()){$time_left = strtotime($battle['battle']['start_date']) - time(); $days_left = ceil($time_left / 86400); echo  "<div class='top_ribbon'> " . $this->functions->_e("begins in", $dictionary) . " " . $days_left . " " .  $this->functions->_e("days", $dictionary) . "</div>";} ?> 
                    <?php if(strtotime($battle['battle']['start_date']) < time() && strtotime($battle['battle']['vote_date']) > time()){$time_left = strtotime($battle['battle']['vote_date']) - time(); $days_left = ceil($time_left / 86400); echo  "<div class=' top_ribbon'>Vote begins in {$days_left} days</div>";} ?> <input type="hidden" id="battle_id" value="<?php echo $battle_id; ?>" />
<div class='w1200 paddingtop40 paddingbottom60'>
	<div class='section_head'>
		<?php include(DIR_TEMPLATES . "battles/battle_menu.php");  ?>
        
        
    </div>
    <div id="battle_view" class='line paddingtop30 clearfix'>
    	<?php if(isset($entry['entries']) && count($entry['entries']) > 0): ?>
    	<p><?php echo $this->functions->_e("Work in progress (WIP) below, latest entry at top.", $dictionary); ?></p>
        <?php endif; ?>
    </div>
	
</div>
<?php if(isset($entry['entries']) && count($entry['entries']) > 0): foreach($entry['entries'] as $e):  ?>
<div class='gallery_wrapper clearfix'>
    	<div class='image_wrapper'><?php
			$imgstring = "";
			if($e['media_type'] == 'image'){$imgsrc = $entry['images'][$e['id']]['source']; $imgstring = " style='background: url({$imgsrc}) no-repeat; display: block;'";}
		?>
            <div class="bg_image img_background bg_image bg_image" <?php echo $imgstring; ?>>
            </div>
            <div class="zoom" onclick="fs_modal('battles/previewImage/<?php echo $e['id'] . "/" . $entry['images'][$e['id']]['token']; ?>');"></div>
        
        </div>
        <div class='image_content' id="image_comment_<?php echo $e['id']; ?>">
        	<div class="social_bar clearfix">
            	<a href="#" class="social_unit facebook">
                </a><a href="#" class="social_unit twitter">
                </a>
            </div>
            <div class="scroll_wrapper">
        	<div class="scroll_content">
            	<div class="gallery_image_content">
                	<div class="comments_wrapper">
                    	<div id="message_thread_<?php echo $e['id']; ?>" class="thread_list" image_id="<?php echo $e['id'] ?>" image_token="<?php echo  $entry['images'][$e['id']]['token']; ?>" thread_id="<?php echo $entry['images'][$e['id']]['thread_id']; ?>" thread_token="<?php echo $entry['images'][$e['id']]['thread_token']; ?>">
                        </div>
                    </div>
                </div>
            </div>
       </div>
            <div class="comment_form">
            <textarea id="comment_message_<?php echo $e['id']; ?>" class="comment_message comment_message_<?php echo $e['id']; ?>" placeholder="type a message" image_id="<?php echo $e['id']; ?>" image_token="<?php echo  $entry['images'][$e['id']]['token']; ?>" thread_id="<?php echo $entry['images'][$e['id']]['thread_id']; ?>" thread_token="<?php echo $entry['images'][$e['id']]['thread_token']; ?>"></textarea>
            <a href="#" class="chat_add_image" onclick="return false;"></a>
           </div>
           <div class='toggle_image_comments' onclick="$('.gallery_wrapper .image_content').toggleClass('open');"></div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
