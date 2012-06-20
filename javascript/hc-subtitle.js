jQuery(function(){
	
	var mb = jQuery('#subtitle-metabox');

	if ( mb ) {
	
		var hcSubtitle = jQuery('#hc-subtitle');
		var title = jQuery('#title');

		hcSubtitle.insertAfter('#title');
		title.css('marginBottom', '3px');
	}

});