<div class="popupcontent">
                	<div class="scroll_wrapper">
                    	<div class="scroll_content">
                        	<div class="popup_content">
                            	<div class='padding'>
                                 <div class="head">
    	<h2><?php echo $this->functions->_e("new message", $dictionary); ?></h2>
        <div class="close" onclick="close_messages();"></div>
                                
                                </div>
                                <div class='message_wrapper'>
                                	<div class='message_deck line clearfix'>
                                            <div class ='unit size1of2'>
                                            <?php echo $this->functions->_e("recipient", $dictionary); ?>:
                                            </div>
                                            <div class ='unit size1of2'>
                                                <strong><?php echo $users['users'][$memberId]['name']; ?></strong>
                                            </div>
                                        </div>
                                    
                                	<form id="new_message_form" class='form  clearfix'>
                                        <input type="hidden" id="new_recipient_ids" value="<?php echo $memberId; ?>" />
                                        <div class='formfield paddingtop20'>
                                        	<label><?php echo $this->functions->_e("subject", $dictionary); ?></label>
                                            <input type="text" id="new_message_subject" class="required" placeholder="<?php echo $this->functions->_e("subject", $dictionary); ?>" maxlength="50" />
                                        </div>
                                        <div class='formfield'>
                                        	<label><?php echo $this->functions->_e("message", $dictionary); ?></label>
                                            <textarea id="new_message_body" style="height: 200px;" class="required" placeholder="<?php echo $this->functions->_e("message", $dictionary); ?>"></textarea>
                                        </div>
                                        <div class='formfield'>
                                        	<button class='button' onclick=""><?php echo $this->functions->_e("send", $dictionary); ?></button>
                                        </div>
                                        <div class="processing_mask light">
                                            <div class="circle"></div>
                                            <div class="checkmark"></div>
                                            <div class='form_overlay_message'><p><?php echo $this->functions->_e("message sent", $dictionary); ?></p></div>
                                        </div>
                                    </form>
                                    	
                                    
                                </div>
   
    </div>    </div>
                        </div>
                    </div>
                </div>
<script>
$(document).ready(function(){
	initializeForm("#new_message_form", sendNewMessage);
	$("#new_message_subject").focus();
});

// $_POST['new_recipient_ids']) && isset($_POST['new_message_subject']) && isset($_POST['new_message_body']

function sendNewMessage(){ 
	$("#new_message_form").addClass('processing');
	var recipient_ids = $("#new_recipient_ids").val();
	var message_subject = $("#new_message_subject").val();
	var message_body = $("#new_message_body").val();
	
	
	if(message_body.length > 0 && recipient_ids.length > 0 && message_subject.length > 0){ 
		$.post(site_url + "app/sendMessage/", {
					'new_recipient_ids' : recipient_ids,
					'new_message_subject' : message_subject,
					'new_message_body' : message_body
					},
				   function(data){
					   if(data.success){
						 $("#new_message_form").addClass('success');
						setTimeout(function(){$("#new_message_form").removeClass('processing');  close_messages(); }, 2000);
						
					} else {
						$("#new_message_form").aremoveClass('processing');		 
					}
					  }, "json");
		
	}
}



</script>