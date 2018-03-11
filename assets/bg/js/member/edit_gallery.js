

function deleteImage(imageId){
	$("#gallery_tile_" + imageId).addClass('opaque');
	
	setTimeout(function(){$("#gallery_tile_" + imageId).remove(); saveGalleryOrder();}, 500);
}

function makeGridSortable(){
	$( "#gallery_grid" ).sortable();	
	$( "#gallery_grid" ).on( "sortchange", function( event, ui ) {setTimeout(function(){saveGalleryOrder();}, 1000); } );
}

function saveGalleryOrder(){
	var imageIds = new Array();
	var no_tiles = $("#gallery_grid .tile").size();
	
	if(no_tiles > 0){
		for(var i = 0; i < no_tiles; i++){
			imageIds[i] = parseFloat($("#gallery_grid .tile").eq(i).attr('image_id'));	
		}
		
		var imageIdsString = imageIds.join(',');
		
		$.post(site_url + "app/reorderUserGallery/", {
					'image_ids' : imageIdsString
					},
				   function(data){
					   
					  }, "json");
		
		//image_ids
		
		
	}
}

function editImageInfo(image_counter){
	$("#gallery_tile_" + image_counter + " .tile_form").slideDown(250);
}

function closeImageForm(image_counter){
	$("#gallery_tile_" + image_counter + " .tile_form").slideUp(250);
}

function saveImageTitle(image_counter){
	var image_title = $("#gallery_tile_" + image_counter + " .tile_form .image_title").val();
	var image_id = parseFloat($("#gallery_tile_" + image_counter).attr('image_id'));
	
	
	$.post(site_url + "app/saveUserImageTitle/", {
					'title' : image_title,
					'image_id' : image_id
					},
				   function(data){
					   if(data.success){
						  $("#gallery_tile_" + image_counter + " h3").text(image_title);
						  closeImageForm(image_counter);
						  }
					  }, "json");
	//saveUserImageTitle
}