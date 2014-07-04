// DOM ready
jQuery(document).ready(function($) {

		
		busy = $.ajax({
			url: ajaxurl,
			type: 'POST',
			data: form.serialize(),
			success: function( response ) {
				
				if( response == 'JM_TC_Options' ) {
					
			
					console.log('ok');
				}
				

			}
		});
			
		
	}
	
	return false;
	
});
		
