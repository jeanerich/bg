<?php if(false): ?><div class='battle_billboard clearfix'>
	<div class='board' id='board1'>
    	<div id="bg_image" class='imagewrapper' style="background: url(http://www.cgforward.com/cgs/temp-content/bigimage<?php echo rand(1,6); ?>.jpg) no-repeat;" src='http://www.cgforward.com/cgs/temp-content/bigimage1.jpg'></div>
        <div class='overlay'></div>
        <div class='textwrapper'>
        	<div class='icon softblink'><div class='bar'></div></div>
            <h1><?php echo $this->functions->_e("weekly challenge", $dictionary); ?></h1>
            <h3><a href='#'><?php echo $this->functions->_e("enter battle", $dictionary); ?></a> | <a href='<?php echo site_url() . "battles/vote/"; ?>'><?php echo $this->functions->_e("vote", $dictionary); ?></a></h3>
            <a href='#' class='button'><?php echo $this->functions->_e("view", $dictionary); ?></a>
        </div>
    </div>
    
</div>
<?php endif; ?><div id="deck_1" class='line'>
	<div class='w1200 battle_list'>
    	<?php if($ongoing_battles['no_battles'] > 0): ?>
    	<h2><?php echo $this->functions->_e("ongoing battles", $dictionary); ?></h2>
        <?php $this->functions->listBattles($userId, $ongoing_battles, 'ongoing', $dictionary); ?>
        <?php endif; ?>
        <?php if($future_battles['no_battles'] > 0): ?>
        <h2><?php echo $this->functions->_e("upcoming battles", $dictionary); ?></h2>
        <?php $this->functions->listBattles($userId, $future_battles, 'future', $dictionary); ?>
        <?php endif; ?>
        <?php if($past_battles['no_battles'] > 0): ?>
        <h2><?php echo $this->functions->_e("past battles", $dictionary); ?></h2>
        <?php $this->functions->listBattles($userId, $past_battles, 'past', $dictionary); ?>
        <?php endif; ?>
    </div>
</div>