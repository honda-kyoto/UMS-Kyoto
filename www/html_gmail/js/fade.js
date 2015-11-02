jQuery(document).ready(function(){
	
	jQuery("a img,#button,#button5").hover(function(){
		jQuery(this).fadeTo(100,0.5);
	},
	function(){
		jQuery(this).fadeTo(100,1.0);
	});
	

});