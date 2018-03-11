<div id="main_feed" class="center_wrapper  <?php if(isset($_COOKIE['left_panel'])){if($_COOKIE['left_panel'] == 'invisible'){echo " close_left_panel";}} ?>">
	
	<div id='profile_head'><div class='background'<?php if($memberInfo['hero_id'] > 0): ?> style="background: url(<?php 
	echo $images[$memberInfo['hero_id']]['sizes']['thumb']; ?>) no-repeat;"<?php endif; ?>></div><?php 
	if($editable): ?><div class='nav'><a class='button' href='#' onclick="fs_modal('user/add_hero_image/<?php echo $memberId; ?>'); return false;">Modify hero image</a></div><?php endif; ?><div id='avatar_image' class='avatar_image <?php echo $profile_type; ?>' <?php if($memberInfo['avatar_id'] > 0): ?> style="background: url(<?php 
	echo $images[$memberInfo['avatar_id']]['sizes']['square']; ?>) no-repeat; "<?php endif; ?>><?php if($editable): ?><div class='nav'><a class='button' href='#' onclick="fs_modal('user/modify_profile_image/<?php echo $memberId; ?>'); return false;">Modify <?php if($profile_type == 'business'){echo "Logo";} else {echo "Avatar";} ?></a></div><?php endif; ?></div></div>
	<div id="profile_wrapper">
    	
        <div class='profile_head'>
        	<h1><?php echo $memberInfo['name']; ?></h1>
            <?php if($editable): ?><div class='profile_console'>
            	<a href="<?php echo site_url() . "member/edit/{$memberId}/" . urlencode($memberInfo['name']); ?>" class='button'>Edit Profile</a>
            </div><?php endif; ?>
            
        </div>
       
        
        <div class='deck'>
        	
            <div class='form profile_form '>
            	<h2><?php echo $this->functions->_e("edit profile", $dictionary); ?></h2>
            	<form id="profile_basic_info" class="clearfix">
                    <div class='formfield half'>
                        <label><?php echo $this->functions->_e("first name", $dictionary); ?></label>
                        <input type="text" id="user_first_name" class="required" minlength="2" placeholder="<?php echo $this->functions->_e("first name", $dictionary); ?>" value="<?php echo $memberInfo['first_name']; ?>" />
                        <div class='tipholder'></div>
                    </div>
                    <div class='formfield half'>
                        <label><?php echo $this->functions->_e("last name", $dictionary); ?></label>
                        <input type="text" id="user_last_name" class="required"  minlength="2" placeholder="<?php echo $this->functions->_e("last name", $dictionary); ?>" value="<?php echo $memberInfo['last_name']; ?>" />
                        <div class='tipholder'></div>
                    </div>
                    <div class='formfield half'>
                        <label><?php echo $this->functions->_e("title", $dictionary); ?></label>
                        <input type="text" id="user_title" class="required"  minlength="2" placeholder="" value="<?php echo $memberInfo['title']; ?>" />
                        <div class='tipholder'></div>
                    </div>
                    <div class='formfield half'>
                        <label><?php echo $this->functions->_e("industry", $dictionary); ?></label>
                        <select id="user_industry">
                        	<?php foreach($menu_lists['industries'] as $key => $value): ?>
                            <option value="<?php echo $key; ?>"<?php if($memberInfo['industries'] == $key){echo " selected";} ?>><?php echo ucfirst($value); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class='tipholder'></div>
                    </div>
                    
                    <input type="hidden" id="user_username" class="tag" exists="username"  placeholder="" value="<?php echo $memberInfo['username']; ?>" />
                    <div class='formfield half'>
                        <label><?php echo $this->functions->_e("email", $dictionary); ?></label>
                        <input type="email" id="user_email" class="required email exists" exists="email" placeholder="Email" value="<?php echo $memberInfo['email']; ?>" />
                        <div class='tipholder'></div>
                    </div>
                    <div class='formfield'>
                    	<label><?php echo $this->functions->_e("about you", $dictionary); ?></label>
                        <textarea id="user_bio" ><?php echo $memberInfo['description']; ?></textarea>
                    </div>
                    <div class='form_message'></div>
                    <div class='formfield'>
                    	<button class='button'><?php echo $this->functions->_e("save", $dictionary); ?></button>
                    </div>
                </form>
                <h2>LOCATION</h2>
                <form id="profile_contact_info" class="clearfix">
                	<div class='formfield half'>
                        <label><?php echo $this->functions->_e("country", $dictionary); ?></label>
                        <select id="user_country">
                        	<?php foreach($menu_lists['country_list'] as $key => $value): ?>
                            <option value="<?php echo $key; ?>"<?php if($memberInfo['country'] == $key){echo " selected";} ?>><?php echo ucfirst($value); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class='tipholder'></div>
                    </div>
                    <div class='formfield half'>
                        <label><?php echo $this->functions->_e("state", $dictionary); ?></label>
                         <select id="user_state">
                        	<?php foreach($menu_lists['US_states_list'] as $key => $value): ?>
                            <option value="<?php echo $key; ?>"<?php if($memberInfo['state'] == $key){echo " selected";} ?>><?php echo ucfirst($value); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class='tipholder'></div>
                    </div>
                    <div class='formfield half' style="display: none;">
                        <label><?php echo $this->functions->_e("province", $dictionary); ?></label>
                         <select id="user_province">
                        	<?php foreach($menu_lists['canadian_provinces_list'] as $key => $value): ?>
                            <option value="<?php echo $key; ?>"<?php if($memberInfo['state'] == $key){echo " selected";} ?>><?php echo ucfirst($value); ?></option>
                            <?php endforeach; ?>
                        </select>
                        
                        <div class='tipholder'></div>
                    </div>
                    <div class='formfield half'>
                        <label><?php echo $this->functions->_e("city", $dictionary); ?></label>
                        <input type="text" id="user_city" class="" minlength="0" placeholder="" value="<?php echo $memberInfo['city']; ?>" />
                        <div class='tipholder'></div>
                    </div>
                    <div class='formfield half'>
                        <label><?php echo $this->functions->_e("postal/zip code", $dictionary); ?></label>
                        <input type="text" id="zip_code" class="" minlength="0" placeholder="" value="<?php echo $memberInfo['zip']; ?>" />
                        <div class='tipholder'></div>
                    </div>
                    <div class='formfield half'>
                        <label><?php echo $this->functions->_e("phone", $dictionary); ?></label>
                        <input type="text" id="user_phone" class="" minlength="0" placeholder="" value="<?php echo $memberInfo['phone']; ?>" />
                        <div class='tipholder'></div>
                    </div>
                    <div class='formfield half'>
                        <label><?php echo $this->functions->_e("fax", $dictionary); ?></label>
                        <input type="text" id="user_fax" class="" minlength="0" placeholder="" value="<?php echo $memberInfo['fax']; ?>" />
                        <div class='tipholder'></div>
                    </div>
                    <div class='formfield half'>
                        <label><?php echo $this->functions->_e("web site", $dictionary); ?></label>
                        <input type="text" id="user_web" class="" minlength="0" placeholder="" value="<?php echo $memberInfo['web']; ?>" />
                        <div class='tipholder'></div>
                    </div>
                    <div class='form_message'></div>
                    <div class='formfield'>
                    	<button class='button'><?php echo $this->functions->_e("save", $dictionary); ?></button>
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
	initializeForm("#profile_basic_info", saveBasicProfile);
	initializeForm("#profile_contact_info", saveLocation);
	swapState();
	
	$("#user_country").change(function(){
		swapState();	
	});
});

