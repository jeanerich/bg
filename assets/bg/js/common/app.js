$(document).ready(function(){
	$("#side_menu").mouseleave(function(){
		$("#side_menu").removeClass('open');
	});
});

function setReturnLink(){
	var d = new Date();
    d.setTime(d.getTime() + (1000*60*20));
    var expires = "expires="+ d.toUTCString();
    document.cookie = 'return_link' + "=" + window.location.href + ";" + expires + ";path=/";
}


function createCookie(name, value, days) {
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                var expires = "; expires=" + date.toGMTString();
            }
            else var expires = "";               

            document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') c = c.substring(1, c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
	}
	return null;
}

function fs_modal(target_url){
	$('#fs_modal .modal_wrapper').load(site_url + target_url);
	$("#fs_modal_mask").fadeIn(500);
	$("#fs_modal").removeClass("closed");
}

function close_fs_modal(){
	
	$("#fs_modal").addClass("closed");
	$("#fs_modal_mask").fadeOut(500);
}

function toggleRightMenu(){
	$("#side_menu").toggleClass('open');
}

function swapLanguage(lang){
	
	
	var d = new Date();
    d.setTime(d.getTime() + (7*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = "user_lang=" + lang + ";" + expires + ";path=/";
	location.reload();
}

function user_logout(){
	$.post(site_url + "app/user_logout/", {
					'key' : 0
					},
				   function(data){
					   if(data.success){
						   clearLocal(); // clears local storage.
						   window.location.replace(HOME);
						  }
					  }, "json");
}