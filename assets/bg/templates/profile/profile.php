<div id="main_feed" class="center_wrapper  <?php if(isset($_COOKIE['left_panel'])){if($_COOKIE['left_panel'] == 'invisible'){echo " close_left_panel";}} ?>">
	<?php include(DIR_TEMPLATES . "profile/profile-hero-head.php"); ?>

	
     <div id="main_profile_wrapper" class="">
     		<?php include(DIR_TEMPLATES . "profile/profile_menu.php"); ?>
            <?php if($userId > 0 && $userId != $memberId): // do not show if this is a user looking at his/her own profile. ?><div class='top_deck personal_list  paddingbottom40 '>
        	<div class='deck_content clearfix'>
                <div class='item lead lead_message ' >
                    <strong class='social'><a href='#'  onclick="directMessage(<?php echo $memberId; ?>); return false;"><?php echo $this->functions->_e("send message", $dictionary); ?></a></strong>
                </div>
                <div class='item lead lead_subscribe <?php if($is_following){echo "following";} ?>' >
                	<div class='subscribe_icon'></div>
                    <strong class='social '><a href='#'  onclick="startFollowing(<?php echo $memberId; ?>); $('.item.lead.lead_subscribe').addClass('following'); return false;"><?php echo $this->functions->_e("subscribe to feed", $dictionary); ?></a><a href='#'  onclick="stopFollowing(<?php echo $memberId; ?>); $('.item.lead.lead_subscribe').removeClass('following'); return false;"><?php echo $this->functions->_e("unsubscribe to feed", $dictionary); ?></a></strong>
                </div>
            </div>
        </div><?php endif; ?>
     	<?php if(isset($sub_template)){include(DIR_TEMPLATES . $sub_template);} ?>
            
            
            
            
        </div>
        
     </div>
     
</div><?php if($editable): ?>
<script>
$(document).ready(function(){
	checkImages();
});

function checkImages(){
	var hero_image_id = parseFloat($("#profile_head").attr('hero_id'));
	if(hero_image_id < 1){
		//alert('set up your hero image');	
	}
}

</script>
<?php endif; ?>


