$(document).ready(function(){
	initializeForm("#edit_battle_form", saveBattle);
	initeditbattle();
	getBattleTeams();
	loadJudges();
	loadMentors();
});


function initeditbattle(){
	var no_radiobar = $(".radiobar").size();
	if(no_radiobar > 0){
		for(var i = 0; i < no_radiobar; i++){
			$(".radiobar:eq(" + i + ") .radiobutton").click(function(){
				var thisButton = $(this);
				var parentButton = thisButton.parent();
				
				if(!$(parentButton).hasClass('multi')){
					$(".radiobutton", parentButton).removeClass('active');
				}
				thisButton.toggleClass('active');
				
				if(thisButton.attr('battle_type') == 'team'){
					$("#team_panel").slideDown(500);	
				} else if(thisButton.attr('battle_type') == 'individual') {
					$("#team_panel").slideUp(500);	
				} else if(thisButton.attr('battle_type') == 'public'){
					
				} else if(thisButton.attr('battle_type') == 'jury'){
					if($(".select_jury.active").size() > 0){
						$("#jury_panel").slideDown(250);
					} else {
						$("#jury_panel").slideUp(250);	
					}
				}
				
				
			});
		}
	}
}

function saveBattle(){
	var battle_id = parseFloat($("#battle_id").val());
	var battle_title = $("#battle_title").val();
	var short_description = $("#battle_short_description").val();
	
	var today_date = new Date();
	var start_date = $("#start_date").val(); var sdate = new Date(start_date);
	var vote_date = $("#vote_date").val(); var vdate = new Date(vote_date);
	var end_date = $("#end_date").val(); var edate = new Date(end_date);
	console.log(today_date + " : " + start_date);
	var date_errors = 0;
	
	if(battle_id < 1 && sdate < today_date){date_errors++; $("#start_date").parent().addClass('error');} else {$("#start_date").parent().removeClass('error');}
	if(vdate < sdate){date_errors++; $("#vote_date").parent().addClass('error');} else {$("#vote_date").parent().removeClass('error');}
	if(edate < vdate){date_errors++; $("#end_date").parent().addClass('error');} else {$("#end_date").parent().removeClass('error');}
	
	var vote_option = 0;
	if($("#vote_option .radiobutton.active").size() > 0){
		vote_option = parseFloat($("#vote_option .radiobutton.active").attr('vote_option'));
	}
	
	var battle_type = "individual";
	if($("#battle_type .radiobutton.active").size() > 0){
		battle_type = $("#battle_type .radiobutton.active").attr('battle_type');
	}
	
	var categories = '';
	if($(".form .formfield .radiobar .radioselect.active").size() > 0){
		var categories_array = new Array();
		for(var i = 0; i < $(".form .formfield .radiobar .radioselect.active").size(); i++){
			categories_array[i] = $(".form .formfield .radiobar .radioselect.active").eq(i).attr('cat');
		}	
		categories = categories_array.join(',');
	}
	
	var territory = ''; if($("#battle_country").size() > 0){territory = $("#battle_country").val();}
	var battle_rules = ''; if($("#battle_rules").size() > 0){battle_rules = $("#battle_rules").val();}
	var long_description = ''; if($("#battle_long_description").size() > 0){long_description = $("#battle_long_description").val();}
	
	if(date_errors < 1){
		$.post(site_url + "app/savebattle/", {
					'battle_id' : battle_id,
					'battle_title' : battle_title,
					'battle_type' : battle_type,
					'start_date' : start_date,
					'vote_date' : vote_date,
					'end_date' : end_date,
					'vote_option' : vote_option,
					'categories' : categories,
					'territory' : territory,
					'battle_rules' : battle_rules,
					'short_description' : short_description,
					'long_description' : long_description
					},
				   function(data){
					   if(data.success){
						   if(battle_id < 1){
						  	window.location.replace(data.return_link); 
						   }
						  }
					  }, "json");
	}
	
	
	
	
}

function toggleCategory(category_id){
	if($("#category_" + category_id + ".active").size() < 1){
		if($("#battle_categories .radioselect.active").size() < 3){
			$("#category_" + category_id).addClass('active');
		}
	} else {
		$("#category_" + category_id).removeClass('active');
	}
	
}

function toggleOption(optionId){
	$("#" + optionId).toggleClass('active');
}

function getBattleTeams(){
	var battle_id = parseFloat($("#battle_id").val());
	
	$("#battle_List_editor").load(site_url + "app/getBattleTeams/?battle_id=" + battle_id);
}

