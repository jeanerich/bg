<div id="main_feed" class="center_wrapper  <?php if(isset($_COOKIE['left_panel'])){if($_COOKIE['left_panel'] == 'invisible'){echo " close_left_panel";}} ?>">
	
	<div id='profile_head'><div class='background'<?php if($memberInfo['hero_id'] > 0): ?> style="background: url(<?php 
	echo $images[$memberInfo['hero_id']]['sizes']['thumb']; ?>) no-repeat;"<?php endif; ?>></div><?php 
	if($editable): ?><div class='nav'><a class='button' href='#' onclick="fs_modal('user/add_hero_image/<?php echo $memberId; ?>'); return false;"><?php echo $this->functions->_e("modify hero image", $dictionary); ?></a></div><?php endif;  if($profile_type != 'tribe' ) :?><div id='avatar_image' class='avatar_image <?php echo $profile_type; ?>' <?php if($memberInfo['avatar_id'] > 0): ?> avatar_id="<?php echo $memberInfo['avatar_id']; ?>" style="background: url(<?php 
	echo $images[$memberInfo['avatar_id']]['sizes']['square']; ?>) no-repeat; "<?php endif; ?>><?php if($editable): ?><div class='nav'><a class='button' href='#' onclick="fs_modal('user/modify_profile_image/<?php echo $memberId; ?>'); return false;"><?php echo $this->functions->_e("modify", $dictionary); ?> <?php if($profile_type == 'business'){echo "Logo";} else {echo "Avatar";} ?></a></div><?php endif; ?></div><?php endif; ?></div>
	<div id="profile_wrapper">
    	
        <div class='profile_head<?php if($profile_type == 'tribe'){echo " tribe";} ?>'>
        	<h1><?php echo $memberInfo['name']; ?></h1>
            <div class='line clearfix profile_sub_head'>
            	<div class='size1of2 unit'>
                	<p class='nopadding location'><?php  if(strlen($memberInfo['city']) > 0){echo $memberInfo['city']; } else {echo $this->functions->_e("not yet defined", $dictionary);}  ?></p>
                </div>
            	<div class='unit size1of2'>
                	<p class='nopadding industries'><?php if(strlen($memberInfo['title']) > 0){echo $memberInfo['title'];} else {echo $this->functions->_e("not yet defined", $dictionary);} ?></p>
                </div>
            </div>
            <?php if($editable): ?><div class='profile_console'>
            	<a href="<?php echo site_url() . $profile_type . "/edit/{$memberId}/" . urlencode($memberInfo['name']); ?>" class='button'><?php echo $this->functions->_e("edit profile", $dictionary); ?></a>
            </div><?php endif; ?>
            
        </div>
        
       <?php include(DIR_TEMPLATES . "profile/profile_menu.php"); ?>
        <?php if($userId > 0 && $userId != $memberId): ?><div class='profile_actions'>
            	<a href='#' class='roundlink <?php if($is_following){echo "inactive"; } ?>' onclick="startFollowing(<?php echo $memberId; ?>); no-repeat;"><div class='roundbutton follow'></div><?php echo $this->functions->_e("subscribe", $dictionary); ?></a>
                <a href='#' class='roundlink <?php if(!$is_following){echo "inactive"; } ?>' onclick="stopFollowing(<?php echo $memberId; ?>); no-repeat;"><div class='roundbutton unfollow'></div><?php echo $this->functions->_e("unsubscribe", $dictionary); ?></a>
                <?php if($profile_type == 'member'): ?><a href='#' class='roundlink' ><div class='roundbutton message' onclick="directMessage(<?php echo $memberId; ?>); return false;"></div>Message</a><?php endif; ?>
        </div><?php endif; ?>
        
        <div class='deck'>
        	<?php if($profile_type == 'member'): ?>
				<?php if(isset($sub_template)): ?>
                <?php include(DIR_TEMPLATES . $sub_template); ?>
                <?php else: ?>
                <div class='profile_deck profile_stats '>
        	<div class='line padding20 clearfix'>
            	<div class='vline'></div>
                <div class='vline'></div>
            	<div class='unit size1of3'>
                	<h3 class="user_followers"><?php echo $memberInfo['followed_by']; ?></h3> <span>followers</span>
                </div>
                <div class='unit size1of3'>
                	<h3 class="users_following"><?php echo $memberInfo['following']; ?></h3> <span>Following</span>
                </div>
                <div class='unit size1of3'>
                	<h3 class="users_following"><?php echo $memberInfo['reputation']; ?></h3> <span>Reputation</span>
                </div>
            </div>
        </div>
                <div class='deck_head'>
                    <h2><?php echo $this->functions->_e("business", $dictionary); ?></h2>
                </div>
                <div class='grid clearfix'>
                <?php foreach($member_businesses as $b): ?>
                    <div class='tile unit'>
                        <a href='<?php echo $b['link']; ?>'><div class='thumb'<?php if($b['avatar_id'] > 0){echo " style='background: url(" . $b['avatar'] . ") no-repeat;'";} ?>></div></a>
                        <div class='text'>
                            <p class='title'><a href='<?php echo $b['link']; ?>'><?php echo $b['name']; ?></a></p>
                            <p class='staff'>0 employees</p>
                            <a href='<?php echo $b['link']; ?>' class='info'>i</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
                <?php endif; ?>
            <?php else: ?>
				<?php if(isset($sub_template)): ?>
                    <?php include(DIR_TEMPLATES . $sub_template); ?>
                    <?php else: ?>
                <div class='deck_head'>
                    <h2>Employees</h2>
                </div>
                <div class='grid clearfix'><?php $employees = $employees_data['users']; $employee_images = $employees_data['images']; // print_r($employee_images); ?>
                <?php foreach($employees as $e): ?>
                    <div class='tile unit'>
                        <a href='<?php echo $e['link']; ?>'><div class='thumb' <?php if($e['avatar_id'] > 0){echo " style='background: url(" . $employee_images[$e['avatar_id']]['sizes']['square'] . ") no-repeat;'";} ?>></div></a>
                        <div class='text'>
                            <p class='title'><a href='<?php echo $e['link']; ?>'><?php echo $e['name']; ?></a></p>
                            <p class='staff'>Title & position</p>
                            <a href='<?php echo $e['link']; ?>' class='info'>i</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
                <?php endif; ?>
			<?php endif; ?>
        </div>
    </div>
    
    <?php //include(DIR_TEMPLATES . "common/left-panel.php"); ?>
     <?php // include(DIR_TEMPLATES . "common/right_panel.php"); ?>
</div>
