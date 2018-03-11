$(document).ready(function(){
	//$(".magic_margin").removeClass('off');
	initAnimations();
	
	
	
});
function drawCharts(){

	setTimeout(function(){
		var noCharts = $('.charts .chart').size();
		var ctx = new Array();
		
		if(noCharts > 0){
			for(var i = 0; i < noCharts; i++){
				
				var dataString = $('.charts .chart').eq(i).attr('val');
				var dataArray = dataString.split(',');
				
				var data = [
					{
						value: 3.6 * dataArray[0],
						color:"#FFFFFF"
					},
					{
						value : 3.6 * dataArray[1],
						color : "rgba(255,255,255,0.1)"
					}
				
				]
				ctx[i] = new Object();
				ctx[i] = $(".charts .chart canvas:eq(" + i + ")").get(0).getContext("2d");
				var chartDelay = i * 500;
				new Chart(ctx[i]).Doughnut(data, {percentageInnerCutout : '85', animationSteps : 50, animationEasing : "easeOutQuart", segmentStrokeWidth : 1, segmentStrokeColor : "rgba(255,255,255,0)"});
				
				$(".charts .chart .percent:eq(" + i + ")").fadeIn(250);
			}
		}	
	}, 400);
	
}


function initializeBars(delayInterval){
	$("#tech_skills .tech_skill .bar_wrapper .dark_bar").css({"width" : 0});
	var no_bars = $("#tech_skills .tech_skill .bar_wrapper .dark_bar").size();
	
	for(var i = 0; i < no_bars; i++){
		var percent = parseFloat($("#tech_skills .tech_skill .bar_wrapper .dark_bar").eq(i).attr('data'));
		var max_width = $("#tech_skills .tech_skill .bar_wrapper .dark_bar").eq(i).parent().width();
		var abs_width = Math.round(max_width * percent / 100);
		
		$("#tech_skills .tech_skill .bar_wrapper .dark_bar").eq(i).delay(delayInterval * i).animate({width: abs_width + 'px'}, 500);
		
		
	}
}

// #skills_deck

function initAnimations(){
	$(window).on("scroll", function() {
	
		// Example 1
		if (isScrolledIntoView('#skills_deck')) {
			if(!$("#skills_deck").hasClass('initialized')){
				
				$(window).resize(function(){initializeBars(0);});
				setTimeout(drawCharts, 100);
			}
			
			$("#skills_deck").addClass('initialized');
			
		} 
		
		if (isScrolledIntoView('#technology_deck')) {
			if(!$("#technology_deck").hasClass('initialized')){
				setTimeout(function(){initializeBars(100);}, 100);
			}
			
			$("#technology_deck").addClass('initialized');
			
		} 
	
		
	
	});
}


function isScrolledIntoView(elem) {
    var $window = $(window),
        docViewTop = $window.scrollTop(),
        docViewBottom = docViewTop + $window.height(),
        elemTop = $(elem).offset().top,
        elemBottom = elemTop + $(elem).outerHeight();
    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}

