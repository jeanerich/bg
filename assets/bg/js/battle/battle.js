$(document). ready(function(){
	highlightAddMedia();
});


function fs_join_team(team_id){
	var battle_id = parseFloat($("#battle_id").val());
	
	var targeturl = "battles/teamjoin/" + battle_id + "/" + team_id + "/";
	
	fs_modal(targeturl);
}

function joinBattle(battle_id, team_id){
	joinBattle
	$.post(site_url + "app/joinBattle/", {
					'battle_id' : battle_id,
					'team_id' : team_id
					},
				   function(data){
					   if(data.success){
						   var output = "<p>" + data.message + "</p>";
						  $(".form_message").html(output);
						  $(".members").slideDown(250);
						  $(".submission_deck").removeClass('active');
						  $(".submission_deck").removeClass('select_team');
						  loadTeams();
						  setTimeout(function(){close_fs_modal(); }, 3000);
						  }
					  }, "json");
}

function fs_leave_team(team_id){
	var battle_id = parseFloat($("#battle_id").val());
	
	var targeturl = "battles/teamquit/" + battle_id + "/" + team_id + "/";
	
	fs_modal(targeturl);
}


function quitBattle(battle_id, team_id){
	joinBattle
	$.post(site_url + "app/removeTeamMember/", {
					'battle_id' : battle_id,
					'team_id' : team_id
					},
				   function(data){
					   if(data.success){
						   var output = "<p>" + data.message + "</p>";
						  $(".form_message").html(output);
						  $(".members").slideDown(250);
						  $(".submission_deck").removeClass('active');
						  $(".submission_deck").addClass('select_team');
						  loadTeams();
						 
						  setTimeout(function(){close_fs_modal(); }, 3000);
						  }
					  }, "json");
}

function loadTeams(){
	var teamsurl = $("#teams_list").attr("ajax-data");
	$("#teams_list").load(teamsurl);
}

function highlightAddMedia(){
	var no_tiles = $("#gallery_grid .scroll_content .tile").size();
	console.log(no_tiles);
	if(no_tiles < 1){$(".sub_deck.submit_entry .submission_nav").addClass('softblink');} else {$(".sub_deck.submit_entry .submission_nav").removeClass('softblink');}
}

