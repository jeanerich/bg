<div id="battle_hero" class="battle_billboard home clearfix"><?php //print_r($battles); ?>
	<input type="hidden" id="battle_info" value='<?php echo json_encode($battles['battle_info']); ?>' />
    <input type="hidden" id="battle_id" value="<?php echo $battle_id; ?>" />
	<?php if(isset($battles['battle_info'])): ?>
		<?php if($battle_id > 0): // IF THIS IS A NUMBERED BATTLED (With a Battle ID) ?>
        <div class='board vote_machine' id="board_1">
    <div class='bg_grid threed'></div>
    <?php if(count($battles['images']) > 1):  ?>
        <div class='side_panel_wrapper left threed'> 
            <?php $battle1 = $battles['battles'][0];
            $battle2 = $battles['battles'][1];
            $battleusers = $battles['users'];
            if($battle_id > 0 && isset($battles['images'])){$battle_images = $battles['images']; }
                //print_r($battle1); print_r($battle2); print_r($battles['users']);
                if(count($battles['users']) > 0){$battle_users = $battles['users'];} // if there are users defined in array
                foreach($battle1 as $i):  ?>
                <div class='panel transition1000 threed'><div class='imagewrapper' bg="<?php echo $battle_images[$i]['source']; ?>" style=""></div><div class='highlight'></div>
                    <div class='overlay_bg'>
                        <div class='topdiamond softblink'></div><?php $imagetitle = $battle_images[$i]['title']; if(strlen($imagetitle) < 1){$imagetitle = $this->functions->_e("untitled image", $dictionary);} ?>
                        <h2><?php echo $imagetitle; ?></h2>
                        <p>By</p> 
                        <h3><?php echo $battleusers['users'][$battle_images[$i]['user_id']]['name']; ?></h3>
                        <?php //print_r($i); ?>
                    </div>
                    <div class='zoom_image left' onclick="fs_modal('<?php echo "battles/previewImage/{$i}/{$battle_images[$i]['token']}?dark=dark";  ?>');";>
                    </div>
                </div>
                <?php endforeach; ?>
        </div>
        <div class='side_panel_wrapper right threed'>
            <?php $battle = $battle2;
                // if there are users defined in array
                foreach($battle2 as $i): ?>
                <div class='panel transition1000 threed'><div class='imagewrapper ' bg="<?php echo $battle_images[$i]['source']; ?>" style=""></div><div class='highlight'></div>
                    <div class='overlay_bg'>
                        <div class='topdiamond softblink'></div><?php $imagetitle = $battle_images[$i]['title']; if(strlen($imagetitle) < 1){$imagetitle = $this->functions->_e("untitled image", $dictionary);} ?>
                        <h2><?php echo $imagetitle; ?></h2>
                        <p>By</p>
                        <h3><?php echo $battleusers['users'][$battle_images[$i]['user_id']]['name']; ?></h3>
                        <?php //print_r($i); ?>
                    </div>
                    <div class='zoom_image right'  onclick="fs_modal('<?php echo "battles/previewImage/{$i}/{$battle_images[$i]['token']}?dark=dark";  ?>');">
                    </div>
                </div>
                <?php endforeach; ?>
        </div>
        
        <div class='diamond blink3'><div class='arrow left' onclick="voteImage(0);"></div><div class='arrow right' onclick="voteImage(1);"></div></div>
        <div id="vote_diamond"><h2 class='vote_sequence'><?php echo $this->functions->_e("vote", $dictionary); ?></h2><div class='next_sequence' onclick="nextBattle();"><h2><?php echo $this->functions->_e("next", $dictionary); ?></h2></div></div>
        <?php else: ?>
        <div class='center_message_box'><h2><?php echo $this->functions->_e("not enough entries yet to vote on...", $dictionary); ?></h2></div>
       <?php endif; ?> 
    </div>
    
        <?php else: // IF THIS IS NOT A NUMBERED BATTLE (Without a Battle ID) ?>
        <div class='board vote_machine' id="board_1">
        <div class='bg_grid threed'></div>
            <div class='side_panel_wrapper left threed'> 
                <?php $battle1 = $battles['battles'][0];
                $battle2 = $battles['battles'][1];
                $battleusers = $battles['users'];
                if($battle_id > 0 && isset($battles['images'])){$battle_images = $battles['images']; }
                    //print_r($battle1); print_r($battle2); print_r($battles['users']);
                    if(count($battles['users']) > 0){$battle_users = $battles['users'];} // if there are users defined in array
                    foreach($battle1 as $i):  ?>
                    <div class='panel transition1000 threed'><div class='imagewrapper' bg="<?php echo $i['source']; ?>" style=""></div><div class='highlight'></div>
                        <div class='overlay_bg'>
                            <div class='topdiamond softblink'></div><?php $imagetitle = $i['title']; if(strlen($imagetitle) < 1){$imagetitle = $this->functions->_e("untitled image", $dictionary);} ?>
                            <h2><?php echo $imagetitle; ?></h2>
                            <p>By</p> 
                            <h3><?php echo $battleusers['users'][$i['user_id']]['name']; ?></h3>
                            <?php //print_r($i); ?>
                        </div>
                        <div class='zoom_image left' onclick="fs_modal('<?php echo "battles/previewImage/{$i['id']}/{$i['token']}/?dark=dark";  ?>');";>
                        </div>
                    </div>
                    <?php endforeach; ?>
            </div>
            <div class='side_panel_wrapper right threed'>
                <?php $battle = $battle2;
                    // if there are users defined in array
                    foreach($battle2 as $i): ?>
                    <div class='panel transition1000 threed'><div class='imagewrapper ' bg="<?php echo $i['source']; ?>" style=""></div><div class='highlight'></div>
                        <div class='overlay_bg'>
                            <div class='topdiamond softblink'></div><?php $imagetitle = $i['title']; if(strlen($imagetitle) < 1){$imagetitle = $this->functions->_e("untitled image", $dictionary);} ?>
                            <h2><?php echo $imagetitle; ?></h2>
                            <p>By</p>
                            <h3><?php echo $battleusers['users'][$i['user_id']]['name']; ?></h3>
                            <?php //print_r($i); ?>
                        </div>
                        <div class='zoom_image right'  onclick="fs_modal('<?php echo "battles/previewImage/{$i['id']}/{$i['token']}?dark=dark";  ?>');">
                        </div>
                    </div>
                    <?php endforeach; ?>
            </div>
            
            <div class='diamond blink3'><div class='arrow left' onclick="voteImage(0);"></div><div class='arrow right' onclick="voteImage(1);"></div></div>
            <div id="vote_diamond"><h2 class='vote_sequence'><?php echo $this->functions->_e("vote", $dictionary); ?></h2><div class='next_sequence' onclick="nextBattle();"><h2><?php echo $this->functions->_e("next", $dictionary); ?></h2></div></div>
            
            
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>