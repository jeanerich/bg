<div class='modal_mini_panel '>
<h1><?php echo $this->functions->_e("invite a friend", $dictionary); ?></h1>
<p><?php echo $this->functions->_e("share battle gallery with your friends and colleagues", $dictionary); ?></p>
<div class=''>
        
        <div class='form'>
        	<form id="modal_invite_form" class='clearfix'>
        	<div class='formfield'>
            	<label><?php echo $this->functions->_e("first name", $dictionary); ?></label>
                <input type="text" id="user_first_name" class="user_first_name required" placeholder="<?php echo $this->functions->_e("first name", $dictionary); ?>" />
            </div>
            <div class='formfield'>
            <div class='formfield'>
            	<label><?php echo $this->functions->_e("last name", $dictionary); ?></label>
                <input type="text" id="user_last_name" class="required" placeholder="<?php echo $this->functions->_e("last name", $dictionary); ?>" />
            </div>
            	<label><?php echo $this->functions->_e("email", $dictionary); ?></label>
                <input type="email" id="user_email" class="email required" placeholder="<?php echo $this->functions->_e("email", $dictionary); ?>" />
            </div>
            <div class='form_message'>
            </div>
            <div class='formfield'>
            	<button class='submit'><?php echo $this->functions->_e("submit", $dictionary); ?></button>
            </div>
            <div class='processing_mask'>
            	<div class='circle'></div>
                <div class='checkmark'></div>
                
            </div>
            </form>
            
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
	$("#user_first_name").focus();
	initializeForm("#modal_invite_form", userInvite);
	
	setReturnLink();
	
});

function userInvite(){
	$("#modal_invite_form").addClass('processing');
	var email = $("#user_email").val();
	var first_name = $("#user_first_name").val();
	var last_name = $("#user_last_name").val();
	
	$.post(site_url + "app/inviteMember/", {
					'email' : email,
					'first_name' : first_name,
					'last_name' : last_name
					},
				   function(data){
					   if(data.success){
						  	$("#modal_invite_form").addClass('success'); 
							var returnLink = site_url + "member/invites/";
							setTimeout(function(){$("#modal_invite_form").removeClass('processing'); window.location.replace(returnLink);}, 1000);
						} else {
							 $("#modal_invite_form").removeClass('processing');
						}
					  }, "json");


}
</script>