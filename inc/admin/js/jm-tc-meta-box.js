// DOM ready
jQuery(document).ready(function($) {

	/* we do this not the simpliest way but it's readable. 
	   next time I'll add some checking for fields
	*/

	//hide
	function hideProduct(){
	   $( '.cmb_id_product_title').hide();
	   $( '.cmb_id_cardData1').hide();
	   $( '.cmb_id_cardLabel1').hide();
	   $( '.cmb_id_cardData2').hide();
	   $( '.cmb_id_cardLabel2').hide();
	}
	
	function showProduct(){
	   $( '.cmb_id_product_title').show(500);
	   $( '.cmb_id_cardData1').show(500);
	   $( '.cmb_id_cardLabel1').show(500);
	   $( '.cmb_id_cardData2').show(500);
	   $( '.cmb_id_cardLabel2').show(500);
	}

	function hideGallery(){ 
	   $( '.cmb_id_gallery_title').hide();
	}

	function showGallery(){
	   $( '.cmb_id_gallery_title').show(500);
	}

	function hidePlayer(){
	   $( '.cmb_id_player_title').hide();
	   $( '.cmb_id_cardPlayer').hide();
	   $( '.cmb_id_cardPlayerWidth').hide();
	   $( '.cmb_id_cardPlayerHeight').hide();
	   $( '.cmb_id_cardPlayerStream').hide();
	}

	function showPlayer(){
	   $( '.cmb_id_player_title').show(500);
	   $( '.cmb_id_cardPlayer').show(500);
	   $( '.cmb_id_cardPlayerWidth').show(500);
	   $( '.cmb_id_cardPlayerHeight').show(500);
	   $( '.cmb_id_cardPlayerStream').show(500);
	}

	hideProduct();
    hidePlayer();
	hideGallery();		

   	//see
	$( '#twitterCardType' ).on('change', function() {

		if ( this.value == 'summary' ) {
			hideProduct();
		    hidePlayer();
			hideGallery();		 
		    console.log('summary');
		} else if( this.value == 'summary_large_image' ){
			hideProduct();
		    hidePlayer();
			hideGallery();
		    console.log('summary large image');
		} else if( this.value == 'photo') { 
			hideProduct();
		    hidePlayer();
			hideGallery();
		    console.log('photo');  
		} else if( this.value == 'product' ){ 
			hideProduct();
		    hidePlayer();
			showProduct();
			console.log('product');
		} else if( this.value == 'player' ){ 
			hideGallery();
			hideProduct();
			showPlayer();
			console.log('player');
		}else if( this.value == 'gallery' ){
			hideProduct();
			hidePlayer();
		    showGallery();
		    console.log('gallery');
		}else if( this.value == 'application' ){
			hideProduct();
		    hidePlayer();
			hideGallery();
			console.log('application');
		}else{
			console.log('oO');
		}

	});

});
		
