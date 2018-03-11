<style>
#site_intro{position: fixed; top: 0; left: 0; width: 100%; height: calc(100vh); background: #151515; z-index: 10; perspective: 400px; -webkit-font-smoothing: antialiased;}
#site_intro .axis1, #site_intro .axis2{
	width: 1px; height: 1px; position: absolute; top: calc(50%); left: calc(50%);
	transform: translateZ(-500px);  
}
#site_intro .axis1{transform: translatez(1000px); }
#site_intro .axis2{rotateZ(-15deg);}

#intro_background{position: absolute; top: 0; left: 0; width: 100%; height: calc(100vh); opacity: 1; transition: opacity 2s;}
#intro_background.visible{opacity: 1; transition: opacity 2s;}

#site_intro .axis1, #site_intro .axis2{
	width: 1px; height: 1px; position: absolute; top: calc(50%); left: calc(50%);
	 
}
#site_intro .axis1{opacity: 1; }

#site_intro .axis2{transform: translateZ(-30000px); }
#site_intro .axis2.moved{transition-timing-function: linear; transform: translatez(1000px); transition: all 60s linear; }

.diamond{position: absolute; width: 0px; height: 0px;}
.diamond .inner{position: absolute; top: calc(50% - 500px); left: calc(50% - 500px); opacity: 1;  width: 1000px; height: 1000px; border: solid #FFF 130px;  transition: opacity 1s; transform: rotate(45deg);}

.threed{
	-webkit-transform-style: preserve-3d;
    -moz-transform-style: preserve-3d;
    -ie-transform-style: preserve-3d;
    transform-style: preserve-3d;
}

.foreground{position: absolute; top: 0; left: 0; width: 100%; height: calc(100vh); background: #151515;}

.main_diamond_wrapper{position: absolute; width: 500px; height: 500px; top: calc(50% - 180px); left: calc(50% - 180px);  }
.main_diamond_wrapper .diamond_center{width: 500px; height: 500px;position: absolute; top: 0; left: 0; transform: rotate(45deg); background: #FFF; }
.main_diamond_wrapper .text_wrapper{ position: absolute; width: 500px; height: 100px; text-align: center; top: 210px; left:  0;  color: #151515;}

.main_diamond_wrapper .text_wrapper h1{color: #151515; text-transform: uppercase; letter-spacing: 0.2em; text-align: center; font-size: 60px!important; position: relative;  width: 500px; text-decoration: underline;display: none;}

</style><div id="site_intro">
<div id="intro_background" class="threed">
            <div class="axis1 threed ">
                <div class="axis2 threed ">
                <?php $offset = 0; for($i = 0; $i < 300; $i++): ?>
               		<div class=" threed diamond" style="transform: translate3d( -<?php echo $i * $offset = 0;; ?>px, -<?php echo $i * $offset = 0;; ?>px, <?php echo $i * 900; ?>px);"><div class='inner'></div></div>
               <?php endfor; ?>
               
                </div>
            </div>
            
        </div>
        <div class='foreground'></div>
    		<div class='main_diamond_wrapper '>
                <div class='diamond_center '></div>
                <div class='text_wrapper'><h1>Welcome</h1></div>
           </div>
</div>
<script>
$(document).ready(function(){
	$("#site_intro .axis2").addClass('moved');
	setTimeout(function(){$("#site_intro .foreground").fadeOut(2000);}, 1000);
	setTimeout(function(){$(".main_diamond_wrapper .text_wrapper h1").fadeIn(2000);}, 2000);
	setTimeout(function(){$("#site_intro").fadeOut(2000, function(){$("#site_intro").remove();});}, 7000);
	
});
</script>
<?php
	
	
	$cookie = array(
	                   'name'   => 'has_visited',
	                   'value'  => true,
	                   'expire' => '31536000',
	                   'path'   => '/',
	                   'prefix' => '',
						);
						
						set_cookie($cookie);
?>