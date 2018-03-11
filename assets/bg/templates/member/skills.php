<div id="skills_deck" class="deck magic_margin">
            	<div class='deck_head'>
            	<h2><?php echo $this->functions->_e("skills", $dictionary); ?></h2>
                <?php if($editable): ?><div class='edit_console'><a href='#' class='edit_content' onclick="toggleSkills(); return false;">+ <?php echo $this->functions->_e("add skill", $dictionary); ?></a></div><?php endif; ?>
            </div>
            	<div class='deck_content'>
            	<div class='charts clearfix'>
					<?php if(strlen($memberInfo['skills']) > 0) :$skills = unserialize($memberInfo['skills']);  foreach($skills as $key => $value): ?>
                        <?php $pro = $value * 10; $con = 100 - $pro; $deleteString = ""; if($editable){$deleteString = "<div class='edit_chart' onclick='editChart(\"{$key}\");'></div><div class='delete_chart' onclick='deleteChart(\"{$key}\"); return false;'></div>";} if(!empty($key)): ?>
                        
                        <div class='chart' id="skill_chart_<?php echo str_replace(" ", "-", $key); ?>" val="<?php echo $pro . "," . $con; ?>"><canvas id="chart_<?php echo $key; ?>" width="150" height="150"></canvas><div class='percent<?php if($con > $pro){echo " con";} ?>'><strong><?php echo ((int)$pro / 10); ?><span class='small'>/10</span></strong></div><p class='chart_title'><?php echo  $menu_lists['skill_set'][$key]; ?></p><?php echo $deleteString; ?></div>
                    <?php endif; endforeach; endif;  ?>
                    
                    </div>
                    
                 </div>
                 <?php if($editable): ?><div class='deck_form clearfix'>
                 	<form id="skill_form" class='form'>
                	<div class='formfield half'>
                    	<label><?php echo $this->functions->_e("select new skill", $dictionary); ?></label>
                        <select id="select_skill" >
                        	<?php foreach($menu_lists['skill_set'] as $key => $value): ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class='formfield half'>
                    	<label><?php echo $this->functions->_e("your score", $dictionary); ?> (1-10)</label>
                        <div id="skill_value" value="0" class='select_bar clearfix'>
                        	<?php for($i = 0; $i < 10; $i++): ?>
                       		<div class='bar_unit' barid='<?php echo $i; ?>'>
                            	<div class='box' onclick="selectBoxValue(this, <?php echo $i; ?>); "></div>
                                <p><?php echo $i + 1; ?></p>
                            </div> 
                            <?php endfor; ?>
                       </div>
                        
                    </div>
                    <div class='formfield'>
                    	<button class='button'><?php echo $this->functions->_e("save", $dictionary); ?></button> <button onclick="$('#main_profile_wrapper #skills_deck.deck .deck_form').slideUp(250);" class='button'><?php echo $this->functions->_e("cancel", $dictionary); ?></button>
                    </div>
                </form>
                 	
                 </div><?php endif; ?>
            </div>