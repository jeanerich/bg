<div class='modal_mini_panel login'>
<h1><?php echo $this->functions->_e("reset password", $dictionary); ?></h1>
<p><?php echo $this->functions->_e("enter your email to reset your password", $dictionary); ?></p>
    <div class='formwrap'>
        <div class='login_method'>
            <a href='<?php echo site_url(); ?>red/fbconfig' class='facebook' onclick=" " ><div class='icon'></div><p>Facebook</p></a>
            <a href='#' class='email' onclick="return false;"><div class='icon'></div><p>Email</p></a>
        </div>
        <div class='form'>
        	<form id="reset_password_form">
        	<div class='formfield'>
            	<label><?php echo $this->functions->_e("email", $dictionary); ?></label>
                <input type="email" id="user_email" class="email required" placeholder="Your email" />
            </div>
            <div class='form_message'></div>
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
	initializeForm("#reset_password_form", resetPassword);
	
	setReturnLink();
});

function resetPassword(){
	var user_email = $("#user_email").val();
	
	
	$.post(site_url + "app/reset_password/", {
					'user_email' : user_email
					},
				   function(data){
					   if(data.success){
						   $('#reset_password_form .form_message').removeClass('error');
						   
							  $('#reset_password_form .form_message').html("<?php echo $this->functions->_e("password reset instructions", $dictionary); ?>"); 
								
							
							
						   $('#reset_password_form .form_message').slideDown(250);
						} else {
							$('#reset_password_form .form_message').addClass('error');
							$('#reset_password_form .form_message').html(data.errors);
							$('#reset_password_form .form_message').slideDown(250);
							
						}
					  }, "json");
}
</script>