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
    	<?php include(DIR_TEMPLATES . $sub_template);  ?>
    </div>
	
</div>
