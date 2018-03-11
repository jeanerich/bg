<?php 
	//$m_images = $messages['images'];
	
	$no_messages = $messages['no_messages'];
	$m_users = $messages['users']['users'];
	$m_images = $messages['users']['images'];
	$msg = $messages['messages'];
	$msglength = 50;
	if($format == 'full'){$msglength = 200;}
	
	?>
    <div class='head'>
    	<h2 class='paddingbottom30'><?php echo $this->functions->_e("messages", $dictionary); ?></h2><?php if($format == 'full'): ?><ul id="" class="secondary_menu clearfix">             
<li class="<?php if($action == 'inbox'){echo "active";} ?>"><a href="<?php echo site_url(); ?>user/messages/"><?php  echo $this->functions->_e("inbox", $dictionary); ?></a></li>
                        <li class="<?php if($action == 'sent'){echo "active";} ?>"><a href="<?php echo site_url(); ?>user/messages/?action=sent"><?php  echo $this->functions->_e("sent messages", $dictionary); ?></a></li>
        
        
        </ul>
		<?php endif; ?>
        <div class='close' onclick="toggle_messages();"></div>
    </div><?php
		$source_name = $this->functions->_e("to", $dictionary) . ":"; 
		if($action == 'sent'){
			
		} else {
			$source_name = $this->functions->_e("from", $dictionary) . ":"; 
		}
		 if($format == 'full'): ?>
    	<div class='pagination'><?php 
			
			$baseurl = site_url() . "user/messages/";		

			$config['base_url'] = $baseurl;
			$config['total_rows'] = $no_messages;
			$config['per_page'] = $per_page;
			if($action == 'sent'){
				$config['suffix'] = '?action=sent';
				$config['first_url'] = $config['base_url'] . $config['suffix'];
			}
			$this->pagination->initialize($config);
			
			echo $this->pagination->create_links();
		?>
        </div>
    <?php endif; ?>
    <ul class='pop_up_list'>
    	<?php if(count($msg) > 0): ?>
        <?php foreach($msg as $n): ?>
       			<?php $hasthumb = false; if(isset($m_users[$n['from_user_id']]['avatar_id'])){$hasthumb = true; $imgsrc = $m_images[$m_users[$n['from_user_id']]['avatar_id']]['sizes']['square'];} ?>
        	<li id="notification_<?php echo $n['message_id']; ?>" class='<?php if($hasthumb){echo 'indent';} ?>'>
            	<?php if($hasthumb): ?><a href='<?php echo $n['extra']['link']; ?>'><div class='thumb' style="background: url(<?php echo $imgsrc; ?>) no-repeat;"></div></a><?php endif; ?>
                <p><?php echo $source_name . " "; ?><a href='<?php echo $m_users[$n['from_user_id']]['link'] . "/"; ?>'><?php echo  "<strong>" . $m_users[$n['from_user_id']]['name'] . "</strong>"; ?></a> <p class='time'><?php echo $n['message_time']; ?></p><p class='message_excerpt'> <?php echo $this->functions->truncate($n['message_body'], $msglength, "..."); ?></p><p><a href='<?php echo site_url() . "user/message/{$action}/{$n['message_id']}/{$n['message_token']}/"; ?>' class='<?php if($format == 'full'){echo "button";} ?>'><?php echo $this->functions->_e("read message", $dictionary); ?></a></p>
                </p>
            </li>
             <?php endforeach; ?>
        <?php else: ?>
        <li><p><?php  echo $this->functions->_e("no notifications", $dictionary); ?></p></li>
        <?php endif; ?>
    </ul><?php if($format == 'full'): ?><div class='pagination'>
     <?php $this->pagination->initialize($config);
			
			echo $this->pagination->create_links(); ?>
            </div>
            <?php endif; ?>
