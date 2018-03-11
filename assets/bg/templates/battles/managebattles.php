<div class='w1200 paddingtop40 paddingbottom60'>
	<h1>Manage battles</h1>
    <?php 
	//print_r($battles);
	$bImages = $battles['images'];
	$no_battles = $battles['no_battles']; 
	$battles = $battles['battles'];
	
	
	$page_config['base_url'] = site_url() . 'battles/manage/';
	$page_config['total_rows'] = $no_battles;
	$page_config['reuse_query_string'] = TRUE;
	$page_config['suffix'] = "";
	$page_config['per_page'] = 20;
	
	$this->pagination->initialize($page_config);
	$pagination = $this->pagination->create_links();
	echo "<p class='pagination'>{$pagination}</p>";

	?>
    
   <div id="manage_battles" class='list line'>
	<?php if($no_battles > 0): foreach($battles as $battle):
		$editlink = site_url() . "battles/create/" . $battle['battle_id'] . "/";
		$viewlink = site_url() . "battles/view/" . $battle['battle_id'] . "/";
		$imgId = 0;
		$imgstring = "";
		if($battle['card_image'] > 0){$imgId = (int)$battle['card_image'];} else {$imgId = (int)$battle['hero_image'];}
		if($imgId > 0){
			$imgstring = " style='background: url(" . $bImages[$imgId]['sizes']['hero'] . ") no-repeat;'";
		}
		 ?>       
        <div class='list_item clearfix'>
        	<a href='<?php echo $editlink; ?>'><div class='unit thumb'¨ <?php echo $imgstring; ?>></div></a>
            <div class='line unit '>
            	<div class='textwrapper'>
                	<h2><?php echo $battle['battle_name'];?></h2> <?php //print_r($battle); ?>
                    <ul>
                    	<li>Start date: <?php echo date("Y-m-d", strtotime($battle['start_date'])); ?></li>
                        <li>Vote begins: <?php echo date("Y-m-d", strtotime($battle['vote_date'])); ?></li>
                        <li>End date: <?php echo date("Y-m-d", strtotime($battle['end_date'])); ?></li>
                    </ul>
                    
                </div>
                <div class='console clearfix'>
                	<?php if(strtotime($battle['start_date']) > time()){$time_left = strtotime($battle['start_date']) - time(); $days_left = ceil($time_left / 86400); echo  "<div class='unit console_item time'>Begins in {$days_left} days</div>";} ?> 
                    <?php if(strtotime($battle['start_date']) < time() && strtotime($battle['vote_date']) > time()){$time_left = strtotime($battle['vote_date']) - time(); $days_left = ceil($time_left / 86400); echo  "<div class='unit console_item tovote'>Vote begins in {$days_left} days</div>";} ?> 
                    <?php if(strtotime($battle['vote_date']) < time() && strtotime($battle['end_date']) > time()){$time_left = strtotime($battle['end_date']) - time(); $days_left = ceil($time_left / 86400); echo  "<div class='unit console_item vote'>{$days_left} days left to vote</div>";} ?> 
                    <?php if(strtotime($battle['end_date']) < time()){echo  "<div class='unit console_item complete'>Battle ended</div>";} ?> 
                	<?php if($battle['admin_approved'] < 1): ?><div class='unit console_item approval_status pending'>PENDING APPROVAL</div><?php else: ?><div class='unit console_item approval_status approved'>APPROVED</div><?php endif; ?>
                </div>
                <div class='nav clearfix'>
                	<a href='<?php echo $viewlink; ?>' class='unit view'>View Battle</a>
                    <a href='<?php echo $editlink; ?>' class='unit edit'>Edit Battle</a>
                </div>
            </div>
        </div>
        <?php endforeach; endif; ?>
        <?php 
		
	
	$this->pagination->initialize($page_config);
		echo "<p class='pagination'>{$pagination}</p>"; ?>
   </div>
   <a href='<?php echo site_url() . "battles/create/"; ?>' class='button'>Create Battle</a>
</div>