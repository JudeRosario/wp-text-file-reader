<?php
/**
 * Plugin Name: WP Text File Reader
 * Plugin URI:  http://wordpress.org/plugins
 * Description: Plugin to Read Text files and print them on screen using Shortcodes.
 * Version:     0.1.2
 * Author:      Jude Rosario
 * Author URI:  
 * License:     GPLv2+
 * Text Domain: wp_tfr
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2015 Jude Rosario (email : judesrosario89@gmail.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Built using grunt-wp-plugin
 * Copyright (c) 2013 10up, LLC
 * https://github.com/10up/grunt-wp-plugin
 */

// Useful global constants
define( 'WP_TFR_VERSION', '0.1.0' );
define( 'WP_TFR_URL',     plugin_dir_url( __FILE__ ) );
define( 'WP_TFR_PATH',    dirname( __FILE__ ) . '/' );
DEFINE( 'WP_TFR_FILE_PATH', '');

/**
 * Default initialization for the plugin:
 * - Registers the default textdomain.
 */
function wp_tfr_init() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'wp_tfr' );
	load_textdomain( 'wp_tfr', WP_LANG_DIR . '/wp_tfr/wp_tfr-' . $locale . '.mo' );
	load_plugin_textdomain( 'wp_tfr', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

/**
 * Activate the plugin
 */
function wp_tfr_activate() {
	// First load the init scripts in case any rewrite functionality is being loaded
	wp_tfr_init();

	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'wp_tfr_activate' );

/**
 * Deactivate the plugin
 * Uninstall routines should be in uninstall.php
 */
function wp_tfr_deactivate() {

}
register_deactivation_hook( __FILE__, 'wp_tfr_deactivate' );

// Wireup actions
add_action( 'init', 'wp_tfr_init' );

// Wireup filters

// Wireup shortcodes

add_shortcode("readfile", "wp_tfr_handler");

function wp_tfr_shortcode_handler($file) {

  $defaults = array( "path"   => WP_TFR_FILE_PATH 
  					 "class"  => 'text-file')	
 
// Extract the path from shortcode atts
  $file = shortcode_atts( $defaults, $file );
  $text = '';

// If path is valid extract text from file
  if('' != $file["path"]):
  	$text = read_file_contents( $file );
  	
  	if('text-file' != $file["class"])
  		$text = '<div class="'.$file["class"].'">'.$text.'</div>' ; 
  	else
  		$text = '<div class="text-file">'.$text.'</div>' ; 
  endif;
// Process any shortcodes as part of the file
return do_shortcode( $text );

}



function wp_tfr_read_file_contents ($file) {

// Get contents from file
	$text_file = $_SERVER['DOCUMENT_ROOT'] .$file["path"]; 
	$contents = file($text_file);

// Process the file input stream
	$text = implode($contents);
	$text = nl2br($text);

return $text;
}
