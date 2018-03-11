<div class='modal_mini_panel'>
<h1><?php echo $this->functions->_e("new team", $dictionary); ?></h1>
<p><?php echo $this->functions->_e("begin by entering a new team name below", $dictionary); ?></p>

        
        <div class='form'>
        	<form id="new_team">
        	<div class='formfield'>
            	<label><?php echo $this->functions->_e("new team name", $dictionary); ?></label>
                <input type="text" id="team_name" class=" required" minlength="2" placeholder="" />
            </div>
            <div class='form_message'>
            </div>
            <div class='formfield'>
            	<button class='submit'><?php echo $this->functions->_e("submit", $dictionary); ?></button>
            </div>
            </form>
            
        </div>
    
</div>
<script>
$(document).ready(function(){
		$("#team_name").focus();
		
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
</script>