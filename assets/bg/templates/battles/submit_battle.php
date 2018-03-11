<div class='submission_deck<?php  if(!isset($entry['warrior_id'])){if($battle['battle']['battle_type'] == 'team'){echo " select_team";} else {echo " active";}} ?>'>
	<div class='sub_deck submit_entry'>
    	<p><?php echo $this->functions->_e("how to submit to battle", $dictionary); ?></p>
    	<div  id="gallery_grid" class='preview_window'>
        	<div class='scroll_wrapper'>
            	<div class='scroll_content'>
                <?php if(!empty($entry['media']) > 0):
					 foreach($entry['media'] as $ei): 
					 	$imgstring = "";
						if($ei['type'] == 'image'){$imgstring = " style=\"background: url({$entry['images'][$ei['id']]['sizes']['square']}) no-repeat; \"";}
					 ?>
                	<div id='' class='tile' media_type="<?php echo $ei['type']; ?>" media_id="<?php echo $ei['id']; ?>" <?php echo $imgstring; ?>><a href='#' class='delete' onclick='delaySaveEntries(); $(this).parent().remove(); return false;' ></a><a href='#' class='preview' onclick="fs_modal('battles/previewImage/<?php echo "{$ei['id']}/{$entry['images'][$ei['id']]['token']}/"; ?>?dark=dark'); return false;"></a></div>
                <?php endforeach; endif; ?>
                	
					
                </div>
            </div>
        </div>
        <div class='submission_nav'><a href='#' class='button' onclick="fs_modal('user/add_battle_image/'); return false;"><?php echo $this->functions->_e("add image", $dictionary); ?></a><?php if(false): ?><a href='#' class='button darker' onclick="fs_modal('user/add_video/); return false;"><?php echo $this->functions->_e("add video", $dictionary); ?></a><?php endif; ?></div>
    </div>
    <div class='sub_deck enter_competition'>
    <div class='preview_window clearfix'>
    	<div class='preview_nav'><a href='#' class='button softblink' onclick="joinSingleBattle(<?php echo $battle_id; ?>); return false;"><?php echo $this->functions->_e("enter battle", $dictionary); ?></a></div>
        </div>
    </div>
    <div class='sub_deck join_team'>
    <div class='preview_window clearfix'>
    	<div class='preview_nav'><a href='#' class='button softblink' onclick="  return false;"><?php echo $this->functions->_e("join a team below", $dictionary); ?></a></div>
        </div>
        <p>Begin by joining a team below.</p>
    </div>
                    	
                        
                    </div>
<script>
<?php  if(!isset($entry['warrior_id'])): ?>

function joinSingleBattle(battleId){
	$.post(site_url + "app/joinBattle/", {
					'battle_id' : battleId,
					'team_id' : 0
					},
				   function(data){
					   if(data.success){
						  $(".submission_deck").removeClass('active'); 
						  }
					  }, "json");
	
}




<?php endif; ?>
function delaySaveEntries(){
	setTimeout(function(){saveEntries();}, 500);
}

function saveEntries(){
	var media_list = {};
	var media_string = "";
	var no_tiles = $(".submission_deck .sub_deck .preview_window .scroll_content .tile").size();
	if(no_tiles > 0){
		for(var i = 0; i < no_tiles; i++){
			var media_item = {};
				media_item['media_type'] = $(".submission_deck .sub_deck .preview_window .scroll_content .tile").eq(i).attr('media_type');
				media_item['id'] = $(".submission_deck .sub_deck .preview_window .scroll_content .tile").eq(i).attr('media_id');
				
				media_list[i] = media_item;
		}
		
		media_string = JSON.stringify(media_list);
	}
	
	$.post(site_url + "app/saveMediaToBattleEntry/", {
					'battle_id' : <?php echo $battle_id; ?>,
					'media_string' : media_string
					},
				   function(data){
					   highlightAddMedia();
					  }, "json");
	
}
</script>