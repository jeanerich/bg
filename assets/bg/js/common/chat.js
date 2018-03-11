var chat = {};
var threads = {};
var users = {};
var chat_update_frequency = 20;
var chat_update_frequency_fast = 5;
var chatInterval = new Object();
var chat_type = '';
var current_chat_member = 0;
var page_title = "";

$(document).ready(function(){
	page_title = document.title;
	loadDirectMessages();
});

function newChat(memberId){
	$(".footer_console .node.notifications .popup").hide();
		$(".footer_console .node.notifications").removeClass('open');
		$(".footer_console .node.messages .popup").slideDown(250);
		$(".footer_console .node.messages").addClass('open');
}

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

function loadDirectMessages(){
	$.post(site_url + "app/getDirectChatThreads/", {},
				   function(data){
					   if(data.success){ 
					   		$("#page_footer .footer_console .node.messages").addClass("open");
							$("#page_footer .footer_console .node.notifications").removeClass('open');
						   var threads = data.threads;
						   var output = "";
						   
						   if(threads.length > 0){
							   for (var key in threads) {
							 	
									var thread = threads[key];
									var imgstring = ""; if(thread.avatar_id > 0){imgstring = " style=' background: url(" + data.images[thread.avatar_id]['sizes']['square'] + ") no-repeat;'";}
									output += "<li class='' id='user_thread_" + thread.to_user_id +  "'><a href='#' onclick='loadDirectThread(" + thread.to_user_id + "); return false;'><div class='thumb'" + imgstring + "><div class='dot'></div></div>" + thread.to_user_name + "</a></li>";	
									
									
									
								}
								
								$("#threads_list").html(output);
								
							 }
						   threads = output;
						   setTimeout(function(){highlightActiveThreads();}, 250);
						   
							//saveChatLocal();
						 }
					  }, "json");
}

function highlightActiveThreads(){
	$("#user_thread_" + msg_id).removeClass('active');
	if(new_messages.length > 0){
		for(var i = 0; i < new_messages.length; i++){
			var msg_id = new_messages[i];
			$("#user_thread_" + msg_id).addClass('active');
			
		}
	}
}

function check_new_messages(){
	getNewDirectMessages();
	setTimeout(check_new_messages, chat_update_frequency * 1000);

}

function scrollThreadBottom(getMethod){
	var content_parent = $("#message_flow_wrapper").height();
	var content_height = $("#message_flow").height();
	
	if(content_height > content_parent){
		var content_scroll_bottom = content_height - content_parent;
		
		if(getMethod == 'init'){
			$("#message_flow").parent().animate({scrollTop : content_scroll_bottom + "px"}, 0);
		} else {
			$("#message_flow").parent().animate({scrollTop : content_scroll_bottom + "px"}, 250);
		}
	}
	
	
}


function directMessage(memberId){
	$.post(site_url + "app/opendirectmessage/", {
					'member_id' : memberId
					},
				   function(data){
					   if(data.success){
						   loadDirectMessages(); // loads threads
						   loadDirectThread(memberId); 
						   
						   $(".footer_console .node.notifications .popup").hide();
							$(".footer_console .node.notifications").removeClass('open');
							
							$(".footer_console .node.messages .popup").slideDown(250);
							$(".footer_console .node.messages").addClass('open');
							
							loadDirectThread(memberId);
							
							chat_update_frequency = chat_update_frequency_fast;
							check_new_messages();
							
						 }
					   
					  }, "json");
}

function loadDirectThread(member_id){
	$("#chat_box").removeClass('closed');
	$("#user_thread_" + member_id).removeClass('active');
	var d = new Date();
    d.setTime(d.getTime() + (1000*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = 'chat_panel' + "=0;" + expires + ";path=/";
	
	showChat(1);
	
	
	var target_url = site_url + "app/displayDirectChat?mid=" + member_id;
	$("#user_chat_panel").load(target_url);
	$("#page_footer .footer_console .node .popup #chat_horizontal_wrapper").addClass('pushed');
	
	chat_update_frequency = chat_update_frequency_fast;
}

function initDirectChat(){
	var member_id = parseFloat($("#threads_messages").attr("member_id"));
	current_chat_member = member_id;
	var chatfield = $("#chat_field");
	$("#chat_field").focus();
	
	var textarea = document.getElementById("chat_field");
	var heightLimit = 450; /* Maximum height: 200px */
	
	textarea.oninput = function() {
	  textarea.style.height = ""; /* Reset the height*/
	  textarea.style.height = Math.min(textarea.scrollHeight, heightLimit) + "px";
	};
	
	
	
	$("#chat_field").keyup(function(e){
		
		var code = e.which;
		if(code == 13){e.preventDefault();}
		if(code == 13){
			var member_id = parseFloat($("#message_flow").attr("member_id"));
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
						 	
						  }
					  }, "json");
}

function getDirectMessages(getMethod){
	
	var memberId = parseFloat($("#message_flow").attr("member_id"));
	//console.log(memberId);
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
										
										var sideclass = ""; 
										if(user_id == p.user_id){sideclass = " self";}
										
										if(p['avatar_id'] > 0){imgString = "background: url(" + data.images[p['avatar_id']]['sizes']['square'] + ") no-repeat;";}
										if(p.time != last_time){output += "<p class='noted_time'>" + p.time + "</p>"; last_time = p.time;}
										output += "<div class='message" + sideclass + "' id='user_message_" + p['message_id'] + "' message_id='" + p['message_id'] + "'><div class='thumb' style='" + imgString + "' alt='" + p.user_name + "'></div><div class='tip'></div><div class='bubble'>" + p.message + "</div></div>";
										
									}
								}
								$("#message_flow").append(output);
								
								if(getMethod == 'init'){chat_update_frequency = chat_update_frequency_fast; check_new_messages();} 
								scrollThreadBottom(getMethod);
							} 
							
							//initCheckUserNewMessage();
						}
					  }, "json");
}

function closeDirectChatWindow(){
	current_chat_member = 0;
	chat_update_frequency = 20; 
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
	//if(dt != null){memberString = dt + "," + memberId; memberArray = memberString.split(','); var newArray = removeDuplicatesArray(memberArray); memberString = newArray.join(',');}
	
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
								$("#page_footer .footer_nav .pod.messages .bubble").addClass('active'); 
								document.title = "Tribulink (" + no_messages + ")"; 
							} else {
								$("#page_footer .footer_nav .pod.messages .bubble").removeClass('active');
								document.title = page_title; 
							}
						  }
					  }, "json");
}

function saveChatLocal(){
	console.log(threads);
	localStorage.setItem('chat', JSON.stringify(chat));
	localStorage.setItem('users', JSON.stringify(users));
	localStorage.setItem('threads', JSON.stringify(threads));
	
	return true;
}

function loadChatLocal(){
	chat = JSON.parse(localStorage.chat);
	users = JSON.parse(localStorage.users);
	threads = JSON.parse(localStorage.threads);
	
	return true;
}

function clearLocal(){
	localStorage.removeItem("chat");
	localStorage.removeItem("users");
	localStorage.removeItem("threads");
	
	return true;
}

