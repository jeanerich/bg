function fs_login(){
	fs_modal("user/user_login");
}

function fs_register(){
	fs_modal("user/user_register");
}

function fs_reset_password(){
	fs_modal("user/user_reset_password");
}



function fs_request_email(){
	fs_modal("user/user_request_email");
}



function user_logout(){
	$.post(site_url + "app/user_logout/", {
					'key' : 0
					},
				   function(data){
					   if(data.success){
						   window.location.replace(HOME);
						  }
					  }, "json");
}

function fs_modal(target_url){
	$('#fs_modal .modal_wrapper').load(site_url + target_url);
	$("#fs_modal_mask").fadeIn(500);
	$("#fs_modal").removeClass("closed");
}

function close_fs_modal(){
	
	$("#fs_modal").addClass("closed");
	$("#fs_modal_mask").fadeOut(500);
}

function startFollowing(memberId){
	// app/stopFollowing
	
	$.post(site_url + "app/startFollowing/", {
					'member_id' : memberId
					},
				   function(data){
					   if(data.success){
							$('.roundbutton.follow').parent().addClass('inactive');
							$('.roundbutton.unfollow').parent().removeClass('inactive'); 
							
							$('.user_followers').text(data.followers); 
						}
					  }, "json");
}

function stopFollowing(memberId){
	// app/stopFollowing
	
	$.post(site_url + "app/stopFollowing/", {
					'member_id' : memberId
					},
				   function(data){
					   $('.roundbutton.follow').parent().removeClass('inactive');
							$('.roundbutton.unfollow').parent().addClass('inactive');
							
							$('.user_followers').text(data.followers); 
					  }, "json");
}

function setLocalTimeZone(){
		var timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
		
		var d = new Date();
    d.setTime(d.getTime() + (1 * 24 * 60 * 60 * 1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = 'timezone' + "=" + timezone + ";" + expires + ";path=/";
}
