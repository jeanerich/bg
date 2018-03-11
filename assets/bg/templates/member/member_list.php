<?php  
	$uusers = $list_users['users'];
	$uimages = $list_users['images'];
	if(count($uusers) > 0): 
	foreach($uusers as $u): ?>

                                    <div class="tile unit">
                    	<div class="mini_hero" <?php if($u['hero_id'] > 0){$img = $uimages[$u['hero_id']]['sizes']['thumb']; echo " style='background: url({$img}) no-repeat;' ";} ?>></div>
                        <a href="<?php echo $u['link']; ?>"><div class="thumb" <?php if($u['avatar_id'] > 0){$img = $uimages[$u['avatar_id']]['sizes']['square']; echo " style='background: url({$img}) no-repeat;' ";} ?>></div></a>
                        <div class="text">
                            <p class="title"><a href="<?php echo $u['link']; ?>"><?php echo $u['name']; ?></a></p>
                            <p class="staff"><?php echo $u['title']; ?></p>
                            
                        </div>
                        <div class="tile_nav">
                        <a href="<?php echo $u['link']; ?>" class="button"><?php echo $this->functions->_e("view", $dictionary); ?></a>
                        </div>
                    </div><?php endforeach; else:  ?>
                    <?php endif; ?>
                                