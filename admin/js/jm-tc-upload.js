jQuery(document).ready(function($) {
	//upload
   $('#twitterCardImageButton').click(function() {  
		formfield = $(this).siblings('#twitterCardImage');  
		preview = $(this).siblings('#twitterCardImage');  
		tb_show('', 'media-upload.php?type=image&TB_iframe=true');  
		window.send_to_editor = function(html) {  
			imgurl = $('img',html).attr('src');  
			classes = $('img', html).attr('class');  
			id = classes.replace(/(.*?)wp-image-/, '');  
			formfield.val(imgurl);  //get image url and copy to field
			preview.attr('src', imgurl);  
			tb_remove();  
		}  
		return false;  
	});  

	$('#twitterCardImageButtonReset').click(function() {  
		var defaultImage = $(this).parent().siblings('#twitterCardImage').text();  
		$(this).parent().siblings('#twitterCardImage').val('');  
		$(this).parent().siblings('#twitterCardImage').attr('src', defaultImage);  
		return false;  
	});  
}); 