<div class='deck'><div class='deck_head nobottompadding'>
                    <h2><?php echo $this->functions->_e("subscribed to the feed of", $dictionary); ?> <?php echo $memberInfo['name'];  ?></h2>
                </div>
                <div class='grid clearfix profile_grid'>
                <?php if(count($followers['users']) > 0): foreach($followers['users'] as $b): ?>
                    <div class='tile unit'>
                    	<div class='mini_hero' <?php if($b['hero_id'] > 0){echo " style='background: url(" . $followers['images'][$b['hero_id']]['sizes']['thumb'] . ") no-repeat;'";} ?>></div>
                        <a href='<?php echo $b['link']; ?>'><div class='thumb'<?php if($b['avatar_id'] > 0){echo " style='background: url(" . $followers['images'][$b['avatar_id']]['sizes']['square'] . ") no-repeat;'";} ?>></div></a>
                        <div class='text'>
                            <p class='title'><a href='<?php echo $b['link']; ?>'><?php echo $b['name']; ?></a></p>
                            <p class='staff'><?php echo $b['title']; ?></p>
                            
                        </div>
                        <div class='tile_nav'>
                        <a href='<?php echo $b['link']; ?>' class='button'><?php echo $this->functions->_e("view", $dictionary); ?></a>
                        </div>
                    </div>
                <?php endforeach;  else: ?>
                	<p><strong><?php echo $this->functions->_e("nobody yet...", $dictionary); ?></strong></p>
                <?php endif; ?>
                </div>
                </div>