     	<div class='deck magic_margin deck magic_margin paddingbottom40' id="personal_deck">
                <div class='deck_head nobottompadding'>
                    <h2><?php echo $this->functions->_e("personal", $dictionary); ?></h2>
                    <?php if($editable): ?><div class='edit_console'><a href='#' class='edit_content' onclick="togglePersonal(); return false; "><?php {echo $this->functions->_e("edit", $dictionary);} ?></a></div><?php endif; ?>
                </div>
                <div class='deck_content '>
                	<div class='personal_list clearfix'>
                    	<div class='item' id="personal_user_name">
                        	<strong><?php {echo $this->functions->_e("name", $dictionary);} ?>:</strong> <span><?php echo $memberInfo['name']; ?></span>
                        </div>
                        <div class='item' id="personal_user_title">
                        	<strong><?php {echo $this->functions->_e("title", $dictionary);} ?>:</strong> <span><?php if(strlen($memberInfo['title']) > 0){ echo $memberInfo['title'];} else {echo "NA";} ?></span>
                        </div>
                        <div class='item' id="personal_user_birthday">
                        	<strong><?php {echo $this->functions->_e("birthday", $dictionary);} ?>:</strong> <span><?php if($memberInfo['birthday'] != "0000-00-00"){ echo date("M j", strtotime($memberInfo['birthday']));} else {echo "NA";} ?></span>
                        </div>
                        <div class='item' id="personal_user_nationality">
                        	<strong><?php {echo $this->functions->_e("nationality", $dictionary);} ?>:</strong> <span><?php if(strlen($memberInfo['nationality']) > 0){echo $menu_lists['country_list'][$memberInfo['nationality']];} else {echo "NA";} ?></span>
                        </div>
                        <div class='item' id="personal_user_languages">
                        	<strong><?php {echo $this->functions->_e("languages", $dictionary);} ?>:</strong> <span><?php if(strlen($memberInfo['languages']) > 0){ echo $memberInfo['languages'];} else {echo "NA";} ?></span>
                        </div>
                       
                    </div>
                </div>
                <div class='deck_form '>
                	<form id="personal_form" class='form clearfix paddingtop20'>
                    	<div class='formfield half'>
                        	<label><?php {echo $this->functions->_e("first name", $dictionary);} ?></label>
                            <input type="text" id="form_user_first_name" value="<?php echo $memberInfo['first_name']; ?>" />
                        </div>
                        <div class='formfield half'>
                        	<label><?php {echo $this->functions->_e("last name", $dictionary);} ?></label>
                            <input type="text" id="form_user_last_name" value="<?php echo $memberInfo['last_name']; ?>" />
                        </div>
                        <div class='formfield half'>
                        	<label><?php {echo $this->functions->_e("title", $dictionary);} ?></label>
                            <input type="text" id="form_user_title" value="<?php echo $memberInfo['title']; ?>" />
                        </div>
                        <div class='formfield half'>
                        	<label><?php {echo $this->functions->_e("birthday", $dictionary);} ?></label>
                            <input type="date" id="form_user_birthday" value="<?php  echo $memberInfo['birthday']; ?>" />
                        </div>
                        <div class='formfield half'>
                        	<label><?php {echo $this->functions->_e("nationality", $dictionary);} ?></label>
                            <select id="form_user_nationality" >
                            <?php 
								$cur_country = ""; if(isset($_COOKIE['user_country'])){$cur_country = $_COOKIE['user_country'];}
								foreach($menu_lists['country_list'] as $key => $country): ?>
                            	<option value="<?php echo $key; ?>" <?php if($key == $cur_country){echo " selected='selected'"; } ?>><?php echo $country; ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                        <div class='formfield half'>
                        	<label><?php {echo $this->functions->_e("languages", $dictionary);} ?></label>
                            <input type="text" id="form_user_languages" value="<?php echo $memberInfo['languages']; ?>" />
                        </div>
                        <div class='formfield'>
                        	<button class='button' onclick="savePersonalInfo(); return false; "><?php {echo $this->functions->_e("save", $dictionary);} ?></button>
                        </div>
                        
                    </form>
                </div>
          </div>
     	<div class='deck magic_margin' id="description_deck">
        	<div class='deck_head'>
            	<h2><?php echo $this->functions->_e("profile", $dictionary); ?></h2>
                <?php if($editable): ?><div class='edit_console'><a href='#' class='edit_content' onclick="toggleDescription(); return false; "><?php {echo $this->functions->_e("edit", $dictionary);} ?></a></div><?php endif; ?>
            </div>
            <div class='deck_content'>
            	<div class='text_wrapper'>
            <?php if(strlen($memberInfo['description']) > 0){echo nl2br($memberInfo['description']);} else {echo $this->functions->_e("not available", $dictionary);} ?>
            	</div>
            </div>
            <?php if($editable): ?><div class='deck_form'>
            	<form id="description_form" class='form clearfix'>
                	<div class='formfield'>
                    	<label><?php echo $this->functions->_e("description", $dictionary); ?></label>
                        <textarea id="description_field" ><?php echo $memberInfo['description']; ?></textarea>
                    </div>
                    <div class='formfield'>
                    	<button class='button'><?php echo $this->functions->_e("save", $dictionary); ?></button>
                    </div>
                </form>
                </div><?php endif; ?>
            </div>
            <?php include(DIR_TEMPLATES . "member/skills.php");  ?>
            <div class='deck magic_margin paddingbottom40' id="work_deck">
                <div class='deck_head'>
                    <h2><?php echo $this->functions->_e("work", $dictionary); ?></h2>
                    <?php if($editable): ?><div class='edit_console'><a href='#' class='edit_content' onclick="addWorkTimeline(0); return false; "><?php {echo $this->functions->_e("add", $dictionary);} ?></a></div><?php endif; ?>
                </div>
                <div class='deck_content fixed300 timeline_wrapper'>
                	<div class='scroll_wrapper'>
                    	<div class='scroll_content work_list_timeline'>
                        	<ul id="work_list" class="list_timeline">
                            	<?php if(count($timeline['work']) > 0): foreach($timeline['work'] as $item): ?>
                                <?php $end_year = $item['end_year']; if($end_year == "3000"){$end_year = $this->functions->_e("now", $dictionary);}?>
                                	<li><div class='year_marker'><span><?php echo $end_year; ?></span></div><div class='dot'></div><div class='textwrapper'><h3><?php echo $item['business_name']; ?> (<?php echo $item['start_year'] . " &mdash; " . $end_year; ?>)<?php if($editable): ?> <a href='#' class='' onclick="addWorkTimeline(<?php echo $item['id']; ?>); return false; "><?php echo $this->functions->_e("edit", $dictionary); ?></a><?php endif; ?></h3><h4><?php echo $item['position_name']; ?></h4><?php echo nl2br($item['description']); ?></div></li>
                                <?php endforeach; endif; ?>
                            </ul>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class='deck magic_margin paddingbottom40' id="education_deck">
                <div class='deck_head'>
                    <h2><?php echo $this->functions->_e("education", $dictionary); ?></h2>
                    <?php if($editable): ?><div class='edit_console'><a href='#' class='edit_content' onclick="addEducationTimeline(0); return false; "><?php {echo $this->functions->_e("add", $dictionary);} ?></a></div><?php endif; ?>
                </div>
                <div class='deck_content fixed300 timeline_wrapper'>
                	<div class='scroll_wrapper'>
                    	<div class='scroll_content education_list_timeline'>
                        	<ul id="education_list" class="list_timeline">
                            <?php if(count($timeline['education']) > 0): foreach($timeline['education'] as $item): ?>
                            <?php $end_year = $item['end_year']; if($end_year == "3000"){$end_year = $this->functions->_e("now", $dictionary);}?>
                                	<li><div class='year_marker'><span><?php echo $end_year; ?></span></div><div class='dot'></div><div class='textwrapper'><h3><?php echo $item['business_name']; ?> (<?php echo $item['start_year'] . " &mdash; " . $end_year; ?>)<?php if($editable): ?> <a href='#' class='' onclick="addEducationTimeline(<?php echo $item['id']; ?>); return false; "><?php echo $this->functions->_e("edit", $dictionary); ?></a><?php endif; ?></h3><h4><?php echo $item['position_name']; ?></h4><?php echo nl2br($item['description']); ?></div></li>
                                <?php endforeach; endif; ?>
                            </ul>
                            <?php if($editable): ?>
                            <div class='form'>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class='deck magic_margin' id="technology_deck">
                <div class='deck_head'>
                    <h2><?php echo $this->functions->_e("software & technology", $dictionary); ?></h2>
                    <?php if($editable): ?><div class='edit_console'><a href='#' class='edit_content' onclick="addTechSkill(0); return false; "><?php {echo $this->functions->_e("add", $dictionary);} ?></a></div><?php endif; ?>
                </div>
                <div class='deck_content '>
                	<div id="tech_skills" class="clearfix">
                	<?php if(count($tech_skills) > 0): ?>
                    <?php include(DIR_TEMPLATES . "profile/tech_skills.php"); ?>
                    <?php endif; ?>
                	</div>
                </div>
                <?php if($editable): ?><div class='deck_form tech'>
                	<form id="technology_form" class='form clearfix'>
                    	<div class='formfield half'>
                        	<label><?php echo $this->functions->_e("technology name", $dictionary); ?></label>
                        	<input type="text" id="technology_name"  />
                            <input type="hidden" id="technology_pos" value="0" />
                            <input type="hidden" id="technology_id" value="0"/>
                            <div id="technology_form_list" class='form_list'>
                            </div>
                        </div>
                        <div class='formfield half'>
                        	<label><?php echo $this->functions->_e("select level", $dictionary); ?></label>
                        	<div id="tech_skill_value" value="0" class="select_bar clearfix">
                            <?php for($i = 0; $i < 10; $i++): ?>
                        	                       		<div class="bar_unit" barid="<?php echo $i; ?>">
                            	<div class="box" onclick="selectBoxValue(this, <?php echo $i; ?>); "></div>
                                
                            </div> <?php endfor; ?>
                        	</div>
                            <div class='line clearfix'>
                            	<div class='size1of2 unit'>
                                	<?php echo $this->functions->_e("beginner", $dictionary); ?>
                                </div>
                                <div class='size1of2 unit rightalign' style='width: calc(50% - 20px); padding-right: 20px;'>
                                	<?php echo $this->functions->_e("genius", $dictionary); ?>
                                </div>
                            </div>
                        </div>
                        <div class='formfield'>
                        	<button class='button' onclick="saveTechExpertise(); return false;"><?php echo $this->functions->_e("save", $dictionary); ?></button>
                        </div>
                    </form>
                </div><?php endif; ?>
           </div>
           <div class='deck magic_margin paddingbottom50' id="contact_deck">
                <div class='deck_head nobottompadding'>
                    <h2><?php echo $this->functions->_e("contact", $dictionary); ?></h2>
                    <?php if($editable): ?><div class='edit_console'><a href='#' class='edit_content' onclick="editContactForm(); return false; "><?php {echo $this->functions->_e("edit", $dictionary);} ?></a></div><?php endif; ?>
                </div>
                <div class='deck_content '>
                	<div class='personal_list clearfix'>
                    	<div class='item' id="contact_mobile">
                        	<strong><?php {echo $this->functions->_e("mobile", $dictionary);} ?>:</strong> <span><?php if(isset($memberInfo['contact']['contact_mobile']) && strlen($memberInfo['contact']['contact_mobile']) > 0){echo $memberInfo['contact']['contact_mobile'];} else {echo "NA"; } ?></span>
                        </div>
                        <div class='item' id="contact_work_phone">
                        	<strong><?php {echo $this->functions->_e("work phone", $dictionary);} ?>:</strong> <span><?php  if(isset($memberInfo['contact']['contact_work_phone']) && strlen($memberInfo['contact']['contact_work_phone']) > 0){echo $memberInfo['contact']['contact_work_phone'];} else {echo "NA"; } ?></span>
                        </div>
                        <div class='item' id="contact_skype">
                        	<strong><?php {echo $this->functions->_e("skype", $dictionary);} ?>:</strong> <span><?php  if(isset($memberInfo['contact']['contact_user_skype']) && strlen($memberInfo['contact']['contact_user_skype']) > 0){echo $memberInfo['contact']['contact_user_skype'];} else {echo "NA"; } ?></span>
                        </div>
                        <div class='item' id="contact_fax">
                        	<strong><?php {echo $this->functions->_e("fax", $dictionary);} ?>:</strong> <span><?php  if(isset($memberInfo['contact']['contact_fax']) && strlen($memberInfo['contact']['contact_fax']) > 0){echo $memberInfo['contact']['contact_fax'];} else {echo "NA"; } ?></span>
                        </div>
                        <div class='item' id="contact_address">
                        	<strong><?php {echo $this->functions->_e("address", $dictionary);} ?>:</strong> <span><?php  if(isset($memberInfo['contact']['contact_address']) && strlen($memberInfo['contact']['contact_address']) > 0){echo $memberInfo['contact']['contact_address'];} else {echo "NA"; } ?></span>
                        </div>
                    </div>
                </div>
                <div class='deck_form'>
                	<div class='form clearfix'>
                    	<div class='formfield half'>
                        	<label><?php echo $this->functions->_e("mobile phone", $dictionary); ?></label>
                            <input type="text" id="contact_user_phone_mobile" value="<?php if(isset($memberInfo['contact']['contact_mobile']) && strlen($memberInfo['contact']['contact_mobile']) > 0){echo $memberInfo['contact']['contact_mobile'];} ?>" />
                        </div>
                        <div class='formfield half'>
                        	<label><?php echo $this->functions->_e("work phone", $dictionary); ?></label>
                            <input type="text" id="contact_user_phone_work" value="<?php  if(isset($memberInfo['contact']['contact_work_phone']) && strlen($memberInfo['contact']['contact_work_phone']) > 0){echo $memberInfo['contact']['contact_work_phone'];} ?>" />
                        </div>
                        <div class='formfield half'>
                        	<label><?php echo $this->functions->_e("skype", $dictionary); ?></label>
                            <input type="text" id="contact_user_skype" value="<?php  if(isset($memberInfo['contact']['contact_user_skype']) && strlen($memberInfo['contact']['contact_user_skype']) > 0){echo $memberInfo['contact']['contact_user_skype'];} ?>" />
                        </div>
                        <div class='formfield half'>
                        	<label><?php echo $this->functions->_e("fax", $dictionary); ?></label>
                            <input type="text" id="contact_user_fax" value="<?php  if(isset($memberInfo['contact']['contact_fax']) && strlen($memberInfo['contact']['contact_fax']) > 0){echo $memberInfo['contact']['contact_fax'];} ?>" />
                        </div>
                        <div class='formfield '>
                        	<label><?php echo $this->functions->_e("contact_address", $dictionary); ?></label>
                            <textarea id="contact_user_address" ><?php  if(isset($memberInfo['contact']['contact_address']) && strlen($memberInfo['contact']['contact_address']) > 0){echo $memberInfo['contact']['contact_address'];}?></textarea>
                        </div>
                        <div class='formfield '>
                        	<button class='button' onclick="saveContactForm();"><?php {echo $this->functions->_e("save", $dictionary);} ?></button>
                        </div>
                    </div>
                </div>
          </div>
          <div class='deck magic_margin paddingbottom50' id="links_deck">
                <div class='deck_head nobottompadding'>
                    <h2><?php echo $this->functions->_e("links", $dictionary); ?></h2>
                    <?php if($editable): ?><div class='edit_console'><a href='#' class='edit_content' onclick="addLink(); return false; "><?php {echo $this->functions->_e("add a link", $dictionary);} ?></a></div><?php endif; ?>
                </div>
                <div class='deck_content '>
                	<div class='personal_list clearfix'>
                    	<?php if(count($links) > 0): ?><?php foreach($links as $l): ?><div class='item <?php echo $l['type']; ?>' id="user_link_<?php echo $l['id']; ?>"><?php if($editable): ?><a href='#' class='delete' onclick='deleteUserLink(<?php echo $l['id']; ?>); return false;' ></a><?php endif;  ?>
                        	<strong class='social'><?php if($l['type'] == 'link'){$name = $l['name']; if(strlen($l['name']) < 1){$name = $this->functions->_e("link", $dictionary); } echo "<a href='{$l['url']}' target='_blank'>{$name }</a>";} else {echo "<a href='{$l['url']}' target='_blank'>{$l['type']}</a>";} ?></strong>
                        </div><?php endforeach; endif; ?>
                    </div>
                	
                </div>
                
                <?php if($editable): ?>
                    <div class='deck_form'>
                    	<form class='form clearfix paddingtop10'>
                        	<div class='formfield'>
                            	<label><?php echo $this->functions->_e("insert new links", $dictionary);  ?></label>
                                <input type="text" id="new_user_link" onkeyup="processLink();" value="" />
                            </div>
                            <input type="hidden" id="new_user_link_type" value="link" />
                            <div class='formfield' style="display:none;">
                            	<label><?php echo $this->functions->_e("link name", $dictionary);  ?></label>
                                <input type="text" id="new_user_link_name" value="" />
                            </div>
                            <div class='formfield'>
                            	<button onclick="saveNewLink(); return false;" class='button'><?php echo $this->functions->_e("save", $dictionary);  ?></button>
                            </div>
                        </form>
                    </div>
				<?php endif; ?>
          </div>
          
          
          
          