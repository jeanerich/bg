<div class="modal_mini_panel upload centeralign welcome_page">
        <div class='heart'></div><?php  // print_r($users); ?>
<h1><?php echo $this->functions->_e("welcome", $dictionary)  . " " . $users['users'][$userId]['first_name']; ?></h1>
<p><?php echo $this->functions->_e("we welcome you to our community", $dictionary); ?></p>
<p><a href='#' class='button' onclick="close_fs_modal(); return false;"><?php echo $this->functions->_e("continue", $dictionary); ?></a></p>



</div>
<style>

</style>