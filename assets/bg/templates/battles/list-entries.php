<div class='deck paddingtop40'>
        	<div class='deck_head'>
            	<h3><?php echo $this->functions->_e("entries", $dictionary); ?></h3>
            </div>
            <div class="grid clearfix profile_grid"><?php //print_r($warriors['users']['images']); ?>
            <?php  foreach($warriors['warriors'] as $w): ?>
            <?php 
			$userlink = $warriors['users']['users'][$w['member_id']]['link'];
			$entrylink = site_url() . "battles/entry/{$battle_id}/{$w['warrior_id']}/" . urlencode($warriors['users']['users'][$w['member_id']]['name']);
			$imgstring = "";
			$imgid = 0;
			 if(isset($w['entries'][0])){$imgid = $w['entries'][0]['id']; $imgstring = " style='background: url(" . $warriors['images'][$imgid]['sizes']['square'] . ") no-repeat;'";} ?>
            	<div class="talltile unit">
                	<div class='thumb' imgid="<?php echo $imgid; ?>" <?php echo $imgstring; ?>><?php if($imgid > 0): ?><a href='#' class='zoom' onclick="fs_modal('battles/previewImage/<?php echo $imgid . "/" . $warriors['images'][$imgid]['token']; ?>'); return false;"></a><?php endif; ?></div>
                    <div class='textwrapper'>
                    	<h3><a href='<?php echo $userlink; ?>'><?php 
						echo $warriors['users']['users'][$w['member_id']]['name']; ?></a></h3><?php //print_r($w['entries'][0]); ?>
                        
                        
                    </div><div class='tilenav'><a href='<?php echo $entrylink; ?>' class='button'><?php echo $this->functions->_e("view", $dictionary); ?></a></div><?php $imgstring = ""; if($warriors['users']['users'][$w['member_id']]['avatar_id'] > 0){$imgstring = "style='background: url(" . $warriors['users']['images'][$warriors['users']['users'][$w['member_id']]['avatar_id']]['sizes']['square'] . ") no-repeat;'";} ?>
                    <a href='<?php echo $userlink; ?>'><div class='avatar' <?php echo $imgstring; ?>></div></a>
                </div>        
            <?php endforeach; ?>
            </div>
        </div>
        
        
        <div class="zoom" onclick="fs_modal('battles/previewImage/548/DTKXOeBb');"></div>