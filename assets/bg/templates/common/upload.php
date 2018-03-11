<?php 
	$upload_tar = false;
	$upload_title = "file_upload"; 
	
	if(isset($upload_form)){$upload_title = $upload_form;}
	if(isset($upload_target)){$upload_tar = $upload_target;}
	

?><style>
	.upload_form{
		display: block;
	}
	
	#<?php echo $upload_title; ?>_target{
		width: 100%;
		height: 200px;
		border: 2px #999 dashed;	
		display: block;
	}
</style><div class="upload_form" id="<?php echo $upload_title; ?>"><?php if($upload_tar): ?><div id='<?php echo $upload_title; ?>_target'></div><?php endif; ?><input class="file_Upload" type="file" name="file_upload" /></div>
<script>
$(document).ready(function(){
	$(function() {
        $('#<?php echo $upload_title; ?> .file_upload').uploadifive({
            'uploadScript' : '<?php echo site_url(); ?>red/upload/'<?php if($upload_target): ?>,
			'queueID'  : '<?php echo $upload_title; ?>_target'<?php endif; ?>
            // Put your options here
        });
    });

});


</script>