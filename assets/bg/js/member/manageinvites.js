$(document).ready(function(){
	
});


function cancelInviation(invitationId){
	 
	$.post(site_url + "app/deleteInvite/", {
					'invite_id' : invitationId
					},
				   function(data){
					   if(data.success){
						  $("#invite_" + invitationId).slideUp(500, function(){$("#invite_" + invitationId).remove();}); 
						  }
					  }, "json");
}