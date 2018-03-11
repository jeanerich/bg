$(document).ready(function(){
	showpage();
	loadMessages();
	initComments();
	initializeForm("#comment_form", sendMessage);
	//
});

function showpage(){
	setTimeout(function(){
		$("#gallery_wrapper #image_wrapper .img_background").fadeIn(1000);
		}, 500);
		
		
}

function loadMessages(){
	var thread_id = parseFloat($("#thread_list").attr('thread_id'));
	var thread_token = $("#thread_list").attr('thread_token');
	var target_url = site_url + "gallery/getThread/?id=" + thread_id + "&token=" + thread_token;
	
	$("#thread_list").load(target_url, function(){initiateMessageItems(); });
}


function initComments(){
	
	var commentfield = $("#comment_message");
	$("#comment_message").focus();
	
	var textarea = document.getElementById("comment_message");
	var heightLimit = 450; /* Maximum height: 200px */
	
	textarea.oninput = function() {
	  textarea.style.height = ""; /* Reset the height*/
	  textarea.style.height = Math.min(textarea.scrollHeight, heightLimit) + "px";
	};
	
	
	
	$("#comment_message").keyup(function(e){
		
		var code = e.which;
		if(code == 13){e.preventDefault();}
		if(code == 13){
			var member_id = parseFloat($("#message_flow").attr("member_id"));
			var message_string = $("#comment_message").val();
			if(message_string.length > 1){
				sendMessage();
				
				$("#comment_message").val("");
				$("#comment_message").focus();
			}
		}	
	});
	
	
}

function sendMessage(){
	var image_id = parseFloat($("#thread_list").attr('image_id'));
	var image_token = $("#thread_list").attr('image_token');
	var thread_id = parseFloat($("#thread_list").attr('thread_id'));
	var thread_token = $("#thread_list").attr('thread_token');
	var message = $("#comment_message").val();
	
	
	//isset($_POST['image_id']) && isset($_POST['image_token']) && isset($_POST['thread_id']) && isset($_POST['thread_token']) && isset($_POST['message'])){
	$.post(site_url + "app/postGalleryComment/", {
					'image_id' : image_id,
					'image_token' : image_token,
					'thread_id' : thread_id,
					'thread_token' : thread_token,
					'message' : message
					},
				   function(data){
					   if(data.success){
						   if(thread_id < 1){
							   $("#thread_list").attr({'thread_id' : data.thread_id});
							   $("#thread_list").attr({'thread_token' : data.thread_token});
							}
							$("#comment_message").val("");
						   setTimeout(function(){loadMessages();}, 50);
						  
						  }
					  }, "json");
					  
}

function initiateMessageItems(){
	$(".message_item .message_options").mouseleave(function(){
		$(".message_item .message_options").removeClass('open');
	});
	
	scrollCommentThreadBottom();
}

function deleteMessage(message_id){
	var thread_id = parseFloat($("#thread_list").attr('thread_id'));
	$.post(site_url + "gallery/deleteMessage/", {
					'thread_id' : thread_id,
					'message_id' : message_id
					},
				   function(data){
					   if(data.success){$(".message_" + message_id).slideUp(500, function(){$(".message_" + message_id).remove();});}
					  }, "json");
	
}

function scrollCommentThreadBottom(transit_speed = 250){
	var content_height = $("#image_content .gallery_image_content").height();
	var content_parent = $("#image_content .scroll_content").height();
	
	if(content_height > content_parent){
		var content_scroll_bottom = content_height - content_parent + 40;
			$("#image_content .gallery_image_content").parent().animate({scrollTop : content_scroll_bottom + "px"}, transit_speed);
		
	}
	
	
}
