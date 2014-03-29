/*
 A little bit of jQuery AJAX to make saving more comfortable
 Source : http://codex.wordpress.org/AJAX_in_Plugins
 I'm not an expert in AJAX, not yet ^^, feel free to improve it and share your worl please
*/

jQuery(document).ready(function($) {
// Save options
	function jm_tc_options_ajax() {
		   $('#jm-tc-form').submit( function () {
				
				//get values from options page
				var getArr =  $(this).serialize();
				
				$.post( 'options.php', getArr ).error( 
					function() {
						console.log('error');//for debug purpose
					}).success( function() {
						console.log('success'); //for debug purpose 
					});
					return false;    
				});
			}
			jm_tc_options_ajax();
			
//Show further options for app install & deep linking
	if ($("#twitterCardDeepLinking").val() == 'yes') {
		$('#further-deep-linking').show();  
	 } else { 
		$('#further-deep-linking').hide(); 
	}
	
	$('#twitterCardDeepLinking').bind("change",function(){
		if ($(this).val() == 'yes') {
		   $('#further-deep-linking').show(400);
		} else {
		   $('#further-deep-linking').hide(400);
		}

	});

// Display text
	$('#jm-tc-form').submit(function(e) {
	
		e.preventDefault();	
		$('.form-loading').fadeIn('slow');
		$('.submit').attr('disabled', true);

		// $.ajax
		var data = {
				action                : 'jm-tc-ajax-saving',
				_tc_ajax_saving_nonce : jmTcObject._tc_ajax_saving_nonce
			};
			// jmTcObject.ajaxurl is a variable that will contain the url to the ajax processing file
			$.post( jmTcObject.ajaxurl, data, function(response) {
				$('.form-loading').fadeOut('slow');
				$('.submit').attr('disabled', false);
				$('.notification').text( response );
				$('.notification').fadeIn(1800).delay(3600).fadeOut(1800);
			});

		});

});