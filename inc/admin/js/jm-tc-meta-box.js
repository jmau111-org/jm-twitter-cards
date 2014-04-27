//cmb_id_preview_title

jQuery(document).ready(function () {
	
	$(document).ready(function(){

	$("#title").keypress(update);
	$("#title").keypress(update);
	
	});
		 
	function update(){     
			 
	var title = $("#title").val();
	var photo = $("#photo").val();
	
	
	
	
	$('#Displaytitle').html(title);
	$('#image').html('<img src="'+photo+'"/>');
	}


});