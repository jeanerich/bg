<div class="scroll_wrapper">
        	<div class="scroll_content">
            	<div class="sidebar_content">
                	<div class='site_logo'>
                    </div>
                    <div class='fullpadding40'>
                    <ul><?php if($userId > 0):  ?>
                    	<li><div class='submenu_wrapper'><a href='<?php echo $users[$userId]['link']; ?>' ><?php echo $this->functions->_e("my page", $dictionary); ?></a></div></li>
                        <li><div class='submenu_wrapper'><a href='<?php echo site_url() . "member/portfolio/{$userId}/" . urlencode($users[$userId]['name']); ?>' ><?php echo $this->functions->_e("my portfolio", $dictionary); ?></a></div></li>
                        <li><div class='submenu_wrapper'><a href='<?php echo site_url() . "member/followers/{$userId}/" . urlencode($users[$userId]['name']); ?>' ><?php echo $this->functions->_e("my fans", $dictionary); ?></a></div></li>
                        <li><div class='submenu_wrapper'><a href='<?php echo site_url() . "member/following/{$userId}/" . urlencode($users[$userId]['name']); ?>' ><?php echo $this->functions->_e("my favorites", $dictionary); ?></a></div></li>
                        <li><div class='submenu_wrapper'><a href='#' onclick="user_logout(); return false;"><?php echo $this->functions->_e("logout", $dictionary); ?></a></li>
                        <?php if($users[$userId]['admin_level'] > 4): ?>
                        <li><div class='submenu_wrapper'><a href='<?php echo site_url() . "admin/"; ?>' ><?php echo $this->functions->_e("admin", $dictionary); ?></a></div></li>
                        <?php endif; ?>
                        <?php else: ?>
                        <?php endif; ?>
                    </ul>
                    </div>
                </div>
            </div>
        </div>