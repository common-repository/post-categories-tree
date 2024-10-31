<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wordpress.org/plugins/post-categories-tree
 * @since      1.0.0
 *
 * @package    Art_Post_Categories_Tree
 * @subpackage Art_Post_Categories_Tree/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Art_Post_Categories_Tree
 * @subpackage Art_Post_Categories_Tree/includes
 * @author     wordpress. <support@wordpress.org>
 */
class Art_Post_Categories_Tree_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'art-post-categories-tree',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
