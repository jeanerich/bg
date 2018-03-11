<div class='presentation'>
	<div class='logo_center'>
    </div>
    <h1><?php echo $this->functions->_e("welcome", $dictionary); ?></h1>
</div>
<div id="confirm_form" class='w1200 paddingtop60 centeralign hidden'>
	<div class='w320'>
        <h1><?php echo $this->functions->_e("confirm your invite", $dictionary); ?></h1>
       
        <div class=' paddingtop20 '>
             <p><?php echo $this->functions->_e("you were invited by", $dictionary); ?></p>
             
             <div class="tile ">
                    	<div class="mini_hero" style="background: url(<?php if($invite['users']['users'][$invite['invite']['from_user_id']]['hero_id'] > 0){echo $invite['users']['images'][$invite['users']['users'][$invite['invite']['from_user_id']]['hero_id']]['sizes']['thumb'];} ?>) no-repeat;"></div>
                        <a href="http://localhost:8888/bg/member/home/3/John+Gabriel"><div class="thumb" style="background: url(<?php if($invite['users']['users'][$invite['invite']['from_user_id']]['avatar_id'] > 0){echo $invite['users']['images'][$invite['users']['users'][$invite['invite']['from_user_id']]['avatar_id']]['sizes']['thumb'];} ?>) no-repeat;"></div></a>
                        <div class="text">
                            <p class="title"><a href="<?php echo $invite['users']['users'][$invite['invite']['from_user_id']]['link']; ?>"><?php echo $invite['users']['users'][$invite['invite']['from_user_id']]['name']; ?></a></p>
                            <p class="staff"><?php echo $invite['users']['users'][$invite['invite']['from_user_id']]['title']; ?></p>
                            
                        </div>
                        <div class="tile_nav">
                        <a href="<?php echo $invite['users']['users'][$invite['invite']['from_user_id']]['link']; ?>" class="button">Voir</a>
                        </div>
                    </div>
                    
                    <div class='form dark paddingtop40 paddingbottom40'>
                    	<form id="invitation_form" class='clearfix'>
                        	<p class='explanation'><?php echo $this->functions->_e("the information below was entered by", $dictionary); ?> <?php echo $invite['users']['users'][$invite['invite']['from_user_id']]['first_name']; ?>. <?php echo $this->functions->_e("please verify that the information provided is accurate", $dictionary); ?></p>
                            <input type="hidden" id="invite_id" value="<?php echo $invite['invite']['invite_id']; ?>" />
                            <input type="hidden" id="invite_token" value="<?php echo $invite['invite']['token']; ?>" />
                        	<div class='formfield'>
                            	<label><?php echo $this->functions->_e("first name", $dictionary); ?></label>
                                <input type="text" id="invite_first_name" class="required" value="<?php echo $invite['invite']['first_name']; ?>" />
                            </div>
                            <div class='formfield'>
                            	<label><?php echo $this->functions->_e("last name", $dictionary); ?></label>
                                <input type="text" id="invite_last_name" class="required" value="<?php echo $invite['invite']['last_name']; ?>" />
                            </div>
                            <div class='formfield'>
                            	<label><?php echo $this->functions->_e("email", $dictionary); ?></label>
                                <input type="text" id="invite_email" class='required email' value="<?php echo $invite['invite']['email']; ?>" />
                            </div>
                            <div class='formfield'>
                            	<label><?php echo $this->functions->_e("password", $dictionary); ?></label>
                                <p class='sublabel'><?php echo $this->functions->_e("8 characters minimum", $dictionary); ?></p>
                                <input type="password" class='required' id="invite_password"  minlength="8" value="" />
                            </div>
                            <div class='formfield'>
                            	<button class='button'><?php echo $this->functions->_e("register", $dictionary); ?></button>
                            </div>
                        </form>
                    </div>
                   
        </div>
    </div>
    
    
    
</div>