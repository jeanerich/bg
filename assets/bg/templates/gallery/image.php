<div id="gallery_wrapper" class=" clearfix">
	<div id="image_wrapper">
    	<div id="bg_image" class='img_background bg_image bg_image' style="background: url(<?php echo $image['source']; ?>) no-repeat;">
        </div>
        <div class='zoom'  onclick="fs_modal('battles/previewImage/<?php echo "{$image_id}/{$image['token']}"; ?>');"></div>
    </div>
    <div id="image_content">
    	
    	<div class="scroll_wrapper<?php if($userId < 1){echo " full";} ?>">
        	<div class="scroll_content">
            	<div class="gallery_image_content">
                	
                   
                    
                    
                	<div class='comments_wrapper'>
                    	
                        <?php if($userId > 0): ?>
                        	<div class='comment_form form clearfix'>
                            	
                            </div>
                        <?php endif; ?>
                        	<div id="thread_list" image_id="<?php echo $image_id; ?>" image_token="<?php echo $token; ?>" thread_id="<?php echo $image['thread_id']; ?>" thread_token="<?php echo $image['thread_token']; ?>">
                            </div>
                    </div>
                </div>
            </div>
       </div>
       
       <div class='image_content_head'>
       <div class='author_box'><?php 
						$imgstring = ""; 
						
						if($memberInfo['avatar_id'] > 0){
							if(isset($users['images'][$memberInfo['avatar_id']]['sizes']['square'])){$imgstring = " style='background: url({$users['images'][$memberInfo['avatar_id']]['sizes']['square']}) no-repeat;' ";}
						}
					?>
                    	<div class='thumb' <?php echo $imgstring; ?>>
                        </div>
                        <div class='author'><p class='member_name'><?php echo $this->functions->_e("by", $dictionary); ?> <a href='<?php echo $memberInfo['link']; ?>'><?php echo $memberInfo['name']; ?></a></p><p class='member_title'><?php echo $memberInfo['title']; ?></p></div>
                    </div>
        	<h1 id="image_title"><?php if(strlen($image['title']) < 1){echo $this->functions->_e("untitled image", $dictionary);} else {echo $image['title']; } ?></h1>
                    <?php if($editable): ?>
                    	<p class='subtext'><a href='#' onclick="$('#image_title').slideToggle(250); $('#title_form_wrapper').slideToggle(250);"><?php echo $this->functions->_e("edit title", $dictionary); ?></a></p>
                        <div class='form dark' id='title_form_wrapper'>
                        	<form id="title_form" class="clearfix">
                            	<div class='formfield'>
                                	<label><?php echo $this->functions->_e("new image name", $dictionary); ?></label>
                                    <input type="text" maxlength="30" id="new_image_title" value="<?php echo $image['title']; ?>" />
                                </div>
                                <div class='formfield'>
                                	<a href='#' class='button' onclick="saveImageTitle(); return false;" ><?php echo $this->functions->_e("save", $dictionary); ?></a> <a href='#' class='button'  onclick="$('.gallery_image_content h1').slideToggle(250); $('#title_form_wrapper').slideToggle(250);"><?php echo $this->functions->_e("cancel", $dictionary); ?></a>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
        	<div class='social_bar clearfix'>
            	<a href='#' class='social_unit facebook'>
                </a><a href='#' class='social_unit twitter'>
                </a>
            </div>
        </div><?php if($userId > 0): ?>
        <div id="comment_form">
            <textarea id="comment_message" placeholder="<?php echo $this->functions->_e("type a message", $dictionary); ?>"></textarea>
            <a href="#" class="chat_add_image" onclick="return false;"></a>
           </div><?php endif; ?>
       
    </div>
    <div id="image_details">
    	<div class='padding'>
        	<h2><?php echo $this->functions->_e("description", $dictionary); ?></h2>
            <?php if($editable): ?>
            <p class='subtext'><a href='#' onclick="$('#image_description_form_wrapper').slideToggle(250); $('#image_description').slideToggle(250); return false;"><?php echo $this->functions->_e("edit description", $dictionary); ?></a></p>
            <?php endif; ?>
            <div class='shortbar softblink'></div><br/>
            <div id="image_description" class='line'>
            <?php $description = $image['description']; 
				if(strlen($description) > 0){echo nl2br($description);} else {echo $this->functions->_e("no description was provided yet.", $dictionary);}
			?></div>
            <?php if($editable): ?>
            <div class='form darker' id="image_description_form_wrapper">
            	<form id="image_description_form" class='darker clearfix'>
                	<div class='formfield'>
                    	<label><?php echo $this->functions->_e("new description", $dictionary); ?></label>
                        <textarea id="image_description_field" style="height: 250px;"><?php echo $description; ?></textarea>
                    </div>
                    <div class='formfield'>
                    	<a href='#' class='button'onclick="saveImageDescription(); return false;"><?php echo $this->functions->_e("save", $dictionary); ?></a> <a href='#' class='button'onclick="saveImageDescription(); return false;"><?php echo $this->functions->_e("cancel", $dictionary); ?></a>
                    </div>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>