<div class='modal_mini_panel'>
<h1><?php echo $this->functions->_e("new judge", $dictionary); ?></h1>
	<div id="select_member_preview">
    	<div class='text_wrapper'>
        	<div class='thumb'></div>
        	<h2></h2>
            <button class='button' onclick="saveJudge();  ">Invite this judge</button>
            <p class='subtext'>Once invited, this member will need to confirm this invitation to appear publicly on the battle page.</p>
        </div>
    </div>
<p><?php echo $this->functions->_e("search a member by typing a name below and select from the list.", $dictionary); ?></p>

        
        <div class='form'>
        	<form id="new_team">
        	<div class='formfield'>
            	<label><?php echo $this->functions->_e("new judge name", $dictionary); ?></label>
                <input type="text" id="judge_name" class=" "  placeholder="" />
                <div id="judge_user_list" class='form_list'></div>
            </div>
            <div class='form_message'>
            </div>
            <div class='formfield'>
            	
            </div>
            </form>
            
        </div>
    
</div>
<script>
$(document).ready(function(){
		$("#judge_name").keyup(function(){
			searchBattleUser($("#judge_name").val());	
		});
		$("#judge_name").focus();
		
		initializeForm("#new_team", createNewTeam);
});

function createNewTeam(){
	var team_name	 = $("#team_name").val();
	var battle_id = parseFloat($("#battle_id").val());
	
	$.post(site_url + "app/createNewTeam/", {
					'battle_id' : battle_id,
					'team_name' :  team_name
					
					},
				   function(data){
					   if(data.success){
						 getBattleTeams();
						 close_fs_modal();  
						 }
					  }, "json");
}

function searchBattleUser(searchString){
	if(searchString.length > 0){
		$.post(site_url + "user/search/", {
					's' : searchString
					},
				   function(data){
					   if(data.success){
						 	listUsers("#judge_user_list", data.users, data.images);  
						 }
					   
					  }, "json");	
	} else {
		
	}
}




</script>