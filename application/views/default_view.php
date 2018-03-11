<?php include(DIR_TEMPLATES . "common/head.php"); ?>

<body<?php if($userId < 1){echo " class='logged_off' ";} ?>>
<div id="page_wrapper">
<?php if(isset($template)){include(DIR_TEMPLATES . $template);} ?>
</div>
<?php include(DIR_TEMPLATES . "common/main-header.php"); ?>
<?php include(DIR_TEMPLATES . "common/main-footer.php"); ?>
</body>
</html>