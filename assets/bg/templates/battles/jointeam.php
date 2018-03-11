<div class='modal_mini_panel'>
	<h1><?php echo $this->functions->_e("join a team", $dictionary); ?></h1>
<p><?php echo $this->functions->_e("Once you join a team, you cannot switch sides once the competition has begun.", $dictionary); ?></p>
<div class='shortbar softblink'></div>
<?php $team = $teams['teams'][$team_id]; //print_r($teams); ?>
<?php $imageSrc = ""; 
					if($team['team_card'] > 0){
						if(isset($teams['images'][$team['team_card']]['sizes']['card'])){
							$imageSrc = 	$teams['images'][$team['team_card']]['sizes']['card'];
						} else {
							$imageSrc = 	$teams['images'][$team['team_card']]['sizes']['thumb'];
						}
						$imageSrc = " style='background: url($imageSrc) no-repeat;'";
					}
					//print_r($teams);
				?>
<div class='fs_thumb' <?php echo $imageSrc; ?>></div>
<h3><?php echo $battle['battle']['battle_name']; ?></h3>
<h2><?php echo $this->functions->_e("team", $dictionary); ?>: <?php echo $team['team_name']; ?></h2>
<div class='form_message'></div>
<p><a href='#' onclick="joinBattle(<?php echo $battle_id . "," . $team_id; ?>); return false;" class='button'><?php echo $this->functions->_e("join this team", $dictionary); ?></a> <a href='#' class='button darker' onclick="close_fs_modal(); return false;"><?php echo $this->functions->_e("cancel", $dictionary); ?></a></p>
</div>