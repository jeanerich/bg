<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo SITE_NAME; ?></title>
<link rel="icon" type="image/png" href="<?php echo site_url(); ?>bg.ico"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
<?php if(isset($opengraph)): foreach($opengraph as $key => $value): ?>
	<meta property="og:<?php echo $key; ?>" content="<?php echo $value; ?>" />
<?php endforeach; endif; ?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<style>
body, html{padding: 0; margin: 0; width: 100%: height: 100vh; background: #333; color: white;}
body{display: none;}
#board_1 {
    height: 100%;
    display: block;
    position: absolute;
	width: 100%; 
	top: 0; left: 0; height: calc(100vh);
    background: #252525;
}

#board_1 .bg_grid {
    
}

#diamond{width: 300px; height: 300px; display: inline-block; position: absolute; top: calc(50% - 150px); left: calc(50% - 150px); background: url(../../assets/bg/css/images/brand/logo-bg-diamond-empty-white.svg) no-repeat; background-size: cover; background-position: center center; }

@media(max-width: 400px){
	#diamond{width: 250px; height: 250px; left: calc(50% - 125px); top: calc(50% - 125px);}
}


.blink1{
	animation-name: blink;
	animation-duration: 250ms;
	animation-iteration-count: infinite;
	animation-timing-function: linear;
}

.blink2{
	animation-name: blink2;
	animation-duration: 1000ms;
	animation-iteration-count: infinite;
	animation-timing-function: linear;
}

.blink3{
	animation-name: blink3;
	animation-duration: 1000ms;
	animation-iteration-count: infinite;
	animation-timing-function: linear;
}

.softblink{
	animation-name: softblink;
	animation-duration: 2000ms;
	animation-iteration-count: infinite;
	animation-timing-function: linear;
}

.appear1{
	animation-name: blink3;
	animation-duration: 250ms;
	animation-timing-function: linear;
	
}

@keyframes blink{
	0%{opacity: 0;}
	49%{opacity: 0;}
	50%{opacity: 1;}
	100%{opacity: 1;}
}


@keyframes blink1{
	0%{opacity: 0;}
	5%{opacity: 0;}
	6%{opacity: 1;}
	10%{opacity: 1;}
	11%{opacity: 0;}
	15%{opacity: 0;}
	26%{opacity: 1;}
	30%{opacity: 1;}
	31%{opacity: 0;}
	35%{opacity: 0;}
	36%{opacity: 1;}
	80%{opacity: 1;}
	81%{opacity: 0;}
	100%{opacity: 0;}
}

@keyframes blink2{
	0%{opacity: 0.3;}
	5%{opacity: 0.3;}
	6%{opacity: 0;}
	10%{opacity: 0;}
	11%{opacity: 0.3;}
	100%{opacity: 0.3;}
}

@keyframes blink3{
	0%{opacity: 1;}
	5%{opacity: 1;}
	6%{opacity: 0;}
	10%{opacity: 0;}
	11%{opacity: 1;}
	100%{opacity: 1;}
}

@keyframes softblink{
	0%{opacity: 1;}
	50%{opacity: 0;}
	100%{opacity: 1;}
}

@keyframes appear1{
	0%{opacity: 1;}
	50%{opacity: 0;}
	100%{opacity: 1;}
}



</style>
<script>
$(document).ready(function(){
	setTimeout(function(){$("body").fadeIn(2000);}, 1000);
	setTimeout(function(){$("#diamond").saddClass('appear1');}, 3000);
});
</script>
</head>

<body>
<div id="board_1"><div class="bg_grid"></div></div>

<div id="diamond" class="softblink"></div>
</body>
</html>