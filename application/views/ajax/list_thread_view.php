<?php 
	if(isset($threads['users']['users'])){$users = $threads['users']['users'];}
	if(isset($threads['users']['images'])){$user_images = $threads['users']['images'];}
	$messages = $threads['messages'];
	if(count($messages) > 0):
	$noted_time = "";
	foreach($messages as $m):
	$time = $m['post_time']; 
	if($noted_time != $m['post_time']): ?>
    	<p class='noted_time' ><?php echo $m['post_time']; $noted_time = $m['post_time']; ?></p>
    <?php endif; ?>
<div class="message_item message_<?php echo $m['message_id']; if($userId == $m['user_id']){echo " self";} ?>" message_id="<?php echo $m['message_id']; ?> not_initiated"><?php
						$imgstring = ""; 
						$memberInfo = $users[$m['user_id']];
						if($memberInfo['avatar_id'] > 0){
							if(isset($user_images[$memberInfo['avatar_id']]['sizes']['square'])){$imgstring = " style='background: url({$user_images[$memberInfo['avatar_id']]['sizes']['square']}) no-repeat;' ";}
						}
						
						
					?>
                    	<div class='thumb' <?php echo $imgstring; ?>>
                        </div>
                        <div class='tip'></div>
    <div class='author_box'>
                        <div class='author'><p class='member_name'><?php echo $this->functions->_e("by", $dictionary); ?> <a href='<?php echo $memberInfo['link']; ?>'><?php echo $memberInfo['name']; ?></a></p></div>
                    </div>
	<div class='text_wrap'>
    	<p><?php echo nl2br($m['message']); ?></p>
    </div>
    <?php if($userId > 0 && $m['editable']): ?>
    	<div class='message_options'>
        	<div class='touch' onclick="$(this).parent().toggleClass('open');"></div>
            <ul>
            <?php if($m['editable']): ?>
            	<li><a href='#' onclick="deleteMessage(<?php echo $m['message_id']; ?>); return false;">Delete</a></li>
            <?php else: ?>
            	<li><a href='#' >Report this message</a></li>
                <li><a href='#' >Report this user</a></li>
            <?php endif; ?>
            </ul>
        </div>
    <?php endif; ?>
</div><?php endforeach; else: ?>
	<p>No comments yet.</p>
<?php endif; ?>