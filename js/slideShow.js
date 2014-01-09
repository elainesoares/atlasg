$(document).ready(function() {
	var currentPosition = 0;
	var slideWidth = 350;
	var slides = $('.slide');
	var numberOfSlides = slides.length;
	var slideShowInterval;
	var speed = 3000;
	
	//Assign a timer, so it will run periodically
	//slideShowInterval = setInterval(changePosition/*, speed*/);
	
	slides.wrapAll('<div id="slidesHolder"></div>')
	
	slides.css({ 'float' : 'left' });
	
	//set #slidesHolder width equal to the total width of all the slides
	$('#slidesHolder').css('width', slideWidth * numberOfSlides);
	
	$('#slideshow')
		.prepend('<span class="nav" id="leftNav">&lsaquo;</span>')
		.append('<span class="nav" id="rightNav">&rsaquo;</span>');
	
	manageNav(currentPosition);
	
	//tell the buttons what to do when clicked
	$('.nav').bind('click', function() {
		//determine new position
		currentPosition = ($(this).attr('id')=='rightNav')
		? currentPosition+1 : currentPosition-1;
		
		//hide/show controls
		manageNav(currentPosition);
		clearInterval(slideShowInterval);
		//slideShowInterval = setInterval(changePosition/*, speed*/);
		moveSlide();
	});
	
	function manageNav(position) {
		//hide left arrow if position is first slide
		if(position==0){ 
			$('#leftNav').hide() 
		}
		else { 
			$('#leftNav').show() 
		}
		
		//hide right arrow is slide position is last slide
		if(position==numberOfSlides-1){ 
			$('#rightNav').hide() 
		}
		else { 
			$('#rightNav').show() 
		}
	}
	
	/*changePosition: this is called when the slide is moved by the timer and NOT when the next or previous buttons are clicked*/
	function changePosition() {
		if(currentPosition == numberOfSlides - 1) {
			currentPosition = 0;
			manageNav(currentPosition);
		} 
		else {
			currentPosition++;
			manageNav(currentPosition);
		}
		//moveSlide();
	}
	
	//moveSlide: this function moves the slide 
	function moveSlide() {
		$('#slidesHolder')
			 .animate({'marginLeft' : slideWidth*(-currentPosition)});
	}
});