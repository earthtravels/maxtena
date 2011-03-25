$(document).ready(function () {
	// Apply fancybox on all images
	$("a[href$='gif']").fancybox();
	$("a[href$='jpg']").fancybox();
	$("a[href$='png']").fancybox();		
		
	// Font replacement
	Cufon.replace('#logo .title');	
	Cufon.replace('h1');

	// Slider
	$('#slider .descriptions .description:first').show(); 
	$('#slider .slides').nivoSlider({
	    effect:                     'fade',
		slices:						15,
		animSpeed:					500,
		pauseTime: 					4000,
		startSlide:					0, //Set starting Slide (0 index)
		directionNav:				true, //Next & Prev
		directionNavHide:			false, //Only show on hover
		controlNav:					true, //1,2,3...
		controlNavThumbs:			false, //Use thumbnails for Control Nav
		controlNavThumbsSearch: 	'.jpg', //Replace this with...
		controlNavThumbsReplace: 	'_thumb.jpg', //...this in thumb Image src
		keyboardNav:				true, //Use left & right arrows
		pauseOnHover:				true, //Stop animation while hovering
		manualAdvance:				false, //Force manual transitions
		captionOpacity:				1.0, //Universal caption opacity
		beforeChange: 				function(){},
		afterChange: 				function(){
			currentSlide = $('#slider .slides').data('nivo:vars').currentSlide
			$('#slider .descriptions table').each(function (index, value) {
				if (index == currentSlide) {
					$('#slider .descriptions table').hide();
					$(this).show();
				}
			});
		},
		slideshowEnd: 				function(){} //Triggers after all slides have been shown
	});
	
	// Validate contact form
	$('#contact_form').validate();
		
	// AJAX contact form
	$('form#contact_form').submit(function() {
		if ($('label.error:visible').length !== 0) {
			return false;
		}
		
		var s = $(this).serialize();
		
		if (($(this).attr('action') === '') || ($(this).attr('action')==='#')) 
			action = '?'; 
		else 
			action = $(this).attr('action');

		$.ajax({
			type: $(this).attr('method'),
			data: s,
			url: action,
			success: function(result) {
				if (result == '1') {
					alert('E-mail sent');
					$('form#contact_form')[0].reset();
				}
				else { 
					alert('E-mail can not be sent!');
				}
				
				return false;
			}
		});	

		return false;
	});
	
	$('.slider .slides img').css({'visibility': 'visible'});	
});