function fieldexists(fieldType, fieldValue, targetField){
	$.post(site_url + "app/attributeExists/", {
					'fieldType' : fieldType,
					'fieldValue' : fieldValue,
					'userId' : <?php echo $memberId; ?>
					},
				   function(data){
					   if(data.success){validateExistField(targetField, data.no_iterations)}
					  }, "json");
}

function saveBasicProfile(){
	var first_name = $("#user_first_name").val();
	var last_name = $("#user_last_name").val();
	var user_title = $("#user_title").val();
	var username = $("#user_username").val();
	var user_email = $("#user_email").val();
	var industries = $("#user_industry").val();
	var biography = $("#user_bio").val();
	
	$.post(site_url + "app/saveMemberBasicInfo/", {
					'member_id' : <?php echo $memberId; ?>,
					'first_name' : first_name,
					'last_name' : last_name,
					'industries' : industries,
					'user_title' : user_title,
					'user_email' : user_email,
					'username' : username,
					'biography' : biography
					},
				   function(data){
					  if(data.success){
						 showMainMessage("Changes saved", false);
						} else {
							showMainMessage("There was a problem.", true);
						}
					   
					  }, "json");
	
}

function saveLocation(){
	var country = $("#user_country").val();
	var state = "";
	if(country == 'US'){state = $("#user_state").val();}
	if(country == 'CA'){state = $("#user_province").val();}
	var city = $("#user_city").val();
	var zip_code = $("#zip_code").val();
	var phone = $("#user_phone").val();
	var fax = $("#user_fax").val();
	var web_site = $("#user_web").val();
	
	
	$.post(site_url + "app/saveMemberContactInfo/", {
					'member_id' : <?php echo $memberId; ?>,
					'user_country' : country,
					'user_state' : state,
					'user_city' : city,
					'zip_code' : zip_code,
					'user_phone' : phone,
					'user_fax' : fax,
					'user_web' : web_site
					},
				   function(data){
					  if(data.success){
						 showMainMessage("Changes saved", false);
						} else {
							showMainMessage("There was a problem.", true);
						}
					   
					  }, "json");
}

function swapState(){
	var sel_country = $("#user_country").val();
	
	if(sel_country == 'CA'){
		$("#user_state").parent().hide(); $("#user_province").parent().show();
	} else if(sel_country == 'US') {
		$("#user_state").parent().show(); $("#user_province").parent().hide();
	} else {
		$("#user_state").parent().hide(); $("#user_province").parent().hide();
	}
}
</script>
