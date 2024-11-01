<?php
/*
Plugin Name: Simple Custom Content Adder
Plugin URI: http://www.davidsneal.co.uk/wordpress/simple-custom-content-adder
Description: A simple plugin that enables you to add some custom content to all of your posts and/or pages.
Version: 1.1
Author: David S. Neal
Author URI: http://www.davidsneal.co.uk/
License: GPLv2

Copyright 2013 David S Neal me@davidsneal.co.uk

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

	// --------- INSTALLATION ------------ //

	// run the activation function upon acitvation of the plugin
	register_activation_hook( __FILE__, 'scca_activate' );
	
	// activate scca function
	function scca_activate() {
	
		// globals
		global $wpdb;
		
		// insert default options for scca
		add_option('scca_content', 			'Simple Custom Content Adder');
		add_option('scca_background_color', '#F3F3F7');
		add_option('scca_font_color', 		'#1a1a1a');
		add_option('scca_font_size', 		'15');
		add_option('scca_padding', 			'10');
		add_option('scca_border_color', 	'#DEDEE3');
		add_option('scca_border_width', 	'1');
		add_option('scca_rounded_corners', 	'Y');
		add_option('scca_before_or_after', 	'before');
		add_option('scca_posts_or_pages', 	'both');
	}

	// --------- ADMIN BITS ------------ //
	
	// add menu to dashboard
	add_action( 'admin_menu', 'scca_menu' );

	// menu settings
	function scca_menu() {
	
		// add menu page
		add_plugins_page( 'Simple Custom Content Adder', 'Custom Content', 'manage_options', 'simple-custom-content-adder', 'scca_settings');
	}

	// --------- SETTINGS PAGE ------------ //
		
	// call scripts add function
	add_action( 'admin_enqueue_scripts', 'scca_add_scripts' );
	
	// add js scripts
	function scca_add_scripts() {
	
		// load js scripts
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'my-script-handle', plugins_url('js/scca.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	}
	
	// answer form
	function scca_settings() {
	
		//' check if user has the rights to manage options
		if ( !current_user_can( 'manage_options' ) )  {
		
			// permissions message
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		
		// globals
		global $wpdb;
	
		// check for submitted form
		if (isset($_POST['scca_options'])) {

			// update existing scca settings
			update_option('scca_content', 			stripslashes_deep($_POST['scca_content']));
			update_option('scca_background_color', 	($_POST['scca_background_color']));
			update_option('scca_font_color', 		($_POST['scca_font_color']));
			update_option('scca_font_size', 		($_POST['scca_font_size']));
			update_option('scca_padding', 			($_POST['scca_padding']));
			update_option('scca_border_color', 		($_POST['scca_border_color']));
			update_option('scca_border_width', 		($_POST['scca_border_width']));				
			update_option('scca_border_width', 		($_POST['scca_border_width']));				
			update_option('scca_rounded_corners', 	($_POST['scca_rounded_corners']));				
			update_option('scca_before_or_after', 	($_POST['scca_before_or_after']));	

			// add the option if using version 1.0
			add_option('scca_posts_or_pages', 	'both');			
			update_option('scca_posts_or_pages', 	($_POST['scca_posts_or_pages']));				

			// show settings saved message
			echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>Settings saved.</strong></p></div>';
		}
		
	
		// query the db for current scca settings
		$arrSettings = $wpdb->get_results("SELECT option_name, option_value
											 FROM $wpdb->options 
											WHERE option_name LIKE 'scca_%'");
		
		// loop through each setting in the array
		foreach ($arrSettings as $setting) {
		
			// add each setting to the array by name
			$arrSettings[$setting->option_name] =  $setting->option_value;
		}
		
		// extract settings and add to user-friendly variables
		$htmlCustomContent 		= $arrSettings['scca_content'];
		$strBackgroundColour 	= $arrSettings['scca_background_color'];
		$strFontColour 			= $arrSettings['scca_font_color'];
		$intFontSize 			= $arrSettings['scca_font_size'];
		$intPadding 			= $arrSettings['scca_padding'];
		$strBorderColour 		= $arrSettings['scca_border_color'];
		$intBorderWidth 		= $arrSettings['scca_border_width'];
		$booRoundedCorners 		= $arrSettings['scca_rounded_corners'];
		$strBeforeOrAfter 		= $arrSettings['scca_before_or_after'];
		$strPostsOrPages 		= $arrSettings['scca_posts_or_pages'];
		
		// check if user has version 1.0
		if ($strPostsOrPages == '') {
		
			// create post or pages option and set to both
			add_option('scca_posts_or_pages', 	'both');
		}

		// get css
		echo get_scca_css($strBackgroundColour, $strFontColour, $intFontSize, $intPadding, $strBorderColour, $intBorderWidth, $booRoundedCorners, $strPostsOrPages);
		
		// html form
		echo '<div class="wrap">';
			echo '<img src="' . plugins_url( 'images/scca.png' , __FILE__ ) . '" align="left" style="margin-right: 10px;" alt="scca" /> ';
			echo '<h2>Simple Custom Content Adder</h2>';
			echo '<form method="post">';
			
			// hidden field to check for post
			echo '<input type="hidden" name="scca_options" id="scca_options" value="save" />';
			
				// continue outputting the form
				echo '<table class="form-table">';
					echo '<tr valign="top">';
						echo '<th scope="row" style="width: 120px;"><label for="scca_content">Content:&nbsp;</label></th>';
						echo '<td>';
						wp_editor($htmlCustomContent,'scca_content', array( 'textarea_name' => 'scca_content' ));
						echo '</td>';
					echo '</tr>';
					echo '<tr valign="top">';
						echo '<th scope="row" style="width: 120px;"><label for="scca_background_color">Background Colour:&nbsp;</label></th>';
						echo '<td><input type="text" name="scca_background_color" id="scca_background_color" value="' . $strBackgroundColour . '">';
						echo '</input></td>';
					echo '</tr>';
					echo '<tr valign="top">';
						echo '<th scope="row" style="width: 120px;"><label for="scca_font_color">Font Colour:&nbsp;</label></th>';
						echo '<td><input type="text" name="scca_font_color" id="scca_font_color" value="' . $strFontColour . '">';
						echo '</input></td>';
					echo '</tr>';
					echo '<tr valign="top">';
						echo '<th scope="row" style="width: 120px;"><label for="scca_border_color">Border Colour:&nbsp;</label></th>';
						echo '<td><input type="text" name="scca_border_color" id="scca_border_color" value="' . $strBorderColour . '">';
						echo '</input></td>';
					echo '</tr>';
					echo '<tr valign="top">';
						echo '<th scope="row" style="width: 120px;"><label for="scca_font_size">Font Size:&nbsp;</label></th>';
						echo '<td><input type="number" name="scca_font_size" id="scca_font_size" value="' . $intFontSize . '"><span class="description">px</span>';
						echo '</input></td>';
					echo '</tr>';
					echo '<tr valign="top">';
						echo '<th scope="row" style="width: 120px;"><label for="scca_padding">Padding:&nbsp;</label></th>';
						echo '<td><input type="number" name="scca_padding" id="scca_padding" step="1" min="0" value="' . $intPadding . '" /><span class="description">px</span>';
						echo '</td>';
					echo '</tr>';
					
					echo '<tr valign="top">';
						echo '<th scope="row" style="width: 120px;"><label for="scca_border_width">Border Width:&nbsp;</label></th>';
						echo '<td><input type="number" name="scca_border_width" id="scca_border_width" value="' . $intBorderWidth . '"><span class="description">px</span>';
						echo '</input></td>';
					echo '</tr>';
					echo '<tr valign="top">';
						echo '<th scope="row" style="width: 120px;"><label for="scca_rounded_corners">Rounded Corners:&nbsp;</label></th>';
						echo '<td><select name="scca_rounded_corners" id="scca_rounded_corners">';
						echo '<option ' . ($booRoundedCorners == 'Y' ? 'selected="selected"' : NULL) . ' value="Y">Yes</option>';
						echo '<option ' . ($booRoundedCorners == 'N' ? 'selected="selected"' : NULL) . ' value="N">No</option>';
						echo '</select></td>';
					echo '</tr>';
					echo '<tr valign="top">';
						echo '<th scope="row" style="width: 120px;"><label for="scca_posts_or_pages">Posts or Pages:&nbsp;</label></th>';
						echo '<td><select name="scca_posts_or_pages" id="scca_posts_or_pages">';
						echo '<option ' . ($strPostsOrPages == 'posts' 	? 'selected="selected"' : NULL) . ' value="posts">Posts</option>';
						echo '<option ' . ($strPostsOrPages == 'pages' 	? 'selected="selected"' : NULL) . ' value="pages">Pages</option>';
						echo '<option ' . ($strPostsOrPages == 'both' 	? 'selected="selected"' : NULL) . ' value="both">Both</option>';
						echo '</select></td>';
					echo '</tr>';
					echo '<tr valign="top">';
						echo '<th scope="row" style="width: 120px;"><label for="scca_before_or_after">Placement:&nbsp;</label></th>';
						echo '<td><select name="scca_before_or_after" id="scca_before_or_after">';
						echo '<option ' . ($strBeforeOrAfter == 'before' 	? 'selected="selected"' : NULL) . ' value="before">Before</option>';
						echo '<option ' . ($strBeforeOrAfter == 'after' 	? 'selected="selected"' : NULL) . ' value="after">After</option>';
						echo '<option ' . ($strBeforeOrAfter == 'both' 		? 'selected="selected"' : NULL) . ' value="both">Both</option>';
						echo '</select></td>';
					echo '</tr>';
					echo '<tr valign="top">';
						echo '<td><input type="submit" value="Save changes" id="submit" class="button button-primary"/></td>';
					echo '</tr>';
				echo '</table>';
			echo '</form>';
			
			// live preview below
			echo '<br />';
			echo '<h2>Preview</h2>';
			echo '<p class="refresh-preview" style="cursor: pointer; color: #278ab7;"><b>Refresh Preview</b></p>';
			echo '<div id="scca_preview">';
			echo $htmlCustomContent;
			echo '</div>';
			echo '<p class="description">Note that rounded corners and content are not applied to preview mode. The preview may differ slightly to live pages.';
			echo 'I hope you find this plugin useful. Should you come across any problems or have any suggestions please <a href="http://www.davidsneal.co.uk/wordpress/simple-custom-content-adder" target="_blank">leave a comment on this page</a>.</p>';
			
		// close #wrap	
		echo '</div>';
	}
	
	// --------- CUSTOM CONTENT ------------ //
			
	// get and show custom content
	function show_custom_content($content) {
		
		// globals
		global $wpdb;
		global $post;
		
		// variables
		$return = '';
		
		// query the db for current scca settings
		$arrSettings = $wpdb->get_results("SELECT option_name, option_value
											 FROM $wpdb->options 
											WHERE option_name LIKE 'scca_%'");
		
		// loop through each setting in the array
		foreach ($arrSettings as $setting) {
		
			// add each setting to the array by name
			$arrSettings[$setting->option_name] =  $setting->option_value;
		}
		
		// extract settings and add to user-friendly variables
		$htmlCustomContent 		= $arrSettings['scca_content'];
		$strBackgroundColour 	= $arrSettings['scca_background_color'];
		$strFontColour 			= $arrSettings['scca_font_color'];
		$intFontSize 			= $arrSettings['scca_font_size'];
		$intPadding 			= $arrSettings['scca_padding'];
		$strBorderColour 		= $arrSettings['scca_border_color'];
		$intBorderWidth 		= $arrSettings['scca_border_width'];
		$booRoundedCorners 		= $arrSettings['scca_rounded_corners'];
		$strBeforeOrAfter 		= $arrSettings['scca_before_or_after'];
		$strPostsOrPages 		= $arrSettings['scca_posts_or_pages'];
		
		// add scca setting to css
		$htmlContent = get_scca_css($strBackgroundColour, $strFontColour, $intFontSize, $intPadding, $strBorderColour, $intBorderWidth, $booRoundedCorners, $strPostsOrPages);
		
		// switch for posts or pages
		switch ($strPostsOrPages) {
		
			case 'both': // posts and pages
			$strIsWhatFunction = is_single() || is_page();
			break;
			
			case 'posts': // posts only
			$strIsWhatFunction = is_single();
			break;
			
			case 'pages': // pages only
			$strIsWhatFunction = is_page();
			break;
			
			case '': // version 1.0 - posts only
			$strIsWhatFunction = is_single();
			break;
		}
		
		// if viewing a single post
		if ($strIsWhatFunction) {
			
			// content
			$htmlContent.= '<div id="scca">';
			$htmlContent.= $htmlCustomContent;
			$htmlContent.= '</div>';
			
			// switch for placement of scca
			switch ($strBeforeOrAfter) {
			
				case 'before': // before the content
				$return = $htmlContent . $content;
				break;
				
				case 'after': // after the content
				$return = $content . $htmlContent;
				break;
				
				case 'both': // before and after the content
				$return = $htmlContent . $content . $htmlContent;
				break;
			}
		} 
		
		// if it's a page
		else {
		
			// just return the content
			$return = $content;
		}
		
		// return answer option
		return $return;
	}
	
	// return html css
	function get_scca_css($strBackgroundColour, $strFontColour, $intFontSize, $intPadding, $strBorderColour, $intBorderWidth, $booRoundedCorners, $strPostsOrPages) {
	
		// css style
		$htmlContent = '<style type="text/css">
						#scca, #scca_preview	{ 	background-color: ' . $strBackgroundColour . '; 
													color: 			  ' . $strFontColour . ';
													font-size: 		  ' . $intFontSize . 'px;
													padding: 		  ' . $intPadding . 'px;
													border: 	      ' . $intBorderWidth . 'px solid ' . $strBorderColour . ';
													' . ($booRoundedCorners == 'N' ? NULL :	'-webkit-border-radius: 10px; 
																							-o-border-radius: 10px;
																							border-radius: 10px;
																							-mox-border-radius: 10px;' ) . '
											   }
						</style>';
	
		// return
		return $htmlContent;	
	}

	// add answer option to the end of the post
	add_filter( 'the_content', 'show_custom_content');	
	
?>