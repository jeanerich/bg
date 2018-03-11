var parsed_address = new Object();
var gMapsLoaded = false;
var popUpContent = "";
var lastinfowindow = 0;
var infowindow = new Array();
$(document).ready(function(){
	
});

function geoAddress(target_address, onCompleteFunction){
	'use strict';
	if(target_address.length > 0){
		var queryUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=" + encodeURI(target_address) + "&key=" + GOOGLE_MAP_API_KEY; 
	
		
		
		parsed_address.status = 'FAIL';
		
		  
		  $.ajax({
			url: queryUrl,
			async: true,
			dataType: 'json',
			success: function(data) {
			  var q_results = data.results;
			  if(data.status === 'OK'){
				  	var address_components = q_results[0].address_components;
					var components = {}; 
					jQuery.each(address_components, function(k,v1) {jQuery.each(v1.types, function(k2, v2){components[v2]=v1.long_name});});

					parsed_address.status = 'OK';
				  	parsed_address.latitude = q_results[0]['geometry']['location']['lat'];
				  	parsed_address.longitude = q_results[0]['geometry']['location']['lng'];
				  	parsed_address.city = components['locality'];
					parsed_address.country = components['political'];
					parsed_address.zip_code = components['postal_code'];
					parsed_address.state = components['administrative_area_level_1'];
					
					onCompleteFunction();
				}
			}
		  });
		  
	  }
}


function loadGoogleMaps(onLoadAction) { if(!gMapsLoaded) { 
	$.getScript("https://maps.googleapis.com/maps/api/js?sensor=false&async=2&callback=" + onLoadAction + "&key=" + GOOGLE_MAP_API_KEY, function(){}); } else { onLoadAction(); } 

}

function GoogleMapsLoaded() {

   
   initMap();
}


function showMap(targetElement, coordinates, locations, showCenter = true){
	
	var map = new google.maps.Map(document.getElementById(targetElement), {
	  zoom: 10,
	  center: coordinates,
	  
	  styles: [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#d5d7d5"},{"visibility":"on"}]}]
	});
	
	if(showCenter){
		var marker = new google.maps.Marker({
		  position: coordinates,
		  map: map,
		  
		});
	}
	
	
	
	
	 
	
	if(locations.length > 0){
		var marker, i;

		for (i = 0; i < locations.length; i++) {  
		  marker = new google.maps.Marker({
			position: new google.maps.LatLng(locations[i][1], locations[i][2]),
			map: map,
			title: locations[i][0]
			
		  });
		  
		  
		  infowindow[i] = new google.maps.InfoWindow({
			  content: locations[i][4]
		 });
	
		  google.maps.event.addListener(marker, 'click', (function(marker, i) {
			return function() {
			  //alert(locations[i][0]);
			  if(infowindow[lastinfowindow]){
				 infowindow[lastinfowindow].close(); 
				 }
			  
			  infowindow[i].open(map, marker);
			  lastinfowindow = i;
			}
		  })(marker, i));
		}
	}
	
	
	
	
	
}