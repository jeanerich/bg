<div id="home_wrapper">
<div class='deck' id="carousel_deck">
	<div class='carousel'>
    	<div class='carousel_item'>
            <div class='background_image' style="background: url(<?php echo site_url(); ?>assets/temp_images/cover1.jpg) no-repeat;"></div>
            <div class='mask'></div>
            <div class='text_wrapper'>
                <h1><?php echo $this->functions->_e("mentored challenges", $dictionary); ?></h1>
                <p><a href='<?php echo site_url(); ?>battles' class='button'><?php echo $this->functions->_e("discover the battles", $dictionary); ?></a></p>
            </div>
            <div class='credit'>Image credit: Nicolas Niño</div>
        </div>
    </div>
</div>
<div class='deck line clearfix' id="mentor_deck">
	<div class='unit size1of2'><a href='<?php echo site_url(); ?>battles/recruitmentor/'><div class='mentor_icon become_mentor' ></div><div class='text_wrapper'><h2><?php echo $this->functions->_e("become a mentor", $dictionary); ?></h2></div></a></div>
    <div class='unit size1of2'><a href='<?php echo site_url(); ?>battles/listmentors/'><div class='mentor_icon find_mentor' ></div><div class='text_wrapper'><h2><?php echo $this->functions->_e("find a mentor", $dictionary); ?></h2></div></a></div>
</div>
	
</div>