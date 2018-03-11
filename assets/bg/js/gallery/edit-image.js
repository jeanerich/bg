

function saveImageTitle(){
	var image_title = $("#new_image_title").val();
	var image_id = parseFloat($("#thread_list").attr('image_id'));
	
	$.post(site_url + "app/saveUserImageTitle/", {
					'title' : image_title,
					'image_id' : image_id
					},
				   function(data){
					   if(data.success){
						  $('#image_title').text(image_title);
						  $('.gallery_image_content h1').slideDown(250);
						  $('#title_form_wrapper').slideUp(250);
						  }
					  }, "json");
}


function saveImageDescription(){
	var image_description = $("#image_description_field").val();
	var image_id = parseFloat($("#thread_list").attr('image_id'));
	
	$.post(site_url + "app/saveUserImageDescription/", {
					'description' : image_description,
					'image_id' : image_id
					},
				   function(data){
					   if(data.success){
						  $('#image_description').html(data.description);
						  $('#image_description_form_wrapper').slideUp(250); 
						  $('#image_description').slideDown(250); 
						  }
					  }, "json");
}


