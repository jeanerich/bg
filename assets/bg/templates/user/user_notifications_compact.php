<?php 
if(isset($notifications['images'])){$n_images = $notifications['images'];}
	
	if(isset($notifications['users'])){$n_users = $notifications['users']['users'];}
	$notif = $notifications['notifications'];
	
	if(isset($notifications['battles'])){$battles = $notifications['battles'];  }
	
	?>
    <div class='head'>
    	<h2><?php echo $this->functions->_e("notifications", $dictionary); ?></h2>
        <div class='close' onclick="toggle_notifications();"></div>
    </div><?php if(isset($no_notifications)): ?>
    	<div class='pagination'><?php 
			
			$baseurl = site_url() . "user/notifications/";		

			$config['base_url'] = $baseurl;
			$config['total_rows'] = $no_notifications;
			$config['per_page'] = 20;
			
			$this->pagination->initialize($config);
			
			echo $this->pagination->create_links();
		?>
        </div>
    <?php endif; ?>
    <ul class='pop_up_list'>
    	<?php if(count($notif) > 0): ?>
        <?php foreach($notif as $n): ?>
        	<?php switch($n['type']){
				case "comment": ?>
					<?php $hasthumb = false; if(isset($n['extra']['image_id'])){$hasthumb = true; $imgsrc = $n_images[$n['extra']['image_id']]['sizes']['square'];} ?>
        	<li id="notification_<?php echo $n['notification_id']; ?>" class='<?php if($hasthumb){echo 'indent';} ?>'>
            	<?php if($hasthumb): ?><a href='<?php echo $n['extra']['link']; ?>'><div class='thumb' style="background: url(<?php echo $imgsrc; ?>) no-repeat;"></div></a><?php endif; ?>
                <p><a href='<?php echo $n_users[$n['from_user_id']]['link']; ?>'><?php echo "<strong>" . $n_users[$n['from_user_id']]['name'] . "</strong>"; ?></a> <?php echo $this->functions->_e("posted a comment on a thread you are involved with.", $dictionary);?> <a href='<?php echo $n['extra']['link']; ?>'><?php echo $this->functions->_e("click here to view this discussion.", $dictionary); ?></a>
                </p>
                <p class='notification_time'><?php echo $this->functions->_e("sent", $dictionary) . ": " . $n['time']; ?></p>
            </li>
                <?php
				break;	
				case "battle_leader":?>
					<?php $hasthumb = false;  if(isset($battles['battles'][$n['extra']['battle_id']]['card_image']) && $battles['battles'][$n['extra']['battle_id']]['card_image'] > 0){$hasthumb = true; $imgsrc = $battles['images'][$battles['battles'][$n['extra']['battle_id']]['card_image']]['sizes']['square'];}  $targeturl = site_url() . "battles/view/{$n['extra']['battle_id']}/" . urlencode($battles['battles'][$n['extra']['battle_id']]['battle_name']);?>
                    <li id="notification_<?php echo $n['notification_id']; ?>" class='<?php if($hasthumb){echo 'indent';} ?>'>
                        <?php if($hasthumb): ?><a href='<?php echo $n['extra']['link']; ?>'><div class='thumb' style="background: url(<?php echo $imgsrc; ?>) no-repeat;"></div></a><?php endif; ?>
                        <p><a href='<?php echo $n_users[$n['from_user_id']]['link']; ?>'><?php echo "<strong>" . $n_users[$n['from_user_id']]['name'] . "</strong>"; ?></a> <?php echo $this->functions->_e("invited you to join this battle: <strong>", $dictionary) . $battles['battles'][$n['extra']['battle_id']]['battle_name'] . "</strong>";  ?> <a href='<?php echo $targeturl; ?>'><?php echo $this->functions->_e("view battle", $dictionary); ?></a>
                        </p>
                        <p class='notification_time'><?php echo $this->functions->_e("sent", $dictionary) . ": " . $n['time']; ?></p>
                    </li>
                <?php
				break;
			}
       			
              endforeach; ?>
        <?php else: ?>
        <li><p><?php  echo $this->functions->_e("no notifications", $dictionary); ?></p></li>
        <?php endif; ?>
    </ul><?php if(isset($no_notifications)): ?><div class='pagination'>
     <?php $this->pagination->initialize($config);
			
			echo $this->pagination->create_links(); ?>
            </div>
            <?php endif; ?>
