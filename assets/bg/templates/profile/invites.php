<div class='w1200 paddingtop60'>
	<h1><?php echo $this->functions->_e("your invitations", $dictionary); ?></h1>
    <p><a href="#" class="button" onclick="fs_modal('/user/userinvite/'); return false;"><?php echo $this->functions->_e("send invitation", $dictionary); ?></a></p>
    
    <div class='list line'>
    	<?php foreach($invites['invites'] as $invite): ?>
    	<div class='list_items' id="invite_<?php echo $invite['invite_id']; ?>">
        	<?php if($invite['new_user_id'] < 1): ?><h3><?php echo $invite['name']; ?></h3>
            <div class='form clearfix'>
            	<div class='formfield half'>
                	<label>Email</label>
                	<input type="text" value="<?php echo $invite['email']; ?>" readonly>
                </div>
                <div class='formfield half'>
                	<label><?php echo $this->functions->_e("link", $dictionary); ?></label>
                	<input type="text" value="<?php echo $invite['link']; ?>" onfocus="$(this).select();" readonly>
                    <p class='sublabel'><?php echo $this->functions->_e("share this site with a friend", $dictionary); ?></p>
                </div>
            </div>
			<div class='nav'>
            	<a href='#' class='button'><?php echo $this->functions->_e("send invitation by email", $dictionary); ?></a> <a href='#' class='button darker'  onclick="$(this).next().fadeToggle(250); return false; "><?php echo $this->functions->_e("cancel invitation", $dictionary); ?></a> <a href='#' onclick="cancelInviation(<?php echo $invite['invite_id']; ?>); return false;" class='button red hidden'><?php echo $this->functions->_e("confirm delete", $dictionary); ?></a>
            </div>
            <?php else: ?>
            <div class='line padding20'>
            	<p><strong><a href='<?php echo $invites['users']['users'][$invite['new_user_id']]['link']; ?>'><?php echo $invite['name']; ?></a></strong> <?php echo $this->functions->_e("accepted your invitation", $dictionary); ?> <strong><a href='<?php echo $invites['users']['users'][$invite['new_user_id']]['link']; ?>'><?php echo $this->functions->_e("view profile", $dictionary); ?></a></strong></p>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
        
        
    </div>
</div>