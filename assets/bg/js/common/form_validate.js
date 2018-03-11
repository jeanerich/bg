function initializeForm(targetForm, successFunction){
	var no_fields = $(targetForm + ' .formfield').size();
	
	$(targetForm + ' .formfield input').blur(function(){
		var targetField = $(this);
		testField(targetField);
	});
	
	$(targetForm).submit(function(){
		
		var errors = 0;
		var no_fields = $('input', targetForm).size();
		for(var i = 0; i < no_fields; i++){
			errors += testField($('input', targetForm).eq(i));	
			
		}
		
		if(errors > 0){
			if($(targetForm + ' .form_message').size() > 0){
				$(targetForm + ' .form_message').slideDown(500);
				return false;
			}
			return false;	
		} else {
			
			
			$(targetForm + 'button').attr('disabled','disabled');
			// SUBMIT FORM	
			successFunction();
			return false;
		}
		
		
	});
}

function testField(targetField, checkExists = true){
	var targetParent = targetField.parent();
	var data = new Array();
	data['error'] = 0;
	data['error_message'] = new Array();
	
	var fieldContent = targetField.val();
	
	if(targetField.hasClass('required')){
		if(fieldContent.length < 1){data['error']++; data['error_message'][data['error']] = "<p>This is a required field.</p>"; }
	}
	
	if(targetField.hasClass('email')){
		if(!validateEmail(fieldContent)){data['error']++; data['error_message'][data['error']] = "<p>Invalid Email.</p>";}
	}
	
	if(targetField.attr('minlength')){ 
		var minLength = parseFloat(targetField.attr('minlength')); 
		if(fieldContent.length < minLength){data['error']++; data['error_message'][data['error']] = "<p>A minimum of " + minLength + " character(s) required.</p>"; }
	} 
	
	if(targetField.attr('sameas')){ 
		var sameas = targetField.attr('sameas'); 
		var targetValue = $('#' + sameas).val();
		if(fieldContent != targetValue){data['error']++; data['error_message'][data['error']] = "<p>This field doesn't match.</p>";}
	} 
	if(checkExists){
		if(targetField.hasClass('exists')){ 
			var attributeType = targetField.attr('exists');
			var attributeValue = targetField.val();
			var tField = targetField.attr('id');
			
			fieldexists(attributeType, attributeValue, tField);
			
			
		}
	}
	
	if(data['error'] < 1 && targetField.hasClass('exists')){
		$('.spinner', targetParent).fadeIn(250).delay(1500).fadeOut(250);
	}
	
	
		
	if(data['error'] > 0){
		var errorString = data['error_message'].join("");
		$('.tipholder', targetParent).html("<div class='tooltip'><p>" + errorString + "</p></div>");
		targetParent.addClass('error');	
	} else {
		targetParent.removeClass('error');	
	}
	
	
	
	return data['error'];
}

function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 

function validateExistField(targetField, noIterations){
	$('#' + targetField).attr({'iterations' : noIterations});
	var targetParent = $('#' + targetField).parent();
	if(noIterations > 0){
		var error_message = "<p>This already in use. Please use something else.</p>";
		
		
		$('.tipholder', targetParent).html("<div class='tooltip'><p>" + error_message + "</p></div>");
		targetParent.addClass('error');
		
	} else {
		$('.tipholder', targetParent).html("");
		targetParent.removeClass('error');
		testField($("#" + targetField), false);
	}
}

