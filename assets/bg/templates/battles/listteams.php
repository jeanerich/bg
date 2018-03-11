<?php if(count($teams['teams']) > 0): foreach($teams['teams'] as $team): ?>
            	<div class='tile unit' id="team_<?php echo $team['team_id']; ?>">
                <?php $imageSrc = ""; 
					if($team['team_card'] > 0){
						if(isset($teams['images'][$team['team_card']]['sizes']['card'])){
							$imageSrc = 	$teams['images'][$team['team_card']]['sizes']['card'];
						} else {
							$imageSrc = 	$teams['images'][$team['team_card']]['sizes']['thumb'];
						}
						$imageSrc = " style='background: url($imageSrc) no-repeat;'";
					}
					//print_r($teams);
				?>
                	<div class='thumb' <?php echo $imageSrc; ?>>
                    </div>
                    <div class='textwrapper'>
                    <h3><?php echo $team['team_name']; ?></h3>
                    	<p class='members'><span><?php echo count($team['team_members']); ?></span> members </p>
                        	
                    </div>
                    <div class="tile_nav">
                    	<?php if(strtotime($battle['battle']['vote_date']) > time() || ($battle['battle']['battle_and_vote'] == 1 && strtotime($battle['battle']['end_date']) > time())): // if this is before the vote ?>
                		<?php if($teams['in_team'] < 1 || $team['team_id'] == $teams['in_team']): ?><a href="#" onclick="<?php if($userId > 0){if($teams['in_team'] > 0){echo "fs_leave_team({$team['team_id']}); return false;";} else {echo "fs_join_team({$team['team_id']}); return false;";}} else {echo "fs_register(); return false;";} ?>" class="button join_button <?php if($userId > 0){if($teams['in_team'] > 0){} else {echo " softblink";} } ?>"><?php if($teams['in_team'] > 0){echo $this->functions->_e("leave team", $dictionary);} else { echo $this->functions->_e("join", $dictionary);} ?></a><?php endif; ?><a href="<?php echo site_url(); ?>/battles/team/<?php echo $battle_id . "/{$team['team_id']}/" . urlencode($team['team_name']); ?>" class="button darker"><?php echo $this->functions->_e("view", $dictionary); ?></a><?php else: ?><a href="<?php echo site_url(); ?>/battles/team/<?php echo $battle_id . "/{$team['team_id']}/" . urlencode($team['team_name']); ?>" class="button  line"><?php echo $this->functions->_e("view", $dictionary); ?></a><?php endif; ?>
                	</div>
                </div>
            <?php endforeach; else: ?>
            <p>No teams were found.</p>
            <?php endif; ?>