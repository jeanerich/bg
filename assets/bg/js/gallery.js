var tile_width = 300;
var padding_size = 10;
var member_id = 0;

$(document).ready(function(){
	member_id = parseFloat($(".gallery_grid").attr('member_id'));
	loadGrid(); 
	
	$(window).resize(function(){sizeGrid();});
});


function loadGrid(){
	var targetUrl = site_url + "member/includeUserGallery/" + member_id;
	$(".gallery_grid").load(targetUrl, function(){sizeGrid(); 
		if($(".gallery_grid .top_nav").size() > 0){
			makeGridSortable();
		}
	});
	
	
}

function revealGrid(){
	var no_tiles = $(".gallery_grid .tile").size()
	if(no_tiles > 0){
		for(var i = 0; i < no_tiles; i++){
			
			var delay_time = i * 100;
			
			$(".gallery_grid .tile").eq(i).delay(i * 100).removeClass('opaque');
		}
	
	}
}

function sizeGrid(){
	if($(".gallery_grid .tile").size() > 0){
		var wWidth = $(".gallery_grid").width();
		var no_tiles_per_row = Math.floor(wWidth / 250);
		var tile_width = wWidth / no_tiles_per_row - 10;
		
		$(".gallery_grid .tile").css({"width" : tile_width + 'px', "height" : tile_width + 'px'});
		revealGrid();
	}
}

function editImageInfo(imageId){
	var targetTile = $(".gallery_tile" + imageId).eq(0);
	
	
	
}

