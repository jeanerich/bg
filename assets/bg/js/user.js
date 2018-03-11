var user_updates = new Object();
var update_frequency = 10;
var new_messages = new Array();

$(document).ready(function(){
	init_updates();
	getUpdates();
	
	
});



function toggle_notifications(){
	if($(".footer_console .node.notifications").hasClass('open')){
		
		$(".footer_console .node.notifications .popup").slideUp(250);
		$(".footer_console .node.notifications").removeClass('open');
	} else {
		$(".footer_console .node.messages .popup").hide();
		$(".footer_console .node.messages").removeClass('open');
		
		$("#page_footer .footer_console .node.notifications .scroll_content .popup_content").load(site_url + "app/getUserNotifications/");
		
		$(".footer_console .node.notifications .popup").slideDown(250);
		$(".footer_console .node.notifications").addClass('open');
	}
}

function toggle_messages(){
	if($(".footer_console .node.messages").hasClass('open')){
		$(".footer_console .node.messages .popup").slideUp(250);
		$(".footer_console .node.messages").removeClass('open');
		chat_update_frequency = 20;
		
	} else {
		//$("#page_footer .footer_console .node.messages .scroll_content .popup_content ").load(site_url + "app/getUsermessages/");
		
		$(".footer_console .node.notifications .popup").hide();
		$(".footer_console .node.notifications").removeClass('open');
		
		$(".footer_console .node.messages .popup").slideDown(250);
		$(".footer_console .node.messages").addClass('open');
	}
}



function init_updates(){
	
	setInterval(getUpdates, update_frequency * 1000);
}

function getUpdates(){
	$.post(site_url + "app/getUserUpdates/", {
					'null' : 0
					},
				   function(data){
					   if(data.success){
						   new_messages = Array();
						   if(data.new_messages.length > 0){new_messages = data.new_messages.split(',');}
						   var no_new_notifications = data.new_notifications; if(no_new_notifications > 99){no_new_notifications = "99+";}
						   var no_new_messages = data.new_messages.split(',').length; if(no_new_messages > 99){no_new_messages = "99+";}
						 
						 if(data.new_notifications > 0){
							 
							 $("#page_footer .footer_console .node.notifications .touch span").show();
						 } else {
							 $("#page_footer .footer_console .node.notifications .touch span").hide();
						}
						
						if(data.new_messages > 0){
							  $("#page_footer .footer_console .node.messages .touch span").show();
						 } else {
							 $("#page_footer .footer_console .node.messages .touch span").hide();
						}
						
						highlightActiveThreads();
						  $("#page_footer .footer_console .node.notifications .touch span").text(no_new_notifications);
						  $("#page_footer .footer_console .node.messages .touch span").text(no_new_messages);
						  }
					  }, "json");
}

function newMessage(targetUser){
	$("#page_footer #new_message").load(site_url + "app/newmessage/?u=" + targetUser);
	$("#page_footer #new_message").slideDown(250);
}

function close_messages(){
	$("#page_footer #new_message").slideUp(250);
}


