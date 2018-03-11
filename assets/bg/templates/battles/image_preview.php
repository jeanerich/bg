<div id="fs_wrapper_padding">
	<div id="preview_image_wrapper" data-src="<?php echo $image['source']; ?>" data-high-res-src="<?php echo $image['source']; ?>">
    </div>
    
</div>

<script>
$(document).ready(function(){
	setTimeout(function(){
		var viewer = ImageViewer('#fs_wrapper_padding #preview_image_wrapper'); 
	}, 500);
	
	
});

// 
</script>