<div class='modal_mini_panel login'>
<h1><?php echo $this->functions->_e("register", $dictionary); ?></h1>
<p><?php echo $this->functions->_e("register in a single click...", $dictionary); ?></p>
    <div class='formwrap'>
        <div class='login_method'>
            <a href='<?php echo site_url(); ?>red/fbconfig' class='facebook' onclick=" " ><div class='icon'></div><p>Facebook</p></a>
            <a href='#' class='email' onclick="return false;"><div class='icon'></div><p>Email</p></a>
        </div>
        <div class='form'>
        	<form id="register_form">
        	<div class='formfield'>
            	<label><?php echo $this->functions->_e("first name", $dictionary); ?></label>
                <input type="text" id="user_first_name" class="required" placeholder="<?php echo $this->functions->_e("first name", $dictionary); ?>" value="" />
            </div>
            <div class='formfield'>
            	<label><?php echo $this->functions->_e("last name", $dictionary); ?></label>
                <input type="text" id="user_last_name" class="required" placeholder="<?php echo $this->functions->_e("last name", $dictionary); ?>" value="" />
            </div>
            <div class='formfield'>
            	<label><?php echo $this->functions->_e("email", $dictionary); ?></label>
                <input type="email" id="user_email" class="email required" placeholder="Your email" />
            </div>
            <div class='formfield'>
            	<label><?php echo $this->functions->_e("password (minimum 8 characters)", $dictionary); ?></label>
                <input type="password" id="user_password" class="required" minlength="8" placeholder="******" />
            </div>
            <div class='form_message'></div>
            <div class='formfield'>
            <input type="hidden" id="user_country" class=""  value="" />
            	<input type="hidden" id="user_city" class="" value="" />
            	<button class='submit'><?php echo $this->functions->_e("submit", $dictionary); ?></button>
            </div>
            </form>
            <p><a href='#' onclick="fs_reset_password(); return false;"><?php echo $this->functions->_e("reset password", $dictionary); ?></a></p>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
	$("#user_first_name").focus();
	initializeForm("#register_form", registerUser);
	
	setReturnLink();
});

function registerUser(){
	var user_first_name = $("#user_first_name").val();
	var user_last_name = $("#user_last_name").val();
	var user_email = $("#user_email").val();
	var user_country = $("#user_country").val();
	var user_city = $("#user_city").val();
	var user_password = $("#user_password").val();
	
	console.log('register');
	$.post(site_url + "app/register/", {
					'user_first_name' : user_first_name,
					'user_last_name' : user_last_name,
					'user_email' : user_email,
					'user_country' : user_country,
					'user_city' : user_city,
					'user_password' : user_password
					},
				   function(data){
					   if(data.success){
						   $('#register_form .form_message').removeClass('error');
						   var d = new Date();
							d.setTime(d.getTime() + (1000*24*60*60*1000));
							var expires = "expires="+ d.toUTCString();
							if($(window).width() < 1400){
								document.cookie = 'chat_panel' + "=" + 1 + ";" + expires + ";path=/";
								document.cookie = 'left_panel' + "=invisible;" + expires + ";path=/";
							}
							
							
							
						   if(false){
							   
						   $('#register_form .form_message').html("Registration successful! Check  your email and follow the instructions to complete the process. (Make sure the mail didn't get into your spam box!)");
						   } else {
							  $('#register_form .form_message').html("<?php echo $this->functions->_e("Registration successful! Check  your email", $dictionary); ?>"); 
							}
							
							var returnLink = data.link;
							
							setTimeout(function(){window.location.replace(returnLink);}, 4000);
						   $('#register_form .form_message').slideDown(250);
						} else {
							$('#register_form .form_message').addClass('error');
							$('#register_form .form_message').html(data.errors);
							$('#register_form .form_message').slideDown(250);
							
						}
					  }, "json");
}
</script>