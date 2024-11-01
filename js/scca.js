<!-- colour pickers -->
jQuery('#scca_background_color').wpColorPicker();
jQuery('#scca_font_color').wpColorPicker();
jQuery('#scca_border_color').wpColorPicker();

<!-- refresh preview -->
jQuery('.refresh-preview').on("click", function() {

	<!-- extract updated values -->
	var strBackgroundColor 	= jQuery("#scca_background_color").val();
	var strFontColor 		= jQuery("#scca_font_color").val();
	var strBorderColor 		= jQuery("#scca_border_color").val();
	var strFontSize 		= jQuery("#scca_font_size").val();
	var strPadding 			= jQuery("#scca_padding").val();
	var strBorderWidth 		= jQuery("#scca_border_width").val();

	<!-- apply values to the preview -->
	jQuery('#scca_preview').css('background-color', strBackgroundColor);
	jQuery('#scca_preview').css('color', strFontColor);
	jQuery('#scca_preview').css('border-color', strBorderColor);
	jQuery('#scca_preview').css('font-size', strFontSize + 'px');
	jQuery('#scca_preview').css('padding', strPadding + 'px');
	jQuery('#scca_preview').css('border-width', strBorderWidth + 'px');

});