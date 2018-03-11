<ul id="profile_menu" class="clearfix"><?php $url_member_string = $memberId . "/" . urlencode($memberInfo['name']) . "/"; $url_type = "member"; ?>
             <li <?php if($menu_option == 'home'){echo " class='active'";} ?>><a href='<?php echo site_url() . "{$url_type}/home/" . $url_member_string; ?>'>
			 <?php echo $this->functions->_e("profile", $dictionary); ?></a></li>
            <li<?php if($menu_option == 'portfolio'){echo " class='active'";} ?>><a href='<?php echo site_url() . "{$url_type}/portfolio/" . $url_member_string; ?>'><?php echo $this->functions->_e("portfolio", $dictionary); ?></a></li>
            <?php if(false): ?><li<?php if($menu_option == 'profile'){echo " class='active'";} ?>><a href='<?php echo site_url() . "{$url_type}/blog/" . $url_member_string; ?>'><?php echo $this->functions->_e("blog", $dictionary); ?></a></li><?php endif; ?>
            <li<?php if($menu_option == 'followers'){echo " class='active'";} ?>><a href='<?php echo site_url() . "{$url_type}/followers/" . $url_member_string; ?>'><?php echo $this->functions->_e("followers", $dictionary); ?></a></li>
            <li<?php if($menu_option == 'following'){echo " class='active'";} ?>><a href='<?php echo site_url() . "{$url_type}/following/" . $url_member_string; ?>'><?php echo $this->functions->_e("following", $dictionary); ?></a></li>
        	<?php if($editable): ?><li<?php if($menu_option == 'invites'){echo " class='active'";} ?>><a href='<?php echo site_url() . "{$url_type}/invites/" . $url_member_string; ?>'><?php echo $this->functions->_e("invites", $dictionary); ?></a></li><?php endif; ?>
        
        </ul>