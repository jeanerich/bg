<?php 
if(!isset($is_invite)){ 
	if(site_url() == "https://www.battlegallery.com/" && $userId < 1){
		 if(isset($options)){ 
			if(isset($options['is_beta'])){
				if($options['is_beta'] == 'true'){ 
					if(isset($_COOKIE['beta_key']) && $_COOKIE['beta_key'] == $options['beta_key']){} else {
						redirect(site_url() . "user/splash/");
					}
				}	
			}
		}
	}
}
$la = "en";
$allowed_lang = array("english", "french", "spanish", "mandarin");
$languages = array("english" => "en", "french" => "fr", "spanish" => "sp", "mandarin" => "zh-Hans");
if(isset($_COOKIE['user_lang']) && in_array($_COOKIE['user_lang'], $allowed_lang)){$la = $languages[$_COOKIE['user_lang']]; }

 ?><!doctype html>
<html  lang="<?php echo $la; ?>">
<head><?php // if(isset($_COOKIE['user_beta_key']) && $_COOKIE['user_beta_key'] == BETA_KEY ){} else {if(!isset($is_public) ){redirect("/");}}  ?>
<meta charset="UTF-8">
<link rel="icon" type="image/png" href="<?php echo site_url(); ?>bg-1.ico"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
<?php if(site_url() == 'https://www.battlegallery.com/'): ?>
<link rel="stylesheet" href="https://use.typekit.net/fzg1hqy.css">
<?php else: ?>
<link rel="stylesheet" href="https://use.typekit.net/kzi3xzx.css">
<?php endif; ?>
<title><?php echo $page_title; ?></title>
<?php if(isset($opengraph)): foreach($opengraph as $key => $value): ?>
	<meta property="og:<?php echo $key; ?>" content="<?php echo $value; ?>" />
<?php endforeach; endif; ?>
<meta property="og:type" content="website" /> 
<meta property="og:url" content="<?php echo current_url(); ?>" />
<meta property="fb:app_id" content="<?php echo FACEBOOK_APP_ID; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo site_url() . DIR_STYLE ; ?>bg.css?id=<?php echo rand(1000,9999); ?>">
<?php if(isset($css) && count($css) > 0): foreach($css as $c):  ?>
<link rel="stylesheet" type="text/css" href="<?php echo site_url() . DIR_STYLE . $c; ?>">
<?php endforeach; endif; ?>
<?php if(!isset($_COOKIE['has_visited'])): ?>
<link rel="stylesheet" type="text/css" href="<?php echo site_url() . DIR_STYLE . "special/intro2.css"; ?>">
<?php endif; ?>
<script>var site_url = '<?php echo site_url(); ?>';</script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script><?php if($userId > 0): ?>
<script src="<?php echo site_url() . DIR_JS; ?>common/uploadifive/jquery.uploadifive.min.js"></script>
<script src="<?php echo site_url() . DIR_JS; ?>user.js"></script>
<?php else: ?>
<script src="<?php echo site_url() . DIR_JS; ?>main.js"></script>
<?php endif; ?>

<script src="<?php echo site_url() . DIR_JS; ?>common/form_validate.js"></script>
<script src="<?php echo site_url() . DIR_JS; ?>common/masonry.pkgd.min.js"></script>
<script src="<?php echo site_url() . DIR_JS; ?>common/imagesloaded.pkgd.min.js"></script>
<script src="<?php echo site_url() . DIR_JS; ?>common/app.js"></script>
<?php if(isset($js) && count($js) > 0): foreach($js as $j):  ?>
<script src="<?php echo site_url() . DIR_JS . $j; ?>"></script>
<?php endforeach; endif; ?>
<?php if($userId > 0): ?>
<script src="<?php echo site_url() . DIR_JS; ?>common/chat.js"></script><?php endif; ?>
<?php if(isset($editable) && $editable && isset($_GET['welcome'])): ?><script src="<?php echo site_url() . DIR_JS; ?>special/welcome.js"></script><?php endif; ?>
<!--<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo YOUR_GOOGLE_MAP_API_KEY; ?>&callback=initMap"></script>-->

<?php if(isset($admin)): ?>
<link rel="stylesheet" type="text/css" href="style/admin.css">
<script src="js/admin.js"></script>
<?php endif; ?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113914313-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-113914313-1');
</script>

<script>
var d = new Date("<?php echo date("Y-m-d H:i:s T", time()); ?>");
var server_time = d.getTime();
var GOOGLE_MAP_API_KEY = '<?php echo YOUR_GOOGLE_MAP_API_KEY; ?>';
var HOME = '<?php echo HOME; ?>';
var user_id = <?php echo $userId; ?>;


$(document).ready(function(){
	$("#tribe_promo_list").load(site_url + "app/getTribes/");

});

</script>
</head>
<?php if(isset($_GET['uref'])){
	$uref = (int)$_GET['uref'];	
	$this->User_model->setUserReference($userId, $uref);
}
?>