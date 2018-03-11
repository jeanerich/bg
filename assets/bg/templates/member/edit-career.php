<div id="main_feed" class="center_wrapper  <?php if(isset($_COOKIE['left_panel'])){if($_COOKIE['left_panel'] == 'invisible'){echo " close_left_panel";}} ?>">
	
	
	<div id="profile_wrapper">
    	<div class='deck'>
        	
            <div class='form profile_form '>
            	<h2>JOB DESCRIPTION</h2>
            	<form id="job_info" class="clearfix">
                	<input type="hidden" id="job_id" value="<?php echo $jobId; ?>" />
                    <div class='formfield half'><?php // print_r($job); ?>
                        <label>Position title</label>
                        <input type="text" id="job_title" class="required" placeholder="" value="<?php if($jobId > 0){echo $job['job_title']; } ?>" />
                        <div class='tipholder'></div>
                    </div>
                    <div class='formfield half'>
                        <label>Position type</label>
                        <select id="job_type">
                        	<?php foreach($menu_lists['job_status'] as $key => $value): ?>
                            <option value="<?php echo $key; ?>"<?php if($jobId > 0 && $key == $job['job_type']){echo " selected";} ?>><?php echo ucfirst($value); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class='tipholder'></div>
                    </div>
                    <div class='formfield'>
                        <label><?php echo $this->functions->_e("description", $dictionary); ?></label>
                        <textarea id="job_description" class="required"><?php if($jobId > 0){echo $job['job_description']; } ?></textarea>
                        <div class='tipholder'></div>
                    </div>
                    <div class='formfield'>
                        <label>Job <?php echo $this->functions->_e("requirements", $dictionary); ?></label>
                        <textarea id="job_requirements" ><?php if($jobId > 0){echo $job['job_requirements']; } ?></textarea>
                        <div class='tipholder'></div>
                    </div>
                    <div class='formfield'>
                        <label><?php echo $this->functions->_e("how to apply", $dictionary); ?></label>
                        <textarea id="job_apply" ><?php if($jobId > 0){echo $job['job_apply']; } ?></textarea>
                        <div class='tipholder'></div>
                    </div>
                    <div class='form_message'></div>
                    <div class='formfield'>
                    	<button class='button'><?php echo $this->functions->_e("submit", $dictionary); ?></button><?php if($jobId > 0): ?><a href='<?php echo site_url() . "business/previewjob/{$memberId}/{$jobId}"; ?>' class='button'>Preview</a><?php endif; ?>
                    </div>
                </form>
                
                
            </div>
            
        </div>
    </div>
    
    <?php include(DIR_TEMPLATES . "common/left-panel.php"); ?>
    <?php include(DIR_TEMPLATES . "common/right_panel.php"); ?>
</div>
<script>
$(document).ready(function(){
	initializeForm("#job_info", saveJob);
	
});



function saveJob(){
	var job_id = $("#job_id").val();
	var job_title = $("#job_title").val();
	var job_type = $("#job_type").val();
	var job_description = $("#job_description").val();
	var job_requirements = $("#job_requirements").val();
	var job_apply = $("#job_apply").val();
	
	$.post(site_url + "app/saveJob/", {
					'member_id' : <?php echo $memberId; ?>,
					'job_id' : job_id,
					'job_title' : job_title,
					'job_type' : job_type,
					'job_description' : job_description,
					'job_requirements' : job_requirements,
					'job_apply' : job_apply
					},
				   function(data){
					  if(data.success){
						 showMainMessage("Changes saved", false);
						 if(job_id < 1){
							setTimeout(function(){
							 	var targetUrl = site_url + "business/editjob/10/" + data.job_id;
								window.location.replace(targetUrl);
							}, 3000); 
							}
						} else {
							showMainMessage("There was a problem.", true);
						}
					   
					  }, "json");
	
}


</script>
