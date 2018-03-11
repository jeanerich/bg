<div id="footer">
	<div class='w1200'>
    	<div class='line clearfix paddingtop50 '>
            <div class="unit size1of4">
                <h3>Menu</h3>
                <ul><?php if($userId > 0):  ?>
                 	<li><div class='submenu_wrapper'><a href='<?php echo site_url() . "user/notifications/"; ?>' ><?php echo $this->functions->_e("notifications", $dictionary); ?></a></div></li>
                 	<li><div class='submenu_wrapper'><a href='<?php echo site_url() . "user/messages/"; ?>' ><?php echo $this->functions->_e("inbox", $dictionary); ?></a></div></li>
                    <li><div class='submenu_wrapper'><a href='<?php echo site_url() . "user/messages/?action=sent"; ?>' ><?php echo $this->functions->_e("sent messages", $dictionary); ?></a></div></li>
                 <?php endif; ?></ul>
            </div>
            <div class="unit size1of4">
                <ul><?php if($userId > 0):  ?>
                    	<li><div class='submenu_wrapper'><a href='<?php echo $users[$userId]['link']; ?>' ><?php echo $this->functions->_e("my profile", $dictionary); ?></a></div></li>
                        <li><div class='submenu_wrapper'><a href='<?php echo site_url() . "member/portfolio/{$userId}/" . urlencode($users[$userId]['name']); ?>' ><?php echo $this->functions->_e("my portfolio", $dictionary); ?></a></div></li>
                        <li><div class='submenu_wrapper'><a href='<?php echo site_url() . "member/followers/{$userId}/" . urlencode($users[$userId]['name']); ?>' ><?php echo $this->functions->_e("my fans", $dictionary); ?></a></div></li>
                        <li><div class='submenu_wrapper'><a href='<?php echo site_url() . "member/following/{$userId}/" . urlencode($users[$userId]['name']); ?>' ><?php echo $this->functions->_e("my favorites", $dictionary); ?></a></div></li>
                        <li><div class='submenu_wrapper'><a href='<?php echo site_url() . "member/invites/{$userId}/" . urlencode($users[$userId]['name']); ?>' ><?php echo $this->functions->_e("my invites", $dictionary); ?></a></div></li>
                        <li><div class='submenu_wrapper'><a href='<?php echo site_url(); ?>battles/create/' ><?php echo $this->functions->_e("create a battle", $dictionary); ?></a></div></li>
                        
                        <?php else: ?>
                        <?php endif; ?>
                    </ul>
            </div>
            <div class="unit size1of2">
                 
            </div>
        </div>
    </div>
</div>
<div id="page_footer">
<?php if($userId > 0): ?>
	<div id="chat_console">
    	<div class='chat_wrapper'></div>
        
    </div>
	<div class='footer_console'>
    	<div class='node notifications'>
        	<div class='touch' onclick="toggle_notifications();"><span>0</span></div>
            <div class='popup'>
            	<div class='popupcontent'>
                	<div class='scroll_wrapper'>
                    	<div class='scroll_content'>
                        	<div class='popup_content'></div>
                            <p class='more'><a href='<?php echo site_url() . "user/notifications/"; ?>'><?php echo $this->functions->_e("view all notifications", $dictionary); ?></a></p>
                        </div>
                    </div>
                </div>
            	<div class='tip'></div>
            </div>
        </div>
        <div class='node messages'>
        	<div class='touch' onclick="toggle_messages();"><span>0</span></div>
            <div class='popup chatpopup'>
            	<div class="overflow">
                    <div id='chat_horizontal_wrapper'>
                    	<div class='panel'>
                        	<div class='panel_head'>
                            	<h3 class='title'><?php echo $this->functions->_e("messages", $dictionary); ?></h3>
                            </div>
                            <div class='wrap'>
                            	<div class='scroll_wrapper'>
                                    <div class='scroll_content'>
                                        <div id="threads_list" class='popup_content'></div>
                                    </div>
                               </div>
                            	
                            </div>
                        </div>
                        <div id="user_chat_panel" class='panel'>
                        	
                        </div>
                    </div>
                </div>
            	<div class='tip'></div>
            </div>
        </div>
    </div>
   
<?php endif; ?>

</div>