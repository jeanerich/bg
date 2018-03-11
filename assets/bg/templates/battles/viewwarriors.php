<div class='deck paddingtop40'>
        	<div class='deck_head'>
            	<h3><?php echo $this->functions->_e("warriors", $dictionary); ?></h3>
            </div>
            <div class="grid clearfix profile_grid">
            <?php  foreach($warriors['warriors'] as $w): ?>
                    
                                        <div class="tile unit">
                            <div class="mini_hero" <?php if($warriors['users']['users'][$w]['hero_id'] > 0){$img = $warriors['users']['images'][$warriors['users']['users'][$w]['hero_id']]['sizes']['thumb']; echo " style='background: url({$img}) no-repeat;'"; } ?> ></div>
                            <a href="<?php echo $warriors['users']['users'][$w]['link']; 
							?>"><div class="thumb" <?php if($warriors['users']['users'][$w]['avatar_id'] > 0){
								$img = $warriors['users']['images'][$warriors['users']['users'][$w]['avatar_id']]['sizes']['thumb']; 
								echo " style='background: url({$img}) no-repeat;'"; } ?>></div></a>
                            <div class="text">
                                <p class="title"><a href="<?php echo $warriors['users']['users'][$w]['link']; ?>"><?php echo $warriors['users']['users'][$w]['name']; ?></a></p>
                                <p class="staff"><?php echo $warriors['users']['users'][$w]['title']; ?></p>
                                
                            </div>
                            <div class="tile_nav">
                            <a href="<?php echo $warriors['users']['users'][$w]['link']; ?>" class="button"><?php echo $this->functions->_e("view", $dictionary); ?></a>
                            </div>
                        </div>
                                    
            <?php endforeach; ?>
            </div>
        </div>