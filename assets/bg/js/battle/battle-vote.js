var no_panels = 0;
var current_panel = 0;
var battle_info = new Array();
var cur_vote_counter = 0;
var img_loaded = 0;
var cur_images = new Array();

$(document).ready(function(){
	no_panels = $("#board_1 .side_panel_wrapper.left .panel").size();
	
	battle_info = $.parseJSON($("#battle_info").val());
	
	//setTimeout(function(){initializePanels();}, 5000);
	panelIntro();
	
	
	
});


function panelIntro(){
	displayPanelImages(0);
	setTimeout(function(){initializePanels(); $("#board_1 .diamond").removeClass('blink3');}, 1000);
	setTimeout(function(){$("#board_1 #vote_diamond").slideDown(1000); $("#board_1 .diamond").toggleClass('open');}, 2000);
	
	
}

function initializePanels(){
	var imageCount = cur_images.length;
	var imagesLoaded = 0;
	var images_array = new Array();
	
	for(var i = 0; i < imageCount; i++){ 
		images_array[i] = new Image();
		images_array[i].onload = function(){
			imagesLoaded++;
			
			if(imagesLoaded == imageCount){
				$("#board_1 .side_panel_wrapper.left .panel").eq(0).addClass('active');	
				$("#board_1 .side_panel_wrapper.right .panel").eq(0).addClass('active');	
			}
		}
	}
	
	for(var i = 0; i < imageCount; i++){ 
		images_array[i].src = cur_images[i];
	}
	
	
	
}

function voteImage(sideId){
	$("#board_1 .diamond").removeClass('open');
	$("#board_1 #vote_diamond .vote_sequence").fadeOut(500);
	$("#board_1 #vote_diamond .next_sequence").fadeIn(500);
	
	if(user_id > 0){
	var pre_id = parseFloat(battle_info[current_panel]['pre_id']);
	var token = battle_info[current_panel]['token'];
	if(sideId == 0){var winning_image = battle_info[current_panel]['image1'];} else {var winning_image = battle_info[current_panel]['image1'];}
	
	console.log(sideId + " : " + pre_id + " : " + token + " : " + winning_image);
	// ['pre_id']) && isset($_POST['token']) && isset($_POST['image_id']
	$.post(site_url + "app/saveBattleVote/", {
					'pre_id' : pre_id,
					'token' : token,
					'image_id' : winning_image
					},
				   function(data){
					   if(data.success){
						  	$("#board_1 .side_panel_wrapper.left .panel:eq(" + current_panel + ") .overlay_bg").fadeIn(500);
							$("#board_1 .side_panel_wrapper.right .panel:eq(" + current_panel + ") .overlay_bg").fadeIn(500);
						  }
					  }, "json");
	
	//$("#board_1 .side_panel_wrapper.left .panel.active").addClass('visible');
	} else {
		$("#board_1 .side_panel_wrapper.left .panel:eq(" + current_panel + ") .overlay_bg").fadeIn(500);
		$("#board_1 .side_panel_wrapper.right .panel:eq(" + current_panel + ") .overlay_bg").fadeIn(500);
	}
}


function nextBattle(){
	next_panel = current_panel + 1; 
	displayPanelImages(next_panel);
	$("#board_1 .side_panel_wrapper.left .panel").eq(current_panel).addClass('out');
	$("#board_1 .side_panel_wrapper.right .panel").eq(current_panel).addClass('out');
	
	var imageCount = cur_images.length;
	var imagesLoaded = 0;
	var images_array = new Array();
	
	for(var i = 0; i < imageCount; i++){ 
		images_array[i] = new Image();
		images_array[i].onload = function(){
			imagesLoaded++;
			
			if(imagesLoaded == imageCount){
				showNextBattle();
			}
		}
	}
	
	for(var i = 0; i < imageCount; i++){ 
		images_array[i].src = cur_images[i];
	}
	
	
	
}

function showNextBattle(){
	$("#board_1 #vote_diamond .vote_sequence").fadeIn(500);
	$("#board_1 #vote_diamond .next_sequence").fadeOut(500);
	setTimeout(function(){$("#board_1 .side_panel_wrapper.left .panel").eq(next_panel).addClass('active');}, (Math.random() * 200));
	setTimeout(function(){$("#board_1 .side_panel_wrapper.right .panel").eq(next_panel).addClass('active');}, (Math.random() * 200));
	$("#board_1 .diamond").addClass('open');
	
	$("#board_1 #vote_diamond .vote_sequence").fadeIn(500);
	
	
	current_panel++;
}

function displayPanelImages(countId){ console.log(countId);
	cur_images = new Array();
	var left_image = $("#board_1 .side_panel_wrapper.left .panel:eq(" + countId + ") .imagewrapper").attr('bg');
	var right_image = $("#board_1 .side_panel_wrapper.right .panel:eq(" + countId + ") .imagewrapper").attr('bg');
	cur_images[0] = left_image;
	cur_images[1] = right_image;
	
	var left_style = "background: url(" + left_image + ") no-repeat;";
	var right_style = "background: url(" + right_image + ") no-repeat;";
	
	$("#board_1 .side_panel_wrapper.left .panel:eq(" + countId + ")  .imagewrapper").attr({"style" : left_style});
	$("#board_1 .side_panel_wrapper.right .panel:eq(" + countId + ")  .imagewrapper").attr({"style" : right_style});
}


