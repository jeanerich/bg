var no_panels = 0;
var current_panel = 0;
var battle_info = new Array();

$(document).ready(function(){
	battle_info = $("#board_1 .side_panel_wrapper.left .panel").size();
	
	
	//setTimeout(function(){initializePanels();}, 5000);
	panelIntro();
	
});


function panelIntro(){
	setTimeout(function(){$("#board_1 .welcome_text").eq(0).addClass('show');}, 1000);
	setTimeout(function(){$("#board_1 .welcome_text").eq(1).addClass('show');}, 1200);
	setTimeout(function(){$("#board_1 .welcome_text").eq(0).removeClass('show');}, 3500);
	setTimeout(function(){$("#board_1 .welcome_text").eq(1).removeClass('show');}, 3800);
	
	setTimeout(function(){initializePanels(); $("#board_1 .diamond").removeClass('blink3');}, 4000);
	setTimeout(function(){$("#board_1 #vote_diamond").slideDown(1000); $("#board_1 .diamond").toggleClass('open');}, 5000);
}

function initializePanels(){
	$("#board_1 .side_panel_wrapper.left .panel").eq(0).addClass('active');	
	$("#board_1 .side_panel_wrapper.right .panel").eq(0).addClass('active');	
}

function nextBattle(){
	next_panel = current_panel + 1;
	$("#board_1 .side_panel_wrapper.left .panel").eq(current_panel).addClass('out');
	$("#board_1 .side_panel_wrapper.right .panel").eq(current_panel).addClass('out');
	
	setTimeout(function(){$("#board_1 .side_panel_wrapper.left .panel").eq(next_panel).addClass('active');}, (Math.random() * 200));
	setTimeout(function(){$("#board_1 .side_panel_wrapper.right .panel").eq(next_panel).addClass('active');}, (Math.random() * 200));
	
	
	current_panel++;
	
}







