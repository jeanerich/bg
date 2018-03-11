var chat_update_frequency = 20;
var chatInterval = new Object();
var chat_type = '';
var current_chat_member = 0;
var page_title = "";

$(document).ready(function(){
	page_title = document.title;
});



function showChat(chatId){
	
	if(!$("#chat_box .chat_wrapper:eq(" + chatId + ")").hasClass('open')){ 
		$("#chat_box .chat_wrapper").removeClass('open');
		$("#chat_box .chat_wrapper:eq(" + chatId + ")").addClass('open');
		var d = new Date();
		d.setTime(d.getTime() + (1000*24*60*60*1000));
		var expires = "expires="+ d.toUTCString();
		document.cookie = 'chat_sub_panel' + "=" + chatId + ";" + expires + ";path=/";
	}
	
	if(chatId == 1){
		loadDirectMessages();
	}
	
}



function check_new_messages(){
	
	if(chat_type == 'group'){
		getNewGroupMessages(); console.log(chat_type);
		setTimeout(check_new_messages, chat_update_frequency * 1000);
	} else {
		getNewDirectMessages();
		setTimeout(check_new_messages, chat_update_frequency * 1000);
	}
	
}

function scrollThreadBottom(getMethod){
	var content_parent = $("message_flow_wrapper").height();
	var content_height = $(".group_chat_thread .scroll_wrapper #message_flow").height();
	
	if(content_height > content_parent){
		var content_scroll_bottom = content_height - content_parent;
		
		if(getMethod == 'init'){
			$(".group_chat_thread .scroll_wrapper .scroll_content").animate({scrollTop : content_scroll_bottom + "px"}, 0);
		} else {
			$(".group_chat_thread .scroll_wrapper .scroll_content").animate({scrollTop : content_scroll_bottom + "px"}, 250);
		}
	}
	
	
}

function loadDirectMessages(){
	$.post(site_url + "app/getDirectChatThreads/", {
					
					},
				   function(data){
					   if(data.success){
						   var threads = data.threads;
						   var output = "";
						   if(threads.length > 0){
							 	for(var i = 0; i < threads.length; i++){
									var thread = threads[i];
									var imgstring = ""; if(thread.avatar_id > 0){imgstring = " style=' background: url(" + data.images[thread.avatar_id]['sizes']['square'] + ") no-repeat;'";}
									output += "<li class='padding40'><div class='thumb'" + imgstring + "></div><a href='#' onclick='loadDirectThread(" + thread.to_user_id + "); return false;'>" + thread.to_user_name + "</a></li>";	
								}
								
								$("#direct_chat").html(output);
							 }
						   
						   
						 }
					  }, "json");
}

function directMessage(memberId){
	$.post(site_url + "app/opendirectmessage/", {
					'member_id' : memberId
					},
				   function(data){
					   if(data.success){
						   loadDirectMessages();
						   loadDirectThread(memberId)
						 }
					   
					  }, "json");
}

