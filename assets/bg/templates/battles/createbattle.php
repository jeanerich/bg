<?php if($battle_id > 0): ?><div id="main_hero" style=" <?php if($battle['battle']['hero_image'] > 0){echo "background: url(" . $battle['images'][$battle['battle']['hero_image']]['source'] . ") no-repeat;";} ?>">
	<div class='hero_nav'>
    	<a class="button" href="#" onclick="fs_modal('user/add_battle_hero_image/<?php echo $battle_id; ?>?type=battle'); return false;"><?php echo $this->functions->_e("modify hero image", $dictionary); ?></a>
    </div>
</div><?php endif; ?>
<div class='w1200 paddingtop40 paddingbottom60'>
	<div class='section_head'>
		<h1><?php if($battle_id > 0){echo "Modify a battle";} else {echo "Create a battle";} ?></h1>
    	<div class='nav'><?php if($battle_id > 0): ?><a href='<?php echo site_url() . "battles/view/{$battle_id}/"; ?>' class='button'><?php echo $this->functions->_e("view", $dictionary); ?></a><?php endif; ?></div>
    </div>
	<div class='form dark paddingtop20'>
    	<form id="edit_battle_form" class="line clearfix ">
        	<div class='unit <?php if($battle_id > 0){echo "size3of4";} else {echo "size1of1";} ?>'>
            	<input type="hidden" id="battle_id" value="<?php echo $battle_id; ?>" />
                <div class='formfield'>
                    <label>Battle name</label>
                    <input type="text" id="battle_title" class='required' placeholder="" value="<?php if($battle_id > 0){echo $battle['battle']['battle_name'];} ?>"  maxlength="50"/>
                </div>
                <div class='formfield'>
                    <label>Short description</label>
                    <p class='subtext'>This text will appear on social networks when shared along as the title card on this site.</p>
                    <textarea id="battle_short_description" style="height: 50px;" maxlength="250"><?php if($battle_id > 0){echo $battle['battle']['battle_short_description'];} ?></textarea>
                </div>
                <div class='formfield size1of3'>
                    <label>Battle start </label>
                    <p class='subtext'>(Must be after today)</p>
                    <input type="date" id="start_date" class='required' placeholder="" value="<?php if($battle_id > 0){echo date("Y-m-d", strtotime($battle['battle']['start_date']));} ?>"  maxlength="50"/>
                </div>
                <div class='formfield size1of3' id="field_vote_date">
                    <label>Vote begins</label>
                    <p class='subtext'>(must be after previous date)</p>
                    <input type="date" id="vote_date" class='required' placeholder="" value="<?php if($battle_id > 0){echo date("Y-m-d", strtotime($battle['battle']['vote_date']));} ?>"  maxlength="50"/>
                </div>
                <div class='formfield size1of3'>
                    <label>Battle end </label>
                    <p class='subtext'>(must be after previous date)</p>
                    <input type="date" id="end_date" class='required' placeholder="" value="<?php if($battle_id > 0){echo date("Y-m-d", strtotime($battle['battle']['end_date']));} ?>"  maxlength="50"/>
                </div>
                <div class='formfield'>
                    <label>Vote option</label>
                    <p class='subtext'>The first option allows people to vote when the vote begins. Entries can no longer be recieved at that time. The second option only allows people to vote as soon as entries are submitted. Usually preferable for short battles.  </p>
                    <div id="vote_option" class='radiobar clearfix'>
                    	<div class='unit size1of2 radiobutton <?php if($battle_id > 0){if($battle['battle']['battle_and_vote'] == 0){echo "active";}} else {echo "active";} ?>'  vote_option="0">
                        	<span class='displayfont'><div class='check'></div>Vote after entries are submited.</span>
                        </div>
                        <div class='unit size1of2 radiobutton <?php if($battle_id > 0){if($battle['battle']['battle_and_vote'] == 1){echo "active";}} ?>' vote_option="1">
                        	<span class='displayfont'><div class='check'></div>Vote as entries are submitted</span>
                        </div>
                    </div>
                </div>
                <div class='formfield'>
                    <label>Battle type </label>
                    <div id="battle_type" class='radiobar clearfix'>
                    	<div class='unit size1of2 radiobutton <?php if($battle_id > 0){if($battle['battle']['battle_type'] == 'individual'){echo "active";}} else {echo "active";} ?>'  battle_type="individual">
                        	<span class='displayfont'><div class='check'></div>Individual</span>
                        </div>
                        <div class='unit size1of2 radiobutton <?php if($battle_id > 0){if($battle['battle']['battle_type'] == 'team'){echo "active";}} ?>' battle_type="team">
                        	<span class='displayfont'><div class='check'></div>Team</span>
                        </div>
                    </div>
                </div>
                <?php if($battle_id > 0): ?>
                <div id='team_panel' <?php if($battle_id > 0){if($battle['battle']['battle_type'] == 'team'){echo " style='display: block;'";}} ?>>
                	<div class='line paddingtop30 paddingbottom40'>
                	
                    <div class='formfield'>
                    <label>Team options</label>
                    <div id="battle_categories" class='radiobar clearfix'>
                    	
                        <div id="allow_new_teams_to_join" class='unit size1of3 radioselect '  onclick="toggleOption('allow_new_teams_to_join');">
                        	<span class=''><div class='check'></div>Allow new teams to join.</span>
                        </div>
                        <div id="team_invitation_only" class='unit size1of3 radioselect '  onclick="toggleOption('team_invitation_only');">
                        	<span class=''><div class='check'></div>Team join per invitation only.</span>
                        </div>
                    </div>
                </div>
                <?php if(false): ?><div class='formfield half'>
                    <label>Minimum number of members per team</label>
                    <input type="text" id="team_minimum_members" class='' placeholder="0" value=""  maxlength="4"/>
                </div>
                <div class='formfield half'>
                    <label>Maximum number of members per team</label>
                    <input type="text" id="team_maximum_members" class='' placeholder="0" value=""  maxlength="4"/>
                </div><?php endif; ?>
                    </div>
                    <div class='formfield'>
                    	<div class='section_head'>
                    	<h2>Teams</h2>
                        	<div class='nav'>
                            	<a href='#' class='button' onclick="fs_modal('battles/newteam/'); return false;">+ Add team</a> 
                            </div>
                        </div>
                        <div id="battle_List_editor">
                        	
                        </div>
                    </div>
                </div>
                <div class='formfield'>
                    <label>Battle categories (Maximum 3)</label>
                    <div id="battle_categories" class='radiobar clearfix'>
                    	<?php $counter = 0; foreach($menu_lists['battle_categories'] as $key => $value): ?>
                        <div id="category_<?php echo $counter; ?>" class='unit size1of3 radioselect <?php if(in_array($key, $battle['battle']['categories'])){echo "active";} ?>' cat="<?php echo $key; ?>" onclick="toggleCategory(<?php echo $counter; ?>);">
                        	<span class=''><div class='check'></div><?php echo $value; ?></span>
                        </div><?php $counter++; endforeach; ?>
                        
                    </div>
                </div>
                <div class='formfield'>
                    <label>Confine to a country?</label>
                    <select id="battle_country">
                    	<option value="all">No</option>
                        <?php foreach($menu_lists['country_list'] as $key => $value):?>
                        <option value="<?php echo $key; ?>"<?php if($battle['battle']['territory'] == $key){echo " selected='selected'";} ?>><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class='formfield'>
                    <label>Long description</label>
                    <textarea id="battle_long_description" style="height: 150px;" ><?php echo $battle['battle']['battle_long_description']; ?></textarea>
                </div>
                <div class='formfield'>
                    <label>Rules</label>
                    <textarea id="battle_rules" style="height: 150px;" ><?php echo $battle['battle']['battle_rules']; ?></textarea>
                </div>
                <div class='formfield'>
                	<label>Vote method</label>
                    <div class='subtext'>Select either a public vote, jury only or both.</div>
                	<div id="battle_jury" class="radiobar clearfix multi">
                    	<div class="unit size1of2 radiobutton " battle_type="public">
                        	<span class="displayfont"><div class="check"></div>Public vote</span>
                        </div>
                        <div class="unit size1of2 radiobutton select_jury active " battle_type="jury">
                        	<span class="displayfont"><div class="check"></div>Jury vote</span>
                        </div>
                    </div>
                </div>
                <div class='formfield'>
                    <div id="jury_panel" class='paddingtop60'>
                        <div class='section_head'>
                        <h2>Judges / Jury</h2>
                        <div class="nav">
                            <a href="#" class="button" onclick="fs_modal('battles/newjudge/'); return false;">+ <?php echo $this->functions->_e("add judge", $dictionary); ?></a> 
                        </div>
                    </div>
                    <div id="jury_list">
                    </div>
                </div>
                <div id="mentor_panel" class='paddingtop60'>
                        <div class='section_head'>
                        <h2>Mentors</h2>
                        <div class="nav">
                            <a href="#" class="button" onclick="fs_modal('battles/newmentor/<?php echo $battle_id; ?>'); return false;">+ <?php echo $this->functions->_e("add mentor", $dictionary); ?></a> 
                        </div>
                    </div>
                    <div id="mentor_list">
                    </div>
                </div>
                </div>
                <?php endif; ?>
                <div class='formfield <?php if($battle_id > 0){ echo "stickyfooter"; } ?>'>
                	<button >Save</button>
                </div>
            </div>
            <?php if($battle_id > 0): ?>
            <div class='unit size1of4'>
            	<div class='sidebar_wrapper'>
                	<div class='formfield'>
                    	<label>Card image</label><?php 
							$imgstring = "";
							if($battle['battle']['card_image'] > 0){
								$imgsrc = $battle['images'][$battle['battle']['card_image']]['sizes']['thumb'];
								if(isset($battle['images'][$battle['battle']['card_image']]['sizes']['card'])){
									$imgsrc = $battle['images'][$battle['battle']['card_image']]['sizes']['card'];
								}
								$imgstring = "background: url({$imgsrc}) no-repeat;";
							} 
						?>
                        <div class='cardWrapper' style=" <?php echo $imgstring; ?>"><a class="button" href="#" onclick="fs_modal('user/add_battle_card_image/<?php echo $battle_id; ?>?type=battle_card'); return false;">Change image</a></div>
                        <p class='subtext'>This is the image that will appear on social media when shared.</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>
