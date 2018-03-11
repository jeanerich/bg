<div class='modal_mini_panel '>
<h1><?php echo $this->functions->_e("invitations", $dictionary); ?></h1>
<?php $imgoutput = "";
 if($battle['battle']['hero_image'] > 0){
	$imgoutput = "<div class='fs_thumb' style='background: url({$battle['images'][$battle['battle']['hero_image']]['sizes']['thumb']}) no-repeat;'></div>"; 
} echo $imgoutput; ?>
<p><?php echo $this->functions->_e("You have been asked to join this battle as a ", $dictionary) . " " . $this->functions->_e($role['role_type'], $dictionary); ?>.</p>
<p><?php echo $this->functions->_e("do you accept?", $dictionary); ?></p>
<p><a href='#' class='button' onclick="respondBattleInvitation(1); return false;">&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp;yes&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp;</a> <a href='#' class='button darker'  onclick="respondBattleInvitation(0); return false;">no thank you</a></p>

</div>
<script>

function respondBattleInvitation(response){
$.post(site_url + "app/respondBattleInvitation/", {
					'role_id' : <?php echo $role['role_id']; ?>,
					'battle_id' : <?php echo $battle_id; ?>,
					'response' : response
					
					},
				   function(data){
					   if(data.success){
						   close_fs_modal();
						  }
					  }, "json");
}
</script>