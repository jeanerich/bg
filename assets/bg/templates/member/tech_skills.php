<?php foreach($tech_skills as $ts): ?>
                    	<div class='tech_skill item' id="tech_skill_<?php echo $ts['id']; ?>"><?php if($editable){echo "<div class='nav'><a href='#' class='edit' onclick='editTechSkills({$ts['id']}, \"{$ts['name']}\", {$ts['skill_id']}, {$ts['skill_level']}); return false;'></a><a href='#' onclick='deleteTechSkills({$ts['id']}); return false;' class='delete'></a></div>";} ?>
                        	<p><?php echo $ts['name']; ?></p>
                            <div class='bar_wrapper'>
                            	<div class='dark_bar' data="<?php echo ((int)$ts['skill_level'] * 10); ?>" style="width: 0;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>