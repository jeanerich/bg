<div class="grid clearfix profile_grid">
            <?php foreach($listmembers as $w): ?>
                    
                                        <div class="tile unit">
                            <div class="mini_hero" <?php if($listusers['users'][$w['member_id']]['hero_id'] > 0){
								$img = $listusers['images'][$listusers['users'][$w['member_id']]['hero_id']]['sizes']['thumb']; echo " style='background: url({$img}) no-repeat;'"; } ?> ></div>
                            <a href="<?php echo $listusers['users'][$w['member_id']]['link']; 
							?>"><div class="thumb" <?php if($listusers['users'][$w['member_id']]['avatar_id'] > 0){
								$img = $listusers['images'][$listusers['users'][$w['member_id']]['avatar_id']]['sizes']['thumb']; 
								echo " style='background: url({$img}) no-repeat;'"; } ?>></div></a>
                            <div class="text">
                                <p class="title"><a href="<?php echo $listusers['users'][$w['member_id']]['link']; ?>"><?php echo $listusers['users'][$w['member_id']]['name']; ?></a></p>
                                <p class="staff"><?php echo $listusers['users'][$w['member_id']]['title']; ?></p>
                                
                            </div>
                            <div class="tile_nav">
                            <a href="<?php echo $listusers['users'][$w['member_id']]['link']; ?>" class="button"><?php echo $this->functions->_e("view", $dictionary); ?></a>
                            </div><?php if($editable): ?>
                            <div class='admin_nav'><?php if(isset($w['invitation_tatus'])){if($w['invitation_tatus'] == 0){echo "<span class='status_box'>Pending</span>";} else {echo "<span class='status_box confirmed'>Confirmed</span>";}} ?></div>
                            <?php endif; ?>
                        </div>
                                    
            <?php endforeach; ?>
            </div>
            <?php if($editable): ?>
            <?php if($leader_category == 'judges'): ?>
            <p><a href='<?php echo site_url() . "battles/create/{$battle_id}/#jury_panel"; ?>' class='button'>Manage judges</a></p>
            <?php else: ?>
             <p><a href='<?php echo site_url() . "battles/create/{$battle_id}/"; ?>' class='button'>Manage mentors</a></p>
            <?php endif; ?>
			<?php endif; ?>