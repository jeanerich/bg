$(document).ready(function(){
	/*setTimeout(function(){
		$(".presentation").animate({"opacity" : 1}, 3000);
	}, 1000);
	
	setTimeout(function(){
		$(".presentation").animate({"opacity" : 0}, 3000);
	}, 5000);
	
	setTimeout(function(){
		$("#confirm_form").fadeIn(2000);
	}, 8000);*/
	$("#confirm_form").fadeIn(500);
	
	initializeForm("#invitation_form", registerNewMember);
	
	
}); 

// ($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['invite_id']) && isset($_POST['invite_token']))

function registerNewMember(){
	var invite_id = parseFloat($("#invite_id").val());
	var invite_token = $("#invite_token").val();
	var first_name = $("#invite_first_name").val();
	var last_name = $("#invite_last_name").val();
	var email = $("#invite_email").val();
	var password = $("#invite_password").val();
	
	$.post(site_url + "app/confirmInvite/", {
					'invite_id' : invite_id,
					'invite_token' : invite_token,
					'first_name' : first_name,
					'last_name' : last_name,
					'email' : email,
					'password' : password
					},
				   function(data){
					   if(data.success){
						   var targetUrl = site_url + "member/?welcome=true";
						   window.location.replace(targetUrl);
					   }
					  }, "json");
}
