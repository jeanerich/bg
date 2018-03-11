<div class='modal_mini_panel'>
<h1><?php if($timeline_id > 0){echo $timeline['business_name'];} else {if($type == 'work'){echo $this->functions->_e("add work experience", $dictionary);} else{echo $this->functions->_e("add education", $dictionary); }  } ?></h1>
<div class='formwrap fullwidth'>
        
    <div class='form'>
                            <form id="work_form">
                                <input type="hidden" class="timeline_id" value="<?php echo $timeline_id; ?>" >
                                <input type="hidden" class="position_type" value="<?php echo $type; ?>" />
                                <div class='formfield'>
                                    <label><?php if($type == 'work'){echo $this->functions->_e("name of business", $dictionary);} else {echo $this->functions->_e("name of school", $dictionary);} ?></label>
                                    <input type="text" class='required business_name' value="<?php if($timeline_id > 0){echo $timeline['business_name'];} ?>"  maxlength="100" />
                                    <input type="hidden" class='business_id' value="<?php if($timeline_id > 0){echo $timeline['business_id'];} else {echo 0;} ?>" />
                                    
                                </div>
                                <div class='formfield'>
                                    <label><?php if($type == 'work'){echo $this->functions->_e("your title / position", $dictionary);} else {echo $this->functions->_e("name of the program taken", $dictionary);} ?></label>
                                    <input type="text" class='required position_name' value="<?php if($timeline_id > 0){echo $timeline['position_name'];} ?>" maxlength="100"/>
                                    
                                </div>
                                <div class='formfield'>
                                	<label><?php echo $this->functions->_e("i am still there", $dictionary); ?></label>
                                    <div id="position_still_active" class='checkbox <?php if($timeline['still_active'] > 0){echo " active";} ?>'></div>
                                </div>
                                <div class='formfield half'>
                                    <label>Début</label>
                                    <select class='business_start_year' style="width: 70px">
                                        <?php $start_year = (int)date("Y"); for($i = 0; $i < 50; $i++):  $y = (int)$start_year - $i;?><option value="<?php echo $y; ?>" <?php if($timeline_id > 0 && (int)$timeline['start_year'] == $y){echo " selected='selected' ";} ?>><?php echo $y; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <select class='business_start_month' style="width: 130px">
                                        <?php $counter = 1; foreach($menu_lists['months'] as $key => $m) :?><option value="<?php echo sprintf("%02d", $counter); ?>" <?php if($timeline_id > 0 && $counter == $timeline['start_month']){echo " selected='selected' ";} ?>><?php echo $m; ?></option>
                                        <?php $counter++; endforeach; ?>
                                    </select>
                                    
                                </div>
                                <div id="business_end_date" class='formfield half'>
                                    <label>Fin</label>
                                    <select class='business_end_year' style="width: 70px">
                                        <?php $start_year = (int)date("Y"); for($i = 0; $i < 50; $i++): $y = (int)$start_year - $i; ?><option value="<?php echo $y; ?>" <?php if($timeline_id > 0 && (int)$timeline['end_year'] == $y){echo " selected='selected' ";} ?>><?php echo $y; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <select class='business_end_month' style="width: 130px">
                                        <?php $counter = 1; foreach($menu_lists['months'] as $key => $m) :?><option value="<?php echo sprintf("%02d", $counter); ?>" <?php if($timeline_id > 0 && $counter == $timeline['end_month']){echo " selected='selected' ";} ?>><?php echo $m; ?></option>
                                        <?php $counter++; endforeach; ?>
                                    </select>
                                </div>
                                <div class='formfield'>
                                    <label><?php if($type == 'work'){echo $this->functions->_e("job description", $dictionary);} else {echo $this->functions->_e("course description", $dictionary);} ?></label>
                                    <textarea class='business_description' style="height: 200px;"><?php if($timeline_id > 0){echo $timeline['description'];} ?></textarea>
                                    
                                </div>
                                <div class='formfield'>
                                    <button class='rectangle_button'><?php {echo $this->functions->_e("save", $dictionary);} ?></button>
                                    
                                </div>
                            </form><?php if($timeline_id > 0): ?>
                                <div class='formfield'>
                                	<button class='rectangle_button' onclick="$(this).next().fadeToggle(500);"><?php echo $this->functions->_e("delete", $dictionary); ?></button> <button class='rectangle_button red' onclick="deleteTimelineItem(<?php echo $timeline['id']; ?>, '<?php echo $type; ?>');" style="display: none; color: white; background: red;"><?php echo $this->functions->_e("confirm delete", $dictionary); ?> delete</button> 
                                </div><?php endif; ?>
                        </div>
    </div>
</div>
<script>
$(document).ready(function(){
	initializeForm("#work_form", saveWorkTimeline);
	toggleBusinessEnd();
	$("#position_still_active").click(function(){
		toggleStillActive();	
	});
});

function toggleStillActive(){
	$("#position_still_active").toggleClass('active');
	toggleBusinessEnd();
}

function toggleBusinessEnd(){
	if($("#position_still_active.active").size() > 0){
		$("#business_end_date").hide();
	} else {
		$("#business_end_date").show();
	}
}

function saveWorkTimeline(){
	var timeline_type = $("#work_form .position_type").val(); 
	var timeline_id = parseFloat($("#work_form .timeline_id").val());
	var business_name = $("#work_form .business_name").val();
	var business_id = parseFloat($("#work_form .business_id").val());
	var position_name = $("#work_form .position_name").val();
	var still_active = $("#position_still_active.active").size();
	
	var business_start = $("#work_form .business_start_year").val() + "-" + $("#work_form .business_start_month").val() + "-01";
	var business_end = $("#work_form .business_end_year").val() + "-" + $("#work_form .business_end_month").val() + "-01";
	var business_description = $("#work_form .business_description").val();
	// timeline_id, business_name, business_id, business_start, business_end, business_description
	$.post(site_url + "app/modifyBusinessTimeline/", {
					'timeline_type' : timeline_type,
					'timeline_id' : timeline_id,
					'business_name' : business_name,
					'position_name' : position_name,
					'business_id' : business_id,
					'business_start' : business_start,
					'business_end' : business_end,
					'business_description' : business_description,
					'still_active' : still_active
					},
				   function(data){
					   if(data.success){
						   <?php if($type == 'work'): ?>
							   var target_url = site_url + "app/getTimelineElements/";
							   $(".work_list_timeline").load(target_url);
						   <?php else: ?>
								var target_url = site_url + "app/getTimelineElements/?c=education";
							   $(".education_list_timeline").load(target_url);
							<?php endif; ?>
						   close_fs_modal();
						   
						   setTimeout(function(){showMainMessage("<?php {echo $this->functions->_e("saved", $dictionary);} ?>");}, 1000);
						  
						  }
					  }, "json");
	
	
}

function deleteTimelineItem(timeline_id, item_type){
	console.log("timeline id: " + timeline_id);
	$.post(site_url + "app/deleteBusinessTimelineItem/", {
					'timeline_id' : timeline_id
					},
				   function(data){
					   if(data.success){
						   <?php if($type == 'work'): ?>
							   var target_url = site_url + "app/getTimelineElements/";
							   $(".work_list_timeline").load(target_url);
						   <?php else: ?>
								var target_url = site_url + "app/getTimelineElements/?c=education";
							   $(".education_list_timeline").load(target_url);
							<?php endif; ?>
							close_fs_modal();
						   
						   setTimeout(function(){showMainMessage("<?php {echo $this->functions->_e("deleted", $dictionary);} ?>");}, 1000);
							
						  }
					  }, "json");
}
</script>