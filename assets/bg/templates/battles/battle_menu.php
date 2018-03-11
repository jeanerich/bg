<ul id="" class="secondary_menu clearfix">         
<li <?php if($current_menu == 'battle'){echo "class='active'"; } ?>><a href="<?php echo site_url() . "battles/view/{$battle_id}/" . urlencode($battle['battle']['battle_name']); ?>"><?php echo $this->functions->_e("battle", $dictionary); ?></a></li>
                        <li <?php if($current_menu == 'warriors'){echo "class='active'"; } ?>><a href="<?php echo site_url() . "battles/warriors/{$battle_id}/" . urlencode($battle['battle']['battle_name']); ?>"><?php echo $this->functions->_e("warriors", $dictionary); ?></a></li>
            <li <?php if($current_menu == 'entries'){echo "class='active'"; } ?>><a href="<?php echo site_url() . "battles/entries/{$battle_id}/" . urlencode($battle['battle']['battle_name']); ?>"><?php echo $this->functions->_e("entries", $dictionary); ?></a></li>
        
        
        </ul>