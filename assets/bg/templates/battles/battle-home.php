<div class='unit size3of4'>
        	<div class='unit_right_padding'>
            	<?php if($userId > 0 && isset($role['status']) && $role['status'] < 1 && $role['pending'] == 1): ?>
                <div id="invitation_pending" class='deck paddingbottom60'>
                <div id="invitation_pending" class='deck paddingbottom60'>
                <h2><?php echo $this->functions->_e("invitations", $dictionary); ?></h2>
                <script>
				$(document).ready(function(){
					fs_modal("app/viewBattleInvitation/<?php echo $battle_id; ?>/");	
				});
				</script>
                </div>
                <?php endif; ?>
            	
                <div class='paddingbottom60'>
                	<div class='deck_head'>
                    <h3><?php echo $this->functions->_e("short description", $dictionary); ?></h3>
                    </div>
                    <?php echo nl2br($battle['battle']['battle_short_description']); ?>
                </div>
                <div class='deck' id="enter_battle_deck">
                	<?php if(isset($entry) && count($entry) > 0): // if the user has already entered the competition, or not, show instructions.?>
                    <div class='deck_head'><h3><?php echo $this->functions->_e("your entry", $dictionary); ?></h3></div>
                    <?php include(DIR_TEMPLATES . "battles/submit_battle.php"); ?>
                    <?php else: // user is logged in but hasn't yet entered the competition. ?>
                	<h3><?php echo $this->functions->_e("how to join the competition", $dictionary); ?></h3>
                	<div class='sub_deck instructions paddingbottom60'>
                    	<ul><?php 
							$steps = []; 
							if($userId < 1){ // if user is not logged in
								$steps[] = "<a href='#' onclick=\"fs_login(); return false;\" >" .$this->functions->_e("sign up", $dictionary) . "</a>"; 
								if($battle['battle']['battle_type'] == 'team'){$steps[] = $this->functions->_e("select a team", $dictionary); }
								$steps[] = $this->functions->_e("submit your entry", $dictionary); 
							} else { // if user is logged in
								if($battle['battle']['battle_type'] == 'team'){ // if this is a team competition
									if($teams['in_team'] < 1){
										$steps[] = $this->functions->_e("select a team", $dictionary);
										$steps[] = $this->functions->_e("submit your entry", $dictionary); 
									}
								} else { // if this is NOT a team competition
									include(DIR_TEMPLATES . "battles/submit_battle.php");
								}
							}
							$counter = 1;
							if(count($steps) > 0): foreach($steps as $step): ?>
                            	<li><div class='node'><?php echo $counter; ?></div><?php echo $step; $counter++; ?></li>
                            <?php endforeach; endif; ?>
						</ul>
                    </div>
                    
                    <?php endif; ?>
                </div>
                <?php if($battle['battle']['battle_type'] == 'team'): ?>
                <div class='paddingbottom60'>
                	<div class='deck_head'>
                    	<h3><?php echo $this->functions->_e("teams", $dictionary); ?></h3><?php  //print_r($entry); ?>
                    </div>
                    <div id="teams_list" class='list clearfix' ajax-data="<?php echo site_url() . "battles/showBattleTeams/{$battle_id}/"; ?>">
                <?php include(DIR_TEMPLATES . "battles/listteams.php"); ?>
                </div>
                </div>
                <?php endif; ?>
                <?php if(count($leaders['judges']) > 0 || $editable): ?>
                <div class='paddingbottom60'>
                	<div class='deck_head'>
                    	<h3><?php echo $this->functions->_e("judges", $dictionary); ?></h3>
                    </div>
                     <?php $leader_category = "judges"; $listmembers = $leaders['judges']; if(isset($leaders['users'])){$listusers = $leaders['users'];}
					 	include(DIR_TEMPLATES . "battles/member-grid.php");
					 ?>
                     
                </div>
                <?php endif; ?>
                <?php if(count($leaders['mentors']) > 0 || $editable): ?>
                <div class='paddingbottom60'>
                	<div class='deck_head'>
                    	<h3><?php echo $this->functions->_e("mentors", $dictionary); ?></h3>
                    </div>
                    <?php $leader_category = "mentors"; $listmembers = $leaders['mentors']; if(isset($leaders['users'])){$listusers = $leaders['users'];}
					 	include(DIR_TEMPLATES . "battles/member-grid.php"); ?>
                </div>
                <?php endif; ?>
               
            </div>
        </div>
        <div class='unit size1of4'>
        	<h3><?php echo $this->functions->_e("battle calendar", $dictionary); ?></h3>
            <div class='battle_calendar'>
            	<div class='calendar_item'>
                	<div class='calendar_item_head'><h4><?php echo $battle['battle']['s_date']; ?></h4>
                    </div>
                    <div class='calendar_item_content'><?php echo $this->functions->_e("battle starts", $dictionary); ?></div>
                </div>
                <div class='calendar_item'>
                	<div class='calendar_item_head'><h4><?php echo $battle['battle']['v_date']; ?></h4></div>
                    <div class='calendar_item_content'><?php echo $this->functions->_e("vote begins", $dictionary); ?></div>
                </div>
                <div class='calendar_item'>
                	<div class='calendar_item_head'><h4><?php echo $battle['battle']['e_date']; ?></h4></div>
                    <div class='calendar_item_content'><?php echo $this->functions->_e("battle ends", $dictionary); ?></div>
                </div>
            	<?php // print_r($battle); ?>
            </div>
        </div>