function saveTeam(team_id){
	var battle_id = parseFloat($("#battle_id").val());
	var team_name = $("#team_" + team_id + " .team_name").val();
	var short_description = $("#team_" + team_id + " .team_description").val();
	var token = $("#team_" + team_id + " .team_token").val();
	//console.log(battle_id + " : " +  team_id + " : " + battle_title + " : " + short_description + " : " + token);
	$.post(site_url + "app/saveTeam/", {
					'team_id' : team_id,
					'token' : token,
					'battle_id' : battle_id,
					'team_name' : team_name,
					'team_description' : short_description
					},
				   function(data){
					   if(data.success){
						  
						  }
					   
					  }, "json");
}

function deleteTeam(team_id){
	
	var battle_id = parseFloat($("#battle_id").val());
	var token = $("#team_" + team_id + " .team_token").val();
	
	$.post(site_url + "app/deleteTeam/", {
					'team_id' : team_id,
					'token' : token,
					'battle_id' : battle_id
					},
				   function(data){
					   if(data.success){
						  $("#team_" + team_id).slideUp(500, function(){
							  $("#team_" + team_id).remove();
							 });
						  }
					   
					  }, "json");
}

function listUsers(targetElement, listUsers, listImages){
	var output = "";
	for(var i = 0; i < listUsers.length; i++){
		var u = listUsers[i];
		var imgstring = "";
		var imgurl = "";
		if(u['avatar_id'] > 0){imgstring = "style='background: url(" + listImages[u['avatar_id']]['sizes']['square'] + ") no-repeat; '"; imgurl = listImages[u['avatar_id']]['sizes']['square'];}
		output += "<li onclick='selectJudge(" + u['id'] + ");' id='select_user_" + u['id'] + "' uname='" + u['name'] + "' imgsrc='" + imgurl + "'><div class='text_wrapper'><div class='thumb'" + imgstring + "></div>" + u['name'] + "</div></li>";
	}
	output = "<ul>" + output + "</ul";
	$(targetElement).html(output);
	$(targetElement).slideDown(250);
}

function selectJudge(memberId){
	var member_name = $("#select_user_" + memberId).attr('uname');
	var member_thumb = $("#select_user_" + memberId).attr('imgsrc');
	$("#select_member_preview .text_wrapper h2").text(member_name);
	if(member_thumb.length > 0){
		$("#select_member_preview .text_wrapper .thumb").attr({"style" : "background: url(" + member_thumb + ") no-repeat;"});
		
	} else {
		$("#select_member_preview .text_wrapper .thumb").attr({"style" : ""});
		
	}
	$(".form_list").slideUp(250);
	$("#mentor_name").val("");
	$("#select_member_preview").attr({"selected_member_id" : memberId});
	$("#select_member_preview").slideDown(250);
}

function loadJudges(){
	var battle_id = parseFloat($("#battle_id").val());
	var targeturl = site_url + "battles/getJudges/" + battle_id;
	$("#jury_list").load(targeturl);
}

function loadMentors(){
	var battle_id = parseFloat($("#battle_id").val());
	var targeturl = site_url + "battles/getMentors/" + battle_id;
	$("#mentor_list").load(targeturl);
}

function saveJudge(){
	
	var memberId = parseFloat($("#select_member_preview").attr("selected_member_id"));
	
	var battle_id = parseFloat($("#battle_id").val());
	if(battle_id > 0){
	$.post(site_url + "battles/addBattleLeader/", {
					'battle_id' : battle_id,
					'member_id' : memberId,
					'title' : 'judge'
					},
				   function(data){
					   if(data.success){loadJudges(); close_fs_modal();}
					  }, "json");
	}
}

function saveMentor(){
	
	var memberId = parseFloat($("#select_member_preview").attr("selected_member_id"));
	var teamId = 0;
	if($("#mentor_team_id").size() > 0){teamId = parseFloat($("#mentor_team_id").val());}
	
	var battle_id = parseFloat($("#battle_id").val());
	if(battle_id > 0){
	$.post(site_url + "battles/addBattleLeader/", {
					'battle_id' : battle_id,
					'member_id' : memberId,
					'team_id' : teamId,
					'title' : 'mentor'
					},
				   function(data){
					   if(data.success){loadMentors(); close_fs_modal();}
					  }, "json");
	}
}

function removeLeader(leader_id){
	var battle_id = parseFloat($("#battle_id").val());
	
	$.post(site_url + "battles/removeBattleLeader/", {
					'battle_id' : battle_id,
					'leader_id' : leader_id
					},
				   function(data){
					   if(data.success){
						   $("#leader_" + leader_id).slideUp(250, function(){$("#leader_" + leader_id).remove();});
						  }
					  }, "json");
	
	
}
