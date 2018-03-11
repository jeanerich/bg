<?php $listmembers = $leaders['mentors']; if(isset($leaders['users'])){$listusers = $leaders['users'];} 
?><div class='list'>
<?php if(count($listmembers) > 0): ?>
<?php foreach($listmembers as $judge): ?>
	<div class='list_item' id="leader_<?php echo $judge['role_id']; ?>" leader_id="<?php echo $judge['role_id']; ?>" judge_member_id="<?php echo $judge['member_id']; ?>">
    	<div class='indent60 text_wrapper' >
        	<div class='thumb' <?php if($listusers['users'][$judge['member_id']]['avatar_id'] > 0){echo " style='background: url(" . $listusers['images'][$listusers['users'][$judge['member_id']]['avatar_id']]['sizes']['square'] . ") no-repeat;'";} ?>>
            </div>
            <h3><?php echo $listusers['users'][$judge['member_id']]['name']; ?></h3>
            <p class='subtext'><?php echo $listusers['users'][$judge['member_id']]['title']; ?></p>
            <?php if($battle['battle']['battle_type'] == 'team'): ?>
					<?php if(count($teams['teams']) > 0): ?>
                    <div class='form clearfix'>
                       <div class='formfield' id='formfield_select_team'>
                        <label>Select team</label>
                            <select class="select_mentor_team" member_id="<?php echo $judge['member_id']; ?>" team_id="<?php echo $judge['team_id']; ?>">
                                <option value="0">All teams</option>
                            <?php foreach($teams['teams'] as $t): ?>
                                <option value="<?php echo $t['team_id']; ?>"<?php if($t['team_id'] == $judge['team_id']){echo " selected='selected'";} ?>><?php echo $t['team_name']; ?></option>
                            <?php endforeach; ?></select>
                       </div>
                   </div>
					<?php endif;  endif; ?>
        </div>
    	<div class='nav'><?php if($judge['invitation_tatus'] < 1){echo "<span class='status_box'>" . $this->functions->_e("pending confirmation", $dictionary) . "</span>";} else {echo "<span class='status_box confirmed'>" . $this->functions->_e("confirmed", $dictionary) . "</span>";} ?> <a href='#' class='button red softblink hidden' onclick="removeLeader(<?php echo $judge['role_id']; ?>); return false;"><?php echo $this->functions->_e("confirm", $dictionary); ?></a> <a href='#' class='button' onclick="$(this).prev().fadeToggle(250); return false;"><?php echo $this->functions->_e("delete", $dictionary); ?></a></div>
    </div>
<?php endforeach; ?>
<?php else: ?>
<?php echo $this->functions->_e("no mentors yet.", $dictionary); ?>
<?php endif; ?>
</div>
<script>
$(document).ready(function(){
	init_mentor_select_team();
});

function init_mentor_select_team(){
	$(".select_mentor_team").change(function(){
		var memberId = parseFloat($(this).attr('member_id'));
		var teamId = parseFloat($(this).val());	
		
		$.post(site_url + "battles/addBattleLeader/", {
					'battle_id' : <?php echo $battle_id; ?>,
					'member_id' : memberId,
					'team_id' : teamId,
					'title' : 'mentor'
					},
				   function(data){
					   if(data.success){ //loadMentors();
					   }
					  }, "json");
	});
}
</script>