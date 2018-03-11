<div class='w1200 paddingtop60'>
    	<h1><?php echo $this->functions->_e("translations", $dictionary); ?></h1>
        <div class='search_bar form'>
        	<form id="search_translation" class='clearfix'>
            	<div class='formfield half'>
                	<input type="text" id="search_translation_field" placeholder="Search..." value="<?php echo $translations['string']; ?>" />
                </div>
                <div class='formfield half'>
                	<a href='#' class='button' onclick="searchTranslation(); return false;"><?php echo $this->functions->_e("search", $dictionary); ?></a> <?php if(strlen($translations['string']) > 0): ?><a href='<?php echo site_url() . "admin/translation/"; ?>' class='button'><?php echo $this->functions->_e("clear", $dictionary); ?></a><?php endif; ?> <a href='#' class='button darker' onclick="$('#translation_0').slideToggle(250); return false;"><?php echo $this->functions->_e("new translations", $dictionary); ?></a>
                </div>
            </form>
        </div>
        <div class='line'><?php
		$sfx = "";
		if(strlen($translations['string']) > 0){$sfx = "/?t=" . $translations['string'];}
		$page_config['base_url'] = site_url() . 'admin/translation/';
		$page_config['total_rows'] = $translations['no_translations'];
		$page_config['reuse_query_string'] = TRUE;
		$page_config['suffix'] = $sfx;
		$page_config['per_page'] = 30;
		
		$this->pagination->initialize($page_config);
		$pagination = $this->pagination->create_links();
		echo "<p class='pagination'>{$pagination}</p>";
		?>
        </div>
        <div id="translation_0" class="hidden">
        	<div class='edit_translation '>
                        <div class='line clearfix form paddingtop20 '>
                            <div class='unit size1of6'>
                                <div class='formfield'>
                                    <label>Key</label>
                                    <input type="text" class='field_translation_key' value="" />
                                </div>
                            </div>
                            <div class='unit size1of6'>
                                <div class='formfield'>
                                    <label>English</label>
                                    <input type="text" class='field_language_english' value="" />
                                </div>
                            </div>
                            <div class='unit size1of6'>
                                <div class='formfield'>
                                    <label>French</label>
                                    <input type="text" class='field_language_french' value="" />
                                </div>
                            </div>
                            <div class='unit size1of6'>
                                <div class='formfield'>
                                    <label>Spanish</label>
                                    <input type="text" class='field_language_spanish' value="" />
                                </div>
                            </div>
                            <div class='unit size1of6'>
                                <div class='formfield'>
                                    <label>Chinese Traditional</label>
                                    <input type="text" class='field_language_mandarin' value="" />
                                </div>
                            </div>
                            <div class='unit size1of6 rightalign'>
                                <a href='#' class='button' onclick="saveTranslation(0); return false;"><?php echo $this->functions->_e("save", $dictionary); ?></a>
                                
                            </div>
                        </div>
                    </div>
        </div>
        <div id="translation_list" >
        	<div class='list line'>
            	<?php if(count($translations['translations']) > 0): foreach($translations['translations'] as $t): ?>
                <div class='list_items padding20 ' id="translation_<?php echo $t['id']; ?>">
                	<div class='line clearfix'>
                        <div class='unit size1of4 clearfix'>
                            <div class='unit size1of2'>
                                <?php echo $t['dictionary_key']; ?>
                                
                            </div>
                            
                        </div>
                        <div class='unit size1of2 clearfix'>
                            <div class='unit size1of4'>
                                <?php echo $t['language_english']; ?>
                            </div>
                            <div class='unit size1of4'>
                                <?php echo $t['language_french']; ?>
                            </div>
                            <div class='unit size1of4'>
                                <?php echo $t['language_spanish']; ?>
                            </div>
                            <div class='unit size1of4'>
                                <?php echo $t['language_mandarin']; ?>
                            </div>
                        </div>
                        <div class='unit size1of4 rightalign'>
                            <a href='#' class='button' onclick="$('#translation_<?php echo $t['id']; ?> .edit_translation').slideToggle(250); return false; "><?php echo $this->functions->_e("edit", $dictionary); ?></a><a href='#' class='button darker' onclick="return false; "><?php echo $this->functions->_e("delete", $dictionary); ?></a>
                        </div>
                    </div>
                    <div class='edit_translation hidden'>
                        <div class='line clearfix form paddingtop20 '>
                            <div class='unit size1of6'>
                                <div class='formfield'>
                                    <label>Key</label>
                                    <input type="text" class='field_translation_key' value="<?php echo $t['dictionary_key']; ?>" />
                                </div>
                            </div>
                            <div class='unit size1of6'>
                                <div class='formfield'>
                                    <label>English</label>
                                    <input type="text" class='field_language_english' value="<?php echo $t['language_english']; ?>" />
                                </div>
                            </div>
                            <div class='unit size1of6'>
                                <div class='formfield'>
                                    <label>French</label>
                                    <input type="text" class='field_language_french' value="<?php echo $t['language_french']; ?>" />
                                </div>
                            </div>
                            <div class='unit size1of6'>
                                <div class='formfield'>
                                    <label>Spanish</label>
                                    <input type="text" class='field_language_spanish' value="<?php echo $t['language_spanish']; ?>" />
                                </div>
                            </div>
                            <div class='unit size1of6'>
                                <div class='formfield'>
                                    <label>Chinese Traditional</label>
                                    <input type="text" class='field_language_mandarin' value="<?php echo $t['language_mandarin']; ?>" />
                                </div>
                            </div>
                            <div class='unit size1of6 rightalign'>
                                <a href='#' class='button' onclick="saveTranslation(<?php echo $t['id']; ?>); return false;">Save</a>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
        <div class='line paddingbottom40'><?php
		$page_config['base_url'] = site_url() . 'admin/translation/';
		$page_config['total_rows'] = $translations['no_translations'];
		$page_config['reuse_query_string'] = TRUE;
		$page_config['suffix'] = $sfx;
		$page_config['per_page'] = 30;
		
		$this->pagination->initialize($page_config);
		$pagination = $this->pagination->create_links();
		echo "<p class='pagination'>{$pagination}</p>";
		?>
        </div>
    </div>
    <script>
	$(document).ready(function(){
		
		initializeForm("#search_translation", searchTranslation);
		
		/*$("#search_translation_field").on('keyup', function (e) {
			if (e.keyCode == 13) {
				searchTranslation();
			}
		});	*/	
	});
	
	function searchTranslation(){
		var searchstring = $("#search_translation_field").val();
		
		if(searchstring.length > 0){
			
			var targeturl = site_url + "admin/translation/?t=" + encodeURI(searchstring);	  //alert(targeturl); console.log(targeturl);
			window.location.href = targeturl;
		}	
	}
	// $_POST['dictionary_id']) && isset($_POST['key']) && isset($_POST['lang_english']) && isset($_POST['lang_french']) && isset($_POST['lang_spanish']) && isset($_POST['lang_mandarin']
	function saveTranslation(translationId){
		var t_key = $("#translation_" + translationId + " .field_translation_key").val();
		var l_english = $("#translation_" + translationId + " .field_language_english").val();
		var l_french = $("#translation_" + translationId + " .field_language_french").val();
		var l_spanish = $("#translation_" + translationId + " .field_language_spanish").val();
		var l_mandarin = $("#translation_" + translationId + " .field_language_mandarin").val();
		
		$.post(site_url + "admin/editDictionary/", {
					'dictionary_id' : translationId,
					'key' : t_key,
					'lang_english' : l_english,
					'lang_french' : l_french,
					'lang_spanish' : l_spanish,
					'lang_mandarin' : l_mandarin
					},
				   function(data){
					   if(data.success){
						var targeturl = site_url + "admin/translation/";	  //alert(targeturl); console.log(targeturl);
						window.location.href = targeturl;   
						}
					  }, "json");
		//console.log(t_key + " : " + l_english + " : " + l_french + " : " + l_spanish + " : " + l_mandarin);
	}
	</script>