<div id="main_header">
	<div class='toggle_menu' onclick="$('body').toggleClass('open');">
    	<div class='bars_wrapper'>
    		<div class='pivot'><div class='bar'></div></div>
            <div class='pivot'><div class='bar'></div></div>
            <div class='pivot'><div class='bar'></div></div>
            </div>
            <p><?php echo $this->functions->_e("menu", $dictionary); ?></p>
    </div>
	<div id="logo">
	<h1><a href='<?php echo site_url(); ?>'><?php echo SITE_NAME; ?></a></h1>
    </div>
    
    <?php if($userId > 0): ?>
    <ul id="nav">
    	<li class='hero'><a href='<?php echo site_url() . "member/invites/{$userId}/" . urlencode($users[$userId]['name']); ?>' ><div class='icon'></div><?php echo $this->functions->_e("my invites", $dictionary); ?></a></li>
        <li class='warrior'><a href='<?php echo site_url(); ?>member/warriors/' ><div class='icon'></div><?php echo $this->functions->_e("warriors", $dictionary); ?></a></li>
        <li class='battles'><a href='<?php echo site_url(); ?>battles/' onclick=""><div class='icon'></div><?php echo $this->functions->_e("battles", $dictionary); ?></a></li>
        <?php  
		if($users[$userId]['is_battle_master'] > 0): ?>
        <li class='create_battle'><a href='<?php echo site_url(); ?>battles/create/' onclick=""><div class='icon'></div><?php echo $this->functions->_e("create a battle", $dictionary); ?></a></li><?php endif; ?>
    </ul>
    <?php else: ?>
    <ul id="nav">
    	<li><a href='#' onclick="fs_login(); return false;"><?php echo $this->functions->_e("login", $dictionary); ?></a></li>
        <?php if(!isset($options) || (isset($options) && !$options['is_beta'])): ?><li><a href='#' onclick="fs_register(); return false;"><?php echo $this->functions->_e("register", $dictionary); ?></a></li><?php endif; ?>
    </ul>
    <?php endif; ?>
    <div id='langbox' onmouseleave="$('#lang_selector').slideUp(250);" onclick="$('#lang_selector').slideToggle(250); "><?php $lang_array = array("french" => "fr", "english" => "en", "spanish" => "sp", "mandarin" => "中文"); ?>
                <p class='lang'><?php  if($userId > 0 && isset($_COOKIE['user_lang'])){echo $lang_array[$_COOKIE['user_lang']];} ?><span class='icon_tip'></span></p>
                <ul id='lang_selector'>
                    <li><a href='#' onclick="swapLanguage('english'); return false;" >English</a></li>
                    <li><a href='#' onclick="swapLanguage('french'); return false;" >Français</a></li>
                    <li><a href='#' onclick="swapLanguage('spanish'); return false;" >Español</a></li>
                    <li><a href='#' onclick="swapLanguage('mandarin'); return false;" >中文</a></li>
                </ul>
            </div>
    <div id="left_panel">
    	<?php include(DIR_TEMPLATES . "common/left_menu.php"); ?>
    </div>
</div>
<div id="fs_modal_mask"></div>
    <div id="fs_modal" class="closed">
    	
        <div class='modal_wrapper'></div>
        <div class='close_modal' onclick="close_fs_modal();"></div>
    </div>
    <div id="side_menu">
    	<div class='menu_button' onclick="toggleRightMenu();">
        	<div class='pivot'><div class='bar'></div></div>
            <div class='pivot'><div class='bar'></div></div>
            <div class='pivot'><div class='bar'></div></div>
            <p><?php echo $this->functions->_e("shortcuts", $dictionary); ?></p>
        </div>
        <ul id="main_menu">
        	<?php if($userId > 0/* && $users[$userId]['is_battle_master'] > 0 */): ?><li><div class='pivot'><div class='slider'><a href='<?php echo site_url(); ?>battles/create/'><?php echo $this->functions->_e("create a battle", $dictionary); ?></a></div></div></li>
            <li><div class='pivot'><div class='slider'><a href='<?php echo site_url(); ?>battles/manage/'><?php echo $this->functions->_e("manage battles", $dictionary); ?></a></div></div></li><?php endif; ?>
            <li><div class='pivot'><div class='slider'><a href='#' onclick="fs_modal('/user/userinvite/'); return false;"><?php echo $this->functions->_e("invite friend", $dictionary); ?></a></div></div></li>
            <?php if(false): ?>
            <li><div class='pivot'><div class='slider'><a href='#'>Menu item 4</a></div></div></li>
            <li><div class='pivot'><div class='slider'><a href='#'>Menu item 5</a></div></div></li>
            <li><div class='pivot'><div class='slider'><a href='#'>Menu item 6</a></div></div></li>
            <?php endif; ?>
        </ul>
        
    </div>