function loadDirectThread(member_id){
	$("#chat_box").removeClass('closed');
	var d = new Date();
    d.setTime(d.getTime() + (1000*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = 'chat_panel' + "=0;" + expires + ";path=/";
	
	showChat(1);
	
	
	var target_url = site_url + "app/displayDirectChat?mid=" + member_id;
	$("#chat_box #chat_horizontal_wrapper .rightpanel").load(target_url);
	$("#chat_box #chat_horizontal_wrapper").addClass('pushed');
	
	chat_update_frequency = 1;
}

function initDirectChat(){
	var member_id = parseFloat($("#message_flow").attr("member_id"));
	current_chat_member = member_id;
	$("#chat_field").focus();
	$("#chat_field").keyup(function(e){
		var code = e.which;
		if(code == 13){e.preventDefault();}
		if(code == 13){
			var message_string = $("#chat_field").val();
			if(message_string.length > 1){
				postDirectMessage(member_id, message_string);
				
				$("#chat_field").val("");
				$("#chat_field").focus();
			}
		}	
	});
	
	
}

function postDirectMessage(member_id, message_string){
	$.post(site_url + "app/directPostMessage/", {
					'member_id' : member_id,
					'message' : message_string,
					'extra' : ''
					},
				   function(data){
					   if(data.success){
						  $("#chat_field").val(""); 
						 // getDirectMessages();
						  }
					  }, "json");
}

function getDirectMessages(getMethod){
	
	var memberId = parseFloat($("#chat_thread").attr("member_id"));
	
	setViewedDirectThreads(memberId);
	
	var message_id = 0;
	if($("#message_flow .message").size() > 0){
		message_id = parseFloat($("#message_flow .message:last").attr('message_id'));
	} 
	
	$.post(site_url + "app/getDirectMessages/", {
					'member_id' : memberId,
					'action' : getMethod,
					'message_id' : message_id
					},
				   function(data){
					   if(data.success){
						   var output = "";
						   var last_time = "";
						 	if(data.no_messages > 0){
								data.messages.reverse();
								for(var i = 0; i < data.no_messages; i++){
									
									var p = data.messages[i];
									if($("#user_message_" + p['message_id']).size() < 1){
										var imgString = "";
										
										if(p['avatar_id'] > 0){imgString = "background: url(" + data.images[p['avatar_id']]['sizes']['square'] + ") no-repeat;";}
										if(p.time != last_time){output += "<p class='noted_time'>" + p.time + "</p>";}
										output += "<div class='message' id='user_message_" + p['message_id'] + "' message_id='" + p['message_id'] + "'><div class='thumb' style='" + imgString + "' alt='" + p.user_name + "'></div><div class='bubble'>" + p.message + "</div></div>";
										//output += "<div class='message' id='user_message_" + p['message_id'] + "' message_id='" + p['message_id'] + "'><div class='thumb' style='" + imgString + "'></div><p><strong>" + p.user_name + "</strong> <span class='post_time' time='" + p.atime + "'>" + p.time + "</span></p>" + p.message + "</div>";
									}
								}
								console.log(output);
								$("#message_flow").append(output);
								//adjustTime();
								if(getMethod == 'init'){check_new_messages();}
								scrollThreadBottom(getMethod);
							}  
							
							//initCheckUserNewMessage();
						}
					  }, "json");
}

function closeDirectChatWindow(){
	current_chat_member = 0;
	chat_update_frequency = 10000; 
	$('#chat_box #chat_horizontal_wrapper').removeClass('pushed'); 
	return false;
}

function getNewDirectMessages(){
	
	chat_type = 'direct';
	var message_id = 0;
	if($("#message_flow .message").size() > 0){
		message_id = parseFloat($("#message_flow .message:last").attr('message_id'));
	} 
	
	getDirectMessages('new', message_id);
}

function setViewedDirectThreads(memberId){
	memberString = memberId.toString();
	
	var dt = readCookie('viewed_direct_threads');
	if(dt != null){memberString = dt + "," + memberId; memberArray = memberString.split(','); var newArray = removeDuplicatesArray(memberArray); memberString = newArray.join(',');}
	
	var d = new Date();
    d.setTime(d.getTime() + (1000*180));
    var expires = "expires="+ d.toUTCString();
   document.cookie = 'viewed_direct_threads' + "=" + memberString + ";" + expires + ";path=/";
}

function getNotifications(){
	$.post(site_url + "app/checkNotifications/", {
					
					},
				   function(data){
					   if(data.success){
						  
						  var no_messages = data.new_messages.length;
						  
						  $("#page_footer .footer_nav .pod.messages .bubble p").text(no_messages);
						  if(no_messages > 0){
							  	 if(no_messages > 99){no_messages = "99+";}
								$("#page_footer .footer_nav .pod.messages key.bubble").addClass('active'); 
								document.title = "Tribulink (" + no_messages + ")"; 
							} else {
								$("#page_footer .footer_nav .pod.messages .bubble").removeClass('active');
								document.title = page_title; 
							}
						  }
					  }, "json");
}




