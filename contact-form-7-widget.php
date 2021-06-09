<?php
/**
 * Plugin Name: Contact Form 7 Widget
 * Description: The Contact Form 7 Widget allows you to add contact form via widget.
 * Plugin URI: http://cubitsoft.com/
 * Author: Dipendra Pancholi
 * Version: 1.0.0
 * Author URI: http://cubitsoft.com/
 * 
 * Text Domain: contact-form-7-widget
 * 
 * @package Contact Form 7 Widget
 * @category Core
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if( !defined( 'CF7WIDGET_VERSION' ) ) {
	define( 'CF7WIDGET_VERSION', '1.0' ); // Plugin Version
}
if( !defined( 'CF7WIDGET_DIR' ) ) {
	define( 'CF7WIDGET_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( !defined( 'CF7WIDGET_BASENAME' ) ) {
	define( 'CF7WIDGET_BASENAME', basename( CF7WIDGET_DIR ) ); //Plugin base name
}

/**
 * Load Text Domain
 *
 * This gets the plugin ready for translation.
 *
 * @package Contact Form 7 Widget
 * @since 1.0.0
 */
function cf7widget_load_text_domain() {
	
	// Set filter for plugin's languages directory
	$cf7widget_lang_dir	= dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$cf7widget_lang_dir	= apply_filters( 'cf7widget_languages_directory', $cf7widget_lang_dir );
	
	// Traditional WordPress plugin locale filter
	$locale	= apply_filters( 'plugin_locale',  get_locale(), 'contact-form-7-widget' );
	$mofile	= sprintf( '%1$s-%2$s.mo', 'contact-form-7-widget', $locale );
	
	// Setup paths to current locale file
	$mofile_local	= $cf7widget_lang_dir . $mofile;
	$mofile_global	= WP_LANG_DIR . '/' . CF7WIDGET_BASENAME . '/' . $mofile;
	
	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/contact-form-7-widget folder
		load_textdomain( 'contact-form-7-widget', $mofile_global );
	} elseif ( file_exists( $mofile_local ) ) { // Look in local /wp-content/plugins/contact-form-7-widget/languages/ folder
		load_textdomain( 'contact-form-7-widget', $mofile_local );
	} else { // Load the default language files
		load_plugin_textdomain( 'contact-form-7-widget', false, $cf7widget_lang_dir );
	}
}

/**
 * Prints an error that the system requirements weren't met.
 *
 * @package Contact Form 7 Widget
 * @since 1.0.0
 */
if( !function_exists( 'cf7widget_required_error' ) ) {
	
	function cf7widget_required_error() {
		
		echo '<div class="notice notice-error">
				<p>'. sprintf( esc_html__( 'Contact Form 7 Widget requires Contact Form 7 plugin to be active.', 'contact-form-7-widget' ), '<strong>', '</strong>') . '</p>
			</div>';
	}
}

//add action to load plugin
add_action( 'plugins_loaded', 'cf7widget_plugin_loaded' );

/**
 * Load plugin after dependent plugin is loaded successfully
 * 
 * @package Contact Form 7 Widget
 * @since 1.0.0
 */
function cf7widget_plugin_loaded() {
	
	if( class_exists( 'WPCF7' ) ) {

		// Load textdomain
		cf7widget_load_text_domain();

		// Include Widget file
		include_once( CF7WIDGET_DIR . '/includes/class-cf7widget.php' );

		// Load Widget
		if( !function_exists( 'cf7widget_load_widget' ) ) {

			function cf7widget_load_widget() {
				register_widget( 'CF7Widget_Widget' );
			}
			add_action( 'widgets_init', 'cf7widget_load_widget' );
		}

	} else {

		// Admin Notice if Contact Form 7 is not activate.
		add_action( 'admin_notices', 'cf7widget_required_error' );
	}
}