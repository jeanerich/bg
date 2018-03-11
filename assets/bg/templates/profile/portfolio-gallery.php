<?php  $counter = 0; foreach($gallery as $g): ?>
	<div id="gallery_tile_<?php echo $counter; ?>" class='tile opaque gallery_tile_<?php echo $g; ?>' image_id='<?php echo $g; ?>'><?php 
		$imgstring = "";
		if(isset($gallery_images[$g]['sizes']['square'])){
			$src = $gallery_images[$g]['sizes']['square']; 
		} else {
			$src = $gallery_images[$g]['thumb']; 
		}
		if(strlen($src) > 0){$imgstring = " style='background: url({$src}) no-repeat; '";}
		$imgLink = site_url() . "gallery/image/{$g}/" . $gallery_images[$g]['token'] . "/" . urlencode($gallery_images[$g]['title']);
	?>
    	<div class='img_bg' <?php echo $imgstring; ?>>
        </div>
        <div class='textwrapper'>
        	<h3><?php echo $gallery_images[$g]['title']; ?></h3>
            <div class='shortbar'></div>
            <p class='author'><?php echo $this->functions->_e("by", $dictionary); ?> <strong><a href='<?php echo $users['users'][$memberId]['link']; ?>'><?php echo $users['users'][$memberId]['name']; ?></a></strong></p>
        </div>
        <?php if($editable): ?>
        <div class='handle'></div>
        <div class='top_nav'>
        	
        	<a href='#' class='delete_image' onclick="deleteImage(<?php echo $counter; ?>); return false;"></a>
        </div><?php endif; ?>
        <div class='nav'>
        	<?php if($editable): ?><a href='#' class='button darker' onclick="editImageInfo(<?php echo $counter; ?>); return false;"><?php echo $this->functions->_e("edit", $dictionary); ?></a><?php endif; ?><a href='<?php echo $imgLink; ?>' class='button'><?php echo $this->functions->_e("view", $dictionary); ?></a>
        </div><?php if($editable): ?>
        <div class='tile_form'>
        	<div class='form'>
            	<div class='formfield'>
                	<label><?php echo $this->functions->_e("image name", $dictionary); ?></label>
                    <input type="text" maxlength="30" class='image_title' value="<?php echo $gallery_images[$g]['title']; ?>" placeholder = "image name" />
                    
                </div>
                <div class='formfield'>
                	<a href='#' onclick="saveImageTitle(<?php echo $counter; ?>); return false;" class='button'><?php echo $this->functions->_e("save", $dictionary); ?></a><a href='#'  class='button darker' onclick="closeImageForm(<?php echo $counter; ?>); return false;"><?php echo $this->functions->_e("cancel", $dictionary); ?></a>
                </div>
            </div>
        </div><?php endif; ?>
    </div>
    <?php $counter++; endforeach; ?>