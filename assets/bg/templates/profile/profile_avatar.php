<div id='avatar_image' class='avatar_image <?php echo $profile_type; ?>' <?php if($memberInfo['avatar_id'] > 0): ?> avatar_id="<?php echo $memberInfo['avatar_id']; ?>" style="background: url(<?php 
	echo $images[$memberInfo['avatar_id']]['sizes']['square']; ?>) no-repeat; "<?php endif; ?>></div><?php if($editable): ?><div class='nav2'><a class='button' href='#' onclick="fs_modal('user/modify_profile_image/<?php echo $memberId; ?>'); return false;"><?php echo $this->functions->_e("modify avatar", $dictionary); ?></a></div><?php endif; ?>