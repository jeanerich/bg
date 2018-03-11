<div class='w1200 paddingtop40'>
	<h2 class='paddingbottom40'><?php  echo $this->functions->_e("subject", $dictionary); ?>: <?php echo $message['message']['subject']; ?></h2>
    <ul id="" class="secondary_menu clearfix">             
<li class="<?php if($action == 'inbox'){echo "active";} ?>"><a href="<?php echo site_url(); ?>user/messages/"><?php  echo $this->functions->_e("inbox", $dictionary); ?></a></li>
                        <li class="<?php if($action == 'sent'){echo "active";} ?>"><a href="<?php echo site_url(); ?>user/messages/?action=sent"><?php  echo $this->functions->_e("sent messages", $dictionary); ?></a></li>
        
        
        </ul>
    <div class='deck message_deck'>
    	<div class='clearfix'>
        	<div class='unit size1of3'>
            	<?php if($action == 'inbox'): ?>
            	<strong><?php echo $this->functions->_e("from", $dictionary);  ?>:</strong><span><a href='<?php echo $message['users']['users'][$message['message']['from_user_id']]['link']; ?>'><?php echo $message['users']['users'][$message['message']['from_user_id']]['name']; ?></a></span>
				<?php else: ?>
				<strong><?php echo $this->functions->_e("to", $dictionary);  ?>:</strong><span><a href='<?php echo $message['users']['users'][$message['message']['to_user_id']]['link']; ?>'><?php echo $message['users']['users'][$message['message']['to_user_id']]['name']; ?></a></span>
				<?php endif; ?>
            </div>
            <div class='unit size1of3'>
            	<?php if($action == 'inbox'): ?>
            	<strong><?php echo $this->functions->_e("to", $dictionary);  ?>:</strong><span><a href='<?php echo $message['users']['users'][$message['message']['to_user_id']]['link'];  ?>'><?php echo $message['users']['users'][$message['message']['to_user_id']]['name'];  ?></a></span>
				<?php else: ?>
				<strong><?php echo $this->functions->_e("from", $dictionary);  ?>:</strong><span><a href='<?php echo $message['users']['users'][$message['message']['from_user_id']]['link'];  ?>'><?php echo $message['users']['users'][$message['message']['from_user_id']]['name'];  ?></a></span>
				<?php endif; ?>
            </div>
            <div class='unit size1of3'>
            	<strong><?php  echo $this->functions->_e("time", $dictionary); ?></strong> <span><?php echo $message['message']['message_time']; ?></span>
            </div>
        </div>
    	
    	
    </div>
    <div class='deck message_deck message_body'>
    	<?php echo nl2br($message['message']['message_body']); ?>
    </div>
</div>