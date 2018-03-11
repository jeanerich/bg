<div class='modal_mini_panel login'>
<h1><?php echo $this->functions->_e("missing email", $dictionary); ?></h1>
<p><?php echo $this->functions->_e("Please provide us with your email", $dictionary); ?></p>
<div class='formwrap fullwidth'>
        
        <div class='form'>
        	<form id="init_email_form">
        	<div class='formfield'>
            	<label><?php echo $this->functions->_e("email", $dictionary); ?></label>
                <input type="email" id="user_email" class="email required" placeholder="<?php echo $this->functions->_e("your email", $dictionary); ?>" />
            </div>
            
            <div class='form_message'>
            </div>
            <div class='formfield'>
            	<button class='submit'><?php echo $this->functions->_e("submit", $dictionary); ?></button>
            </div>
            </form>
            
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
	$("#user_email").focus();
	initializeForm("#init_email_form", initEmail);
	
	setReturnLink();
	
});

function initEmail(){
	var user_email = $("#user_email").val();
	
	$.post(site_url + "app/setMissingUserEmail/", {
					'user_email' : user_email
					},
				   function(data){
					   if(data.success){
						   $('#init_email_form .form_message').removeClass('error');
						   $('#init_email_form .form_message').html("<?php echo $this->functions->_e("Thank you! Email was sent. Double-check your spam box if you don't see it.", $dictionary); ?>");
						   $('#init_email_form .form_message').slideDown(250);
						   
						   setTimeout(close_fs_modal, 4000);
						   
						   
							
						 } else {
							 $('#init_email_form .form_message').addClass('error');
						   $('#init_email_form .form_message').html("Login failed.");
						   $('#init_email_form .form_message').slideDown(250);
						}
					  }, "json");
}
</script>