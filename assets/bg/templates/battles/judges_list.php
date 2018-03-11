<?php  $listmembers = $leaders['judges']; if(isset($leaders['users'])){$listusers = $leaders['users'];} 
?><div class='list'>
<?php if(count($listmembers) > 0): ?>
<?php foreach($listmembers as $judge): ?>
	<div class='list_item' id="leader_<?php echo $judge['role_id']; ?>" leader_id="<?php echo $judge['role_id']; ?>" judge_member_id="<?php echo $judge['member_id']; ?>">
    	<div class='indent60'>
        	<div class='thumb' <?php if($listusers['users'][$judge['member_id']]['avatar_id'] > 0){echo " style='background: url(" . $listusers['images'][$listusers['users'][$judge['member_id']]['avatar_id']]['sizes']['square'] . ") no-repeat;'";} ?>>
            </div>
            <h3><?php echo $listusers['users'][$judge['member_id']]['name']; ?></h3>
            <p class='subtext'><?php echo $listusers['users'][$judge['member_id']]['title']; ?></p>
        </div>
    	<div class='nav'><?php if($judge['invitation_tatus'] < 1){echo "<span class='status_box'>" . $this->functions->_e("pending confirmation", $dictionary) . "</span>";} else {echo "<span class='status_box confirmed'>" . $this->functions->_e("confirmed", $dictionary) . "</span>";} ?> <a href='#' class='button red softblink hidden' onclick="removeLeader(<?php echo $judge['role_id']; ?>); return false;"><?php echo $this->functions->_e("confirm", $dictionary); ?></a> <a href='#' class='button' onclick="$(this).prev().fadeToggle(250); return false;"><?php echo $this->functions->_e("delete", $dictionary); ?></a></div>
    </div>
<?php endforeach; ?>
<?php else: ?>
<?php echo $this->functions->_e("no judges yet.", $dictionary); ?>
<?php endif; ?>
</div>