<div class='panel_head'>
    <h3 class='title'><?php echo $user['name']; ?></h3>
    <div class='return_main' onclick="$('#page_footer .footer_console .node .popup #chat_horizontal_wrapper').toggleClass('pushed');"></div>
</div>
<div class='wrap'>
    <div class='scroll_wrapper'>
        <div id="message_flow_wrapper" class='scroll_content'>
            <div id="message_flow" class='popup_content' member_id="<?php echo $member_id; ?>"></div>
            </div>
        </div>
   </div>
   <div id="message_form">
    <textarea id="chat_field" placeholder="<?php echo $this->functions->_e("type a message", $dictionary); ?>"></textarea>
    <a href='#' class='chat_add_image' onclick='return false;' ></a>
   </div>
</div>
                        
<script>
$(document).ready(function(){
	
	
	initDirectChat();
	
	getDirectMessages('init');
	
	
});
</script>
