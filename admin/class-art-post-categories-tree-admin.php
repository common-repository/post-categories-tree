<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/post-categories-tree
 * @since      1.0.0
 *
 * @package    Art_Post_Categories_Tree
 * @subpackage Art_Post_Categories_Tree/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Art_Post_Categories_Tree
 * @subpackage Art_Post_Categories_Tree/admin
 * @author     wordpress. <support@wordpress.org>
 */
class Art_Post_Categories_Tree_Admin {

    /**
     * Art_Post_Categories_Tree_Admin __construct
     *
     * @since 1.0.0
     */
    public function __construct() {
         require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/class-art-widget-categories.php';
    }

    /**
     * Register Widgets.
     *
     * @since 1.0.0
     */
    function art_register_widgets() {
    	register_widget( 'ART_Widget_Categories' );
    }

}
