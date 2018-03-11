<div class='modal_mini_panel login'>
<h1><?php echo $this->functions->_e("log in", $dictionary); ?></h1>
<p><?php echo $this->functions->_e("method login", $dictionary); ?></p>
<div class='formwrap'>
        <div class='login_method'>
            <a href='<?php echo site_url(); ?>red/fbconfig' class='facebook' onclick="" ><div class='icon'></div><p>Facebook</p></a>
            <a href='#' class='email' onclick="return false;"><div class='icon'></div><p>Email</p></a>
        </div>
        <div class='form'>
        	<form id="login_form">
        	<div class='formfield'>
            	<label><?php echo $this->functions->_e("email", $dictionary); ?></label>
                <input type="email" id="user_email" class="email required" placeholder="<?php echo $this->functions->_e("your email", $dictionary); ?>" />
            </div>
            <div class='formfield'>
            	<label><?php echo $this->functions->_e("password", $dictionary); ?></label>
                <input type="password" id="user_password" class="required" placeholder="******" />
            </div>
            <div class='form_message'>
            </div>
            <div class='formfield'>
            	<button class='submit'><?php echo $this->functions->_e("submit", $dictionary); ?></button>
            </div>
            </form>
            <p><a href='#' onclick="fs_reset_password(); return false;"><?php echo $this->functions->_e("reset password", $dictionary); ?></a></p>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
	$("#user_email").focus();
	initializeForm("#login_form", userLogin);
	
	setReturnLink();
	
});

function userLogin(){
	var user_email = $("#user_email").val();
	var user_password = $("#user_password").val();
	
	$.post(site_url + "app/user_login/", {
					'user_email' : user_email,
					'user_password' : user_password
					},
				   function(data){
					   if(data.success){
						   $('#login_form .form_message').removeClass('error');
						   $('#login_form .form_message').html("<?php echo $this->functions->_e("login successful", $dictionary); ?>");
						   $('#login_form .form_message').slideDown(250);
						   
						   var returnLink = "/member/home/";
							if (document.cookie.indexOf("return_link=") >= 0) {returnLink = readCookie("return_link");}
							if(returnLink =="http://www.battlegallery.com/"){returnLink = "/member/home/";}
						 	setTimeout(function(){window.location.replace(returnLink);}, 4000);
						 } else {
							 $('#login_form .form_message').addClass('error');
						   $('#login_form .form_message').html("Login failed.");
						   $('#login_form .form_message').slideDown(250);
						}
					  }, "json");
}
</script>