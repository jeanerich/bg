var user_talents = new Array();
var select_bars = new Array();

$(document).ready(function(){
	initializeForm("#description_form", saveProfileBio);
	initializeForm("#skill_form", saveNewSkill);
	
	initTechSearch();
	userHelpFillProfile();
});

function toggleDescription(){
	$("#description_deck .deck_content").slideToggle(250);
	$("#description_deck .deck_form").slideToggle(250);
	maskDecks(1);
}

function toggleSkills(){
	$("#main_profile_wrapper #skills_deck.deck .deck_form").slideToggle(250);
	maskDecks(2);
}

function saveProfileBio(){
	var description = $("#description_field").val();
	
	$.post(site_url + "app/saveProfileBio/", {
					'description' : description
					},
				   function(data){
					   if(data.success){
						   
						   $("#description_deck .deck_content").html(data.description);
						   	$("#description_deck .deck_content").slideToggle(250);
							$("#description_deck .deck_form").slideToggle(250);
							clearMaskDecks();
						 }
					  }, "json");
}

function saveNewSkill(){
	
	//if //user_talents
	
	
	var new_skill_type = $("#select_skill").val();
	var new_skill_string = $("#select_skill option[value='" + new_skill_type + "']").text();
	var new_skill_value = parseFloat($("#skill_value").attr('value'));
	
	$.post(site_url + "app/addUserProfileSkill/", {
					'skill_id' : new_skill_type,
					'skill_value' : new_skill_value
					},
				   function(data){
					   if(data.success){
						  var element_id = "#skill_chart_" + new_skill_type.replace(' ','_'); 
						  if($(element_id).size() > 0){
							  $("#skills_deck .charts").load(site_url + "app/reloadUserSkills/", function(){drawCharts();});
							} else {
								var next_skill_id = $(".charts .chart").size() + 1;
								var output = "<div class='chart' id=\"skill_chart_" + new_skill_type.replace(" ", "_") + "\" val='100," + (new_skill_value * 10) + "'>";
								output += "<canvas id='chart_" + next_skill_id+ "' width='150' height='150'></canvas><div class='percent'><strong>" + new_skill_value + "<span class='small'>/10</span></strong></div><p class='chart_title'>" + new_skill_string + "</p><div class='edit_chart' onclick='editChart(\"" + new_skill_type + "\");'></div><div class='delete_chart' onclick='deleteChart(\"" + new_skill_type + "\"); return false;'></div></div>";
								
								$("#skills_deck .charts").load(site_url + "app/reloadUserSkills/", function(){drawCharts();});
								
							}
						  
							
							
							$("#main_profile_wrapper #skills_deck.deck .deck_form").slideUp(250);
							$("#select_skill").val("");
							$("#skill_value").val("");
							closeSkillsForm();
						  
						  
						  }
					  }, "json");
	
	
	
	
	
}

function closeSkillsForm(){
	$("#main_profile_wrapper #skills_deck.deck .deck_form #skill_value .box").removeClass("on");
	$("#main_profile_wrapper #skills_deck.deck .deck_form #skill_value").attr("value", "0");
	clearMaskDecks();
}

function deleteChart(new_skill_type){
	var element_id = "#skill_chart_" + new_skill_type.replace(' ','_'); 
	$.post(site_url + "app/removeProfileSkill/", {
					'skill_id' : new_skill_type
					},
				   function(data){
					   if(data.success){
						 $(element_id).hide(250);  
						 }
					  }, "json");
	
}

function initTechSearch(){
	$("#technology_name").keyup(function(){
		var searchstring = $("#technology_name").val();
		var saved_name = $("#technology_name").attr('tech_name');
		
		if($("#technology_name").val() != saved_name){
			$("#technology_id").val(0);
		}
		
		
		searchTechSkill(searchstring);
		
	});
	
	
}

function searchTechSkill(searchstring){
	console.log("length: " + searchstring.length);
	if(searchstring.length > 0){
	
	this.ajax_search_tech_skills = $.post(site_url + "app/searchTechSkills/", {
					's' : searchstring
					},
				   function(data){
					   if(data.success){
						  	var output = "";
						  	
						  	if(data.items.length > 0){
							  	output += "<ul>";
								for(var i = 0; i < data.items.length; i++){
										var single_item = data.items[i];
										output += "     <li id='list_item_" + single_item['id'] + "' list_id='" + single_item['id'] + "' list_name='" + single_item['name'] + "'><a href='#' onclick='selectTechFromList(" + single_item['id'] + "); return false;' >" + single_item['name'] + "</a></li>";
										 
								}
							  output += "</ul>";
							  
							  $("#technology_form_list").slideDown(250);
							} else {
								output = "";	
								$("#technology_form_list").slideUp(250);
							}
							
							$("#technology_form_list").html(output);
						  
						  } else {
							 output = "";	
							$("#technology_form_list").slideUp(250); 
						}
					  }, "json");
	} else {
		output = "";	
		$("#technology_form_list").slideUp(250);
	}
}

