$(document).ready(function(){
	loadMessages();
	initComments();
	
	
});

function loadMessages(){
	var no_threads = $(".thread_list").size();
	
	for(var i = 0; i < no_threads; i++){
		var image_id = parseFloat($(".thread_list").eq(i).attr('image_id'));
		var thread_id = parseFloat($(".thread_list").eq(i).attr('thread_id'));
		var thread_token = $(".thread_list").eq(i).attr('thread_token'); 
		var target_url = site_url + "gallery/getThread/?id=" + thread_id + "&token=" + thread_token;
		
		$(".thread_list").eq(i).load(target_url, function(){
			scrollCommentThreadBottom(image_id, 0);
		});
	}
	
}

function initComments(){
	$(".comment_message").focus(function(){
		var image_id = $(this).attr('image_id');
		var textarea = document.getElementById("comment_message_" + image_id);
		var heightLimit = 450; /* Maximum height: 200px */
		
		textarea.oninput = function() {
		  textarea.style.height = ""; /* Reset the height*/
		  textarea.style.height = Math.min(textarea.scrollHeight, heightLimit) + "px";
		};
		
		
	});
	
	$(".comment_message").keyup(function(e){
		var code = e.which;
		
		if(code == 13){e.preventDefault();}
		if(code == 13){
			var image_id = parseFloat($(this).attr('image_id'));
			sendMessage(image_id);
		}
	});
}


function sendMessage(image_id){
	
	var thread_id = parseFloat($("#comment_message_" + image_id).attr('thread_id'));
	var image_token = $("#comment_message_" + image_id).attr('image_token');
	var thread_token = $("#comment_message_" + image_id).attr('thread_token');
	var message = $("#comment_message_" + image_id).val();
	
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
							   thread_id = data.thread_id;
							   thread_token = data.thread_token;
							   $("#comment_message_" + image_id).attr({'thread_id' : data.thread_id});
							   $("#comment_message_" + image_id).attr({'thread_token' : data.thread_token});
							}
							$("#comment_message_" + image_id).val("");
						   setTimeout(function(){
							  	var target_url = site_url + "gallery/getThread/?id=" + thread_id + "&token=" + thread_token; 
								console.log(target_url);
								$("#message_thread_" + image_id).load(target_url, function(){ scrollCommentThreadBottom(image_id, 250);  });
							  }, 50);
						  
						  }
					  }, "json");
					  
}

function deleteMessage(message_id){
	var thread_id = parseFloat($(".message_item.message_" + message_id).parent().attr('thread_id'));
	console.log(thread_id);
	$.post(site_url + "gallery/deleteMessage/", {
					'thread_id' : thread_id,
					'message_id' : message_id
					},
				   function(data){
					   if(data.success){$(".message_" + message_id).slideUp(500, function(){$(".message_" + message_id).remove();}); }
					  }, "json");
					  
					  return false;
	
}

function scrollCommentThreadBottom(image_id, transit_speed = 250){
	var content_height = $("#image_comment_" + image_id + " .gallery_image_content").height();
	var content_parent = $("#image_comment_" + image_id + " .scroll_content").height();
	
	if(content_height > content_parent){
		var content_scroll_bottom = content_height - content_parent + 40; 
			$("#image_comment_" + image_id + " .scroll_content").animate({scrollTop : content_scroll_bottom + "px"}, transit_speed);
		
	}
	
	
}