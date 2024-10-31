<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wordpress.org/plugins/post-categories-tree
 * @since             1.0.0
 * @package           Art_Post_Categories_Tree
 *
 * @wordpress-plugin
 * Plugin Name:       Display Categories Tree
 * Plugin URI:        https://wordpress.org/plugins/post-categories-tree
 * Description:       This plugin display your sidebar categories in tree-view.
 * Version:           1.0.1
 * Author:            Abderrahmane Oulmderat
 * Author URI:        https://profiles.wordpress.org/aoulmderat/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       art-post-categories-tree
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ART_POST_CATEGORIES_TREE_VERSION', '1.0.1' );

/**
 * Currently plugin name.
 */
define( 'ART_POST_CATEGORIES_TREE_NAME', 'art-post-categories-tree' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-art-post-categories-tree-activator.php
 */
function activate_art_post_categories_tree() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-art-post-categories-tree-activator.php';
	Art_Post_Categories_Tree_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-art-post-categories-tree-deactivator.php
 */
function deactivate_art_post_categories_tree() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-art-post-categories-tree-deactivator.php';
	Art_Post_Categories_Tree_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_art_post_categories_tree' );
register_deactivation_hook( __FILE__, 'deactivate_art_post_categories_tree' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-art-post-categories-tree.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_art_post_categories_tree() {

	$plugin = new Art_Post_Categories_Tree();
	$plugin->run();

}
run_art_post_categories_tree();
