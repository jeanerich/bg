$(document).ready(function(){
	loadBillBoard();
});

function loadBillBoard(){
	var $div = $('#bg_image'),
	  bg = $div.css('background-image');
	  if (bg) {
		var src = bg.replace(/(^url\()|(\)$|[\"\'])/g, ''),
		  $img = $('<img>').attr('src', src).on('load', function() {
			// do something, maybe:
			$div.fadeIn(1000);
		  });
	  }
	
}
