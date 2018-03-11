<div id="battle_hero" class="battle_billboard home clearfix">
<input type="hidden" id="battle_info" value="<?php echo json_encode($battles['battle_info']); ?>" />
	<div class='board vote_machine' id="board_1">
    
    	<div class='bg_grid threed'><div class='threed clock1'></div><div class='threed clock2'></div></div>
    	<div class='side_panel_wrapper left threed'> <?php // print_r($battles['battles']); ?>
        	<?php $battle1 = $battles['battles'][0];
			$battle2 = $battles['battles'][1];
				
				if(count($battles['users']) > 0){$battle_users = $battles['users'];} // if there are users defined in array
				foreach($battle1 as $i): ?>
                <div class='panel transition1000 threed'><div class='imagewrapper' bg="<?php echo $i['source']; ?>" style=""></div><div class='highlight'></div></div>
                <?php endforeach; ?>
        </div>
        <div class='side_panel_wrapper right threed'>
        	<?php $battle = $battle2;
				// if there are users defined in array
				foreach($battle2 as $i): ?>
                <div class='panel transition1000 threed'><div class='imagewrapper '  bg="<?php echo $i['source']; ?>" style="></div><div class='highlight'></div></div>
                <?php endforeach; ?>
        </div>
        
        <div class='diamond blink3'><div class='arrow left'></div><div class='arrow right'></div></div>
        <div id="vote_diamond"><h2><?php echo $this->functions->_e("vote", $dictionary); ?></h2></div>
        <div class='welcome_text welcome_text1 '><p class='blink3'>Welcome to</p></div>
        <div class='welcome_text welcome_text2 '><p  class='blink3'>Battle Gallery</p></div>
        
    </div>
    
    <div class='battle_console'><a href='#' onclick="nextBattle(); return false;">Next >>></a></div>
</div>