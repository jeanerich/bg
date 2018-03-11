<div class='modal_mini_panel upload'>
<h1><?php echo $page_title; ?></h1>
<p><?php echo $this->functions->_e("You can either upload a new image or use one you already uploaded to your library.", $dictionary); ?></p>

<div class='panels_wrapper'>
	<ul class='panel_head'>
    	<li class='active'><a href='#' onclick="swapUploadPanel(0); return false;"><?php echo $this->functions->_e("upload", $dictionary); ?></a></li>
    	<li><a href='#' onclick="swapUploadPanel(1); return false;"><?php echo $this->functions->_e("library", $dictionary); ?></a> </li>
    </ul>
    <div class='panels'>
    	<div class='pane upload active'>
        	<h2><?php echo $this->functions->_e("upload", $dictionary); ?></h2><?php if(!isset($user_id)){$user_id = 0;} 
				$onCompleteFunction = "";
			?>
            <?php 
			$upload_type = "post_image";
			if(isset($_GET['type'])){$upload_type = $_GET['type'];}
			$this->functions->upload_form("post_image", 'target1', $image_type, "registerImage", 0, "select file", $user_id); ?>
        </div>
        <div class='pane library'>
        	<div class='library_head'>
        	<h2><?php echo $this->functions->_e("library", $dictionary); ?></h2>
            	<div class='nav'><a href='#' class='button' onclick="saveSelection(); return false;"><?php echo $this->functions->_e("use selection", $dictionary); ?></a></div>
            </div>
            <div class='image_grid'> 
            <?php if(count($image_library) > 0): foreach($image_library as $key => $img):  ?>
           
            	<div id='library_tile_<?php echo $key; ?>' class='tile' tile_id='<?php echo $key; ?>' tile_token='<?php echo $img['token']; ?>' onclick='toggleTiles(<?php echo $key; ?>);'>
                	<div class='tile_bg' style='background: url(<?php echo $img['sizes']['square']; ?>) no-repeat; '></div>
                    <div class='checkbox'></div>
                </div>
             <?php endforeach; ?>
            <?php else:  endif; ?>
            <?php // print_r($image_library); ?>
            </div>
        </div>
    </div>
</div>

</div>
<script>
function swapUploadPanel(paneId){
	$(".modal_mini_panel.upload .panel_head li").removeClass('active');
	$(".modal_mini_panel.upload .panel_head li").eq(paneId).addClass('active');
	
	$(".modal_mini_panel.upload .panels .pane").removeClass('active');
	$(".modal_mini_panel.upload .panels .pane").eq(paneId).addClass('active');
	
}

function registerImage(uploadData){
	var output = "<div id='library_tile_" + uploadData.image_id + "' class='tile' tile_id='" + uploadData.image_id + "' onclick='toggleTiles(" + uploadData.image_id + ");' tile_token='" + uploadData.token + "'><div class='tile_bg' style='background: url(" + uploadData.square + ") no-repeat; '></div><div class='checkbox'></div></div>";
	
	
	$(".image_grid").prepend(output);
	$(".image_grid .tile").removeClass('active');
	$("#library_tile_" + uploadData.image_id).addClass('active');
	toggleNav();
	swapUploadPanel(1);
	
	
}

function registerFeedImage(output){
	
}

function toggleTiles(tile_id){
	<?php if(!$multiple_images): ?>$(".tile.active").removeClass('active');
	<?php endif; ?>$('#library_tile_' + tile_id).toggleClass('active');
	toggleNav();
}

function toggleNav(){
	if($('.image_grid .tile.active').size() > 0){
	$('.panels_wrapper .library .library_head .nav').show();
	} else {
		$('.panels_wrapper .library .library_head .nav').hide();
	}
}

function saveSelection(){
	var image_type = '<?php echo $image_type; ?>';
	var image_id = 0;
	var img_array = [];
		var src_array = []; 
	if($(".image_grid .tile").size() > 0){ 
	<?php if(true): ?>
		
		var preview_output = "";
		var no_tiles = $(".image_grid .tile.active").size();
		if(no_tiles > 0){ 
			for(var i = 0; i < no_tiles; i++){ 
				img_array[i] = parseFloat($(".image_grid .tile.active").eq(i).attr('tile_id'));
				var tile_style = $(".image_grid .tile.active:eq(" + i + ") .tile_bg").attr('style'); 
				var tile_token = $("#library_tile_" + img_array[i]).attr('tile_token');  //console.log(tile_token);
				preview_output += "<div class='tile' media_type='image' media_id='" + img_array[i] + "' style=\"" + tile_style + "\" id=\"preview_tile_" + img_array[i] + "\" img_id='" + img_array[i] + "' ><a href='#' class='delete' onclick='delaySaveEntries(); $(this).parent().remove(); return false;'></a>";
				preview_output += "<a href='#' class='preview' onclick=\"fs_modal('battles\/previewImage\/" + img_array[i] + "\/" + tile_token + "?dark=dark'); return false;\"></a><\/div>";
			}
			var image_id = img_array.join(',');
		}
		
	
	<?php endif; ?>
	<?php if($image_type == 'gallery'): ?>
	var image_string = img_array.join(',');
	$(".submission_deck .sub_deck .preview_window .scroll_content").prepend(preview_output);
	delaySaveEntries();
	close_fs_modal();
	/*$.post(site_url + "app/addImageToGallery/", {
					'image_ids' : image_string
					},
				   function(data){
					   if(data.success){
						   loadGrid();
						   close_fs_modal();
						  }
					  }, "json");*/
	
	
	<?php else: ?>
	
		$.post(site_url + "app/saveImageSelection/", {
					'image_type' : image_type,
					'image_ids' : image_id,
					'memberId' : <?php echo $user_id; ?>
					},
				   function(data){
					   if(data.success){
						 	var rimage =  data.image;
							<?php if($image_type == 'hero'): ?>
							var rthumb = rimage[image_id]['source'];
							$("#profile_head .background").attr({"style" : "background: url(" + rthumb + ") no-repeat;"});
							<?php elseif($image_type == 'battle_hero'): ?>
								var rthumb = rimage[image_id]['source'];
							$("#main_hero").attr({"style" : "background: url(" + rthumb + ") no-repeat;"});
							<?php elseif($image_type == 'battle_card'): ?>
								var rthumb = rimage[image_id]['source'];
							$(".cardWrapper").attr({"style" : "background: url(" + rthumb + ") no-repeat;"});
							<?php elseif($image_type == 'team_card'): ?>
								var rthumb = rimage[image_id]['source'];
							$("#team_<?php echo $user_id; ?> .thumb").attr({"style" : "background: url(" + rthumb + ") no-repeat;"});
							<?php elseif($image_type == 'avatar'): ?>
							var rthumb = rimage[image_id]['sizes']['square'];
							$("#profile_head #avatar_image").attr({"style" : "background: url(" + rthumb + ") no-repeat;"});
							
							
							<?php endif; ?>
							
							close_fs_modal();
							
						 }
					  }, "json");
	<?php endif; ?>
		
	}
}


</script>