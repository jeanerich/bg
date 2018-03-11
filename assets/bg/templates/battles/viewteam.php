<div id="main_hero" style=" <?php if($battle['battle']['hero_image'] > 0){echo "background: url(" . $battle['images'][$battle['battle']['hero_image']]['source'] . ") no-repeat;";} ?>">
	<div class='hero_mask'>
    </div>
    <div class="title_card">
    	<div class="icon softblink"></div>
        <h1><?php echo $battle['battle']['battle_name']; ?></h1><h2><?php if($battle['battle']['battle_type'] == 'team'){ echo $this->functions->_e("team battle", $dictionary); } else {echo $this->functions->_e("single battle", $dictionary);} ?></h2><div class="bar"></div>
    </div>
    <div class='nav'><?php if($editable): ?><a href='<?php echo site_url() . "battles/create/{$battle_id}/"; ?>' class='button'><?php echo $this->functions->_e("edit", $dictionary); ?></a><?php endif; ?></div>
</div>
<div id="main_profile_wrapper" class='w1200 paddingtop40'><?php 
//$team = $team['team']; 
?>
	<h2><?php echo $team['team']['name']; ?></h2>
    <div class='deck description paddingtop40'>
    	<div class='deck_head'>
        	<h2><?php echo $this->functions->_e("description", $dictionary); ?></h2>
        </div>
    	<?php echo $team['team']['description']; if(strlen($team['team']['description']) < 1){echo $this->functions->_e("no description yet", $dictionary);  } ?>
    </div>
        <?php if(count($team['team_ids']) > 0): ?>
        <div class='deck paddingtop40'>
        	<div class='deck_head'>
            	<h2><?php echo $this->functions->_e("team members", $dictionary); ?></h2>
            </div>
            <div class="grid clearfix profile_grid">
            <?php  foreach($team['team_ids'] as $ti): ?>
                    
                                        <div class="tile unit">
                            <div class="mini_hero" <?php if($team['users'][$ti]['hero_id'] > 0){$img = $team['images'][$team['users'][$ti]['hero_id']]['sizes']['thumb']; echo " style='background: url({$img}) no-repeat;'"; } ?> ></div>
                            <a href="<?php echo $team['users'][$ti]['link']; ?>"><div class="thumb" <?php if($team['users'][$ti]['avatar_id'] > 0){$img = $team['images'][$team['users'][$ti]['avatar_id']]['sizes']['thumb']; echo " style='background: url({$img}) no-repeat;'"; } ?>></div></a>
                            <div class="text">
                                <p class="title"><a href="<?php echo $team['users'][$ti]['link']; ?>"><?php echo $team['users'][$ti]['name']; ?></a></p>
                                <p class="staff"><?php echo $team['users'][$ti]['title']; ?></p>
                                
                            </div>
                            <div class="tile_nav">
                            <a href="http://localhost:8888/bg/member/home/3/John+Gabriel" class="button"><?php echo $this->functions->_e("view", $dictionary); ?></a>
                            </div>
                        </div>
                                    
            <?php endforeach; ?>
            </div>
        </div>
        <?php else: ?>
			<p><?php echo $this->functions->_e("there is nobody on this team yet.", $dictionary);  ?></p>
		<?php endif; ?>
        
    
</div>