function selectTechFromList(tech_id){
	var tech_name = $("#technology_form_list ul li#list_item_" + tech_id).attr("list_name");
	
	$("#technology_name").val(tech_name);
	$("#technology_name").attr({"tech_name" : tech_name});
	$("#technology_id").val(tech_id);
	
	$("#technology_form_list").slideUp(250);
}

function selectBoxValue(target, fieldValue){
	var target_field = $(target).parent().parent();
	
	for(var i = 0; i < 10; i++){
		if(i < (fieldValue + 1)){
			$(".box", target_field).eq(i).addClass('on');
		
		} else {
			$(".box", target_field).eq(i).removeClass('on');
		}
		
		target_field.attr({'value': (fieldValue + 1)});
	}
}

function editChart(sSkill){
	$("#select_skill").val(sSkill);
	$("#main_profile_wrapper #skills_deck.deck .deck_form").slideDown(250);
}

function addWorkTimeline(itemId){
	fs_modal("app/modifyUserTimeLine/?type=work&tid=" + itemId);
	
}

function addEducationTimeline(itemId){
	fs_modal("app/modifyUserTimeLine/?type=education&tid=" + itemId);
}



function saveEducationTimeline(){
	fs_modal("app/modifyUserTimeLine");
}

function addTechSkill(){
	$("#tech_pos").val(0);
	$("#main_profile_wrapper .deck .deck_form.tech").slideToggle(500);
	$("#technology_name").focus();
}

function saveTechExpertise(){
	var tech_name = $("#technology_name").val();
	var tech_pos = parseFloat($("#technology_pos").val());
	var tech_id = parseFloat($("#technology_id").val());
	
	var tech_level = $("#technology_form .box.on").size();
	
	$.post(site_url + "app/addTechSkills/", {
					'tech_name' : tech_name,
					'tech_pos' : tech_pos,
					'tech_id' : tech_id,
					'tech_level' : tech_level
					},
				   function(data){
					   if(data.success){
						   var target_url = site_url + "app/getTechSkillsAJAX/";
						   $("#tech_skills").load(target_url, function(){initializeBars(0);});
						   $("#main_profile_wrapper .deck .deck_form.tech").slideUp(500);
						   $("#technology_name").val("");
						  }
					  }, "json");
}

function togglePersonal(){
	$("#personal_deck .deck_content").slideToggle(250);
	$("#personal_deck .deck_form").slideToggle(250);
	maskDecks(0);
}

function editTechSkills(tech_id, tech_name, skill_id, tech_level){
	
	$("#technology_pos").val(tech_id);
	$("#technology_name").val(tech_name);
	$("#technology_id").val(skill_id);
	
	$("#technology_form .box").removeClass('on');
	
	for(var i = 0; i < tech_level; i++){
		$("#technology_form .box").eq(i).addClass('on');
	}
	$("#main_profile_wrapper .deck .deck_form.tech").slideDown(500);
	
}

function deleteTechSkills(tech_id){
	$.post(site_url + "app/deleteTechSkills/", {
					'tech_id' : tech_id
					},
				   function(data){
					   if(data.success){
						   $("#tech_skill_" + tech_id).hide(250);
						  }
					  }, "json");
}


function savePersonalInfo(){
	var first_name = $("#form_user_first_name").val();
	var last_name = $("#form_user_last_name").val();
	var title = $("#form_user_title").val();
	var birthday = $("#form_user_birthday").val();
	var nationality = $("#form_user_nationality").val();
	var languages = $("#form_user_languages").val();
	
	$.post(site_url + "app/savePersonalInfo/", {
					'first_name' : first_name,
					'last_name' : last_name,
					'title' : title,
					'birthday' : birthday,
					'nationality' : nationality,
					'languages' : languages
					},
				   function(data){
					   if(data.success){
						  $(".title_card h1").text(first_name + " " + last_name);
						  $(".title_card h2").text(title);
						  
						  $(".personal_user_name span").text(first_name + " " + last_name);
						  $("#personal_user_title span").text(title);
						   $("#personal_user_languages span").text(languages);
						   $("#personal_user_birthday span").text(data.birthday_string);
						   
						   clearMaskDecks();
						   
						  
						  $("#personal_deck .deck_content").slideToggle(250);
	$("#personal_deck .deck_form").slideToggle(250);
						  }
					  }, "json");
	

}

function editContactForm(){
	$("#contact_deck .deck_content").slideToggle(250);
	$("#contact_deck .deck_form").slideToggle(250);
	maskDecks(6);
}

function saveContactForm(){
	var contact_mobile = $("#contact_user_phone_mobile").val();
	var contact_work_phone = $("#contact_user_phone_work").val();
	var contact_user_skype = $("#contact_user_skype").val();
	var contact_fax = $("#contact_user_fax").val();
	var contact_address = $("#contact_user_address").val();
	
	$.post(site_url + "app/saveContactForm/", {
					'contact_mobile' : contact_mobile,
					'contact_work_phone' : contact_work_phone,
					'contact_user_skype' : contact_user_skype,
					'contact_fax' : contact_fax,
					'contact_address' : contact_address
					},
				   function(data){
					   
					    $("#contact_mobile span").text(contact_mobile);
					    $("#contact_work_phone span").text(contact_work_phone);
						$("#contact_skype span").text(contact_user_skype);
						$("#contact_fax span").text(contact_fax);
						$("#contact_address span").text(contact_address);
					   
					   $("#contact_deck .deck_content").slideToggle(250);
						$("#contact_deck .deck_form").slideToggle(250);
						clearMaskDecks();
					  }, "json");
	
	
}

function addLink(){
	$("#links_deck .deck_content").slideToggle(250);
	$("#links_deck .deck_form").slideToggle(250);
	maskDecks(7);
}

function processLink(){
	var link_string = $("#new_user_link").val();
	
	console.log(link_string);
	if(isUrl(link_string)){
		if(link_string.indexOf('facebook.com') > 0){
			$("#new_user_link_type").val("facebook");
			$("#new_user_link_name").parent().slideUp(250);
		} else if(link_string.indexOf('linkedin.com') > 0){
			$("#new_user_link_type").val("linkedin");
			$("#new_user_link_name").parent().slideUp(250);
		} else if(link_string.indexOf('instagram.com') > 0){
			$("#new_user_link_type").val("instagram");
			$("#new_user_link_name").parent().slideUp(250);
		} else if(link_string.indexOf('twitter.com') > 0){
			$("#new_user_link_type").val("twitter");
			$("#new_user_link_name").parent().slideUp(250);
		} else {
			$("#new_user_link_type").val("link");
			$("#new_user_link_name").parent().slideDown(250);
		}
	}
	
	
}

function saveNewLink(){
	var link_string = $("#new_user_link").val(), 
	link_type = $("#new_user_link_type").val(),
	link_name = $("#new_user_link_name").val();
	
	if(link_string.length > 4 && isUrl(link_string)){ 
		
		$.post(site_url + "app/saveUserLink/", {
					'link_name' : link_name,
					'link_type' : link_type,
					'link_url' : link_string
					},
				   function(data){
					   if(data.success){
						   loadUserLinks();
						   $("#links_deck .deck_content").slideToggle(250);
							$("#links_deck .deck_form").slideToggle(250);
							maskDecks(7);
						   
						  resetNewLink(); 
						  }
					  }, "json");
		
		
		
		return false;
	}
}

function deleteUserLink(link_id){
	
		$.post(site_url + "app/deleteUserLink/", {
					'link_id' : link_id
					},
				   function(data){
					   if(data.success){
						  $("#user_link_" + link_id).hide(250);
						  }
					  }, "json");
		
		
		
		return false;
	
}

function loadUserLinks(){
	var target_url = site_url + "app/loadUserLinks/";
	$("#links_deck .personal_list").load(target_url);
}

function resetNewLink(){
	$("#new_user_link").val('');
	$("#new_user_link_type").val("link");
	$("#new_user_link_name").val("");
	$("#new_user_link_name").parent().slideUp(250);
}

function maskDecks(deck_id){
	if($('.deck.masked_deck').size() > 0){
		clearMaskDecks();
	} else {
		var no_decks = $("#main_profile_wrapper .deck").size();
		
		for(var i = 0; i < no_decks; i++){
			if(i != deck_id){$("#main_profile_wrapper .deck").eq(i).addClass('masked_deck');}	
		}
	}
}

function clearMaskDecks(){
	$("#main_profile_wrapper .deck").removeClass('masked_deck');
}

function isUrl(s) {

  var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
  return regexp.test(s);
}


function userHelpFillProfile(){
	var hero_bg = $("#profile_head > .background").attr('style');
	if(hero_bg){$("#profile_head .nav .button").removeClass('softblink'); $("#profile_head .nav ").attr({"style" : ""}); } else {$("#profile_head .nav .button").addClass('softblink'); $("#profile_head .nav ").css({"opacity" : "1"});}
	
	var avatar_bg = $("#profile_head > #avatar_image").attr('style');
	if(avatar_bg){$("#profile_head .nav2 .button").removeClass('softblink'); $("#profile_head .nav2").attr({"style" : ""}); } else {$("#profile_head .nav2 .button").addClass('softblink'); $("#profile_head .nav2").css({"opacity" : 1}); }
}

/*

user_notifications

- notification_id
- from_user_id
- user_id
- notification_type (new_follower, confirm_you_work_for, confirm_work_for_you, reply_to_news, reply_to_comment)
- notification_data
- notification_time
- viewed


*/