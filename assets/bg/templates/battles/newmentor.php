<div class='modal_mini_panel'>
<h1><?php echo $this->functions->_e("new mentor", $dictionary); ?></h1>
	<div id="select_member_preview">
    	<div class='text_wrapper'>
        	<div class='thumb'></div>
        	<h2></h2>
            <?php if($battle['battle']['battle_type'] == 'team'): ?>
					<?php if(count($teams['teams']) > 0): ?>
                    <div class='form clearfix'>
                       <div class='formfield' id='formfield_select_team'>
                        <label>Select team</label>
                            <select id="mentor_team_id">
                                <option value="0">All teams</option>
                            <?php foreach($teams['teams'] as $t): ?>
                                <option value="<?php echo $t['team_id']; ?>"><?php echo $t['team_name']; ?></option>
                            <?php endforeach; ?></select>
                       </div>
                   </div>
					<?php endif; ?>
				<?php else: ?>
					<input type="hidden" id="formfield_select_team" value="0" />
				<?php endif; ?>
            <button class='button' onclick="saveMentor();  ">Invite this mentor</button>
            <p class='subtext'>Once invited, this member will need to confirm this invitation to appear publicly on the battle page.</p>
        </div>
    </div>
<p><?php echo $this->functions->_e("search a member by typing a name below and select from the list.", $dictionary); ?></p>

        
        <div class='form'>
        	<form id="new_team">
        	<div class='formfield'>
            	<label><?php echo $this->functions->_e("new mentor name", $dictionary); ?></label>
                <input type="text" id="mentor_name" class=" "  placeholder="" />
                
				
				
                <div id="mentor_user_list" class='form_list'></div>
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
		$("#mentor_name").keyup(function(){
			searchBattleUser($("#mentor_name").val());	
		});
		$("#mentor_name").focus();
		
		//initializeForm("#new_team", createNewTeam);
});



function searchBattleUser(searchString){
	if(searchString.length > 0){
		$.post(site_url + "user/search/", {
					's' : searchString
					},
				   function(data){
					   if(data.success){
						 	listUsers("#mentor_user_list", data.users, data.images);  
						 }
					   
					  }, "json");	
	} else {
		
	}
}




</script>