<?php
/**
 * Widget API: ART_Widget_Categories class
 *
 * @package widgets
 * @since 1.0.0
 */

/**
 * Core class used to implement a Categories widget.
 *
 * @since 1.0.0
 *
 * @see WP_Widget
 */
class ART_Widget_Categories extends WP_Widget {

    /**
	 * Category ancestors.
	 *
	 * @var array
	 */
	public $cat_ancestors;

	/**
	 * Sets up a new Categories widget instance.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'                   => 'art_widget_categories',
			'description'                 => __( 'Display list or dropdown of categories in tree mode.' ),
			'customize_selective_refresh' => true,
		);

		parent::__construct( 'art-categories', __( 'Display Categories Tree' ), $widget_ops );
	}


    /**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 * Note: This function is called only if the category widget is present otherwise not, this is very important
	 * to load script and style only if needed.
	 *
	 * @since    1.0.1
	 */
	public function define_public_hooks() {
       /**
        * The class responsible for defining all actions that occur in the public-facing
        * side of the site.
        */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-art-post-categories-tree-public.php';

       /**
        * The class responsible for defining walker to our category
        */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'walkers/class-art-cat-list-walker.php';

		$plugin_public = new Art_Post_Categories_Tree_Public( ART_POST_CATEGORIES_TREE_NAME, ART_POST_CATEGORIES_TREE_VERSION );
       if ( is_active_widget(false, false, $this->id_base, true) ) {
		    $plugin_public->enqueue_styles();
		    $plugin_public->enqueue_scripts();
		}

	}

	/**
	 * Outputs the content for the current Categories widget instance.
	 *
	 * @since 1.0.0
	 *
	 * @staticvar bool $first_dropdown
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Categories widget instance.
	 */
	public function widget( $args, $instance ) {

	    global $wp_query, $post;
		static $first_dropdown = true;

		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Categories' );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'art_widget_title', $title, $instance, $this->id_base );

		$count        = ! empty( $instance['count'] ) ? '1' : '0';
		$hide_empty   = ! empty( $instance['hide_empty'] ) ? '1' : '0';
		$hierarchical = ! empty( $instance['hierarchical'] ) ? '1' : '0';
		$dropdown     = ! empty( $instance['dropdown'] ) ? '1' : '0';

        $this->current_cat   = false;
		$this->cat_ancestors = array();

		$this->current_cat   = $wp_query->queried_object;
        $this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'category' );

		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$cat_args = array(
			'orderby'      => 'name',
			'show_count'   => $count,
			'hide_empty'   => $hide_empty,
			'hierarchical' => $hierarchical,
		);

		if ( $dropdown ) {
			echo sprintf( '<form action="%s" method="get">', esc_url( home_url() ) );
			$dropdown_id    = ( $first_dropdown ) ? 'cat' : "{$this->id_base}-dropdown-{$this->number}";
			$first_dropdown = false;

			echo '<label class="screen-reader-text" for="' . esc_attr( $dropdown_id ) . '">' . $title . '</label>';

			$cat_args['show_option_none'] = __( 'Select Category' );
			$cat_args['id']               = $dropdown_id;

			/**
			 * Filters the arguments for the Categories widget drop-down.
			 *
			 * @since 1.0.0
			 *
			 * @see wp_dropdown_categories()
			 *
			 * @param array $cat_args An array of Categories widget drop-down arguments.
			 * @param array $instance Array of settings for the current widget.
			 */
			wp_dropdown_categories( apply_filters( 'art_widget_categories_dropdown_args', $cat_args, $instance ) );

			echo '</form>';

			$type_attr = current_theme_supports( 'html5', 'script' ) ? '' : ' type="text/javascript"';
			?>

<script<?php echo $type_attr; ?>>
/* <![CDATA[ */
(function() {
	var dropdown = document.getElementById( "<?php echo esc_js( $dropdown_id ); ?>" );
	function onCatChange() {
		if ( dropdown.options[ dropdown.selectedIndex ].value > 0 ) {
			dropdown.parentNode.submit();
		}
	}
	dropdown.onchange = onCatChange;
})();
/* ]]> */
</script>

			<?php
		} else {
			?>
		<ul class="art-categories">
			<?php
			$this->define_public_hooks();
			$cat_args['title_li'] = '';
            $cat_args['walker']                     = new ART_Cat_List_Walker();
            $cat_args['current_category']           = ( $this->current_cat ) ? $this->current_cat->term_id : '';
            $cat_args['current_category_ancestors'] = $this->cat_ancestors;
			/**
			 * Filters the arguments for the Categories widget.
			 *
			 * @since 2.8.0
			 * @since 4.9.0 Added the `$instance` parameter.
			 *
			 * @param array $cat_args An array of Categories widget options.
			 * @param array $instance Array of settings for the current widget.
			 */
			wp_list_categories( apply_filters( 'art_widget_categories_args', $cat_args, $instance ) );
			?>
		</ul>
			<?php
		}

		echo $args['after_widget'];
	}

	/**
	 * Handles updating settings for the current Categories widget instance.
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                 = $old_instance;
		$instance['title']        = sanitize_text_field( $new_instance['title'] );
		$instance['count']        = ! empty( $new_instance['count'] ) ? 1 : 0;
		$instance['hide_empty']   = ! empty( $new_instance['hide_empty'] ) ? 1 : 0;
		$instance['hierarchical'] = ! empty( $new_instance['hierarchical'] ) ? 1 : 0;
		$instance['dropdown']     = ! empty( $new_instance['dropdown'] ) ? 1 : 0;

		return $instance;
	}

	/**
	 * Outputs the settings form for the Categories widget.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		// Defaults.
		$instance     = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$count        = isset( $instance['count'] ) ? (bool) $instance['count'] : false;
		$hide_empty   = isset( $instance['hide_empty'] ) ? (bool) $instance['hide_empty'] : false;
		$hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
		$dropdown     = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></p>

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'dropdown' ); ?>" name="<?php echo $this->get_field_name( 'dropdown' ); ?>"<?php checked( $dropdown ); ?> />
		<label for="<?php echo $this->get_field_id( 'dropdown' ); ?>"><?php _e( 'Display as dropdown' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Show post counts' ); ?></label><br />

        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'hide_empty' ); ?>" name="<?php echo $this->get_field_name( 'hide_empty' ); ?>"<?php checked( $hide_empty ); ?> />
		<label for="<?php echo $this->get_field_id( 'hide_empty' ); ?>"><?php _e( 'Hide empty' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'hierarchical' ); ?>" name="<?php echo $this->get_field_name( 'hierarchical' ); ?>"<?php checked( $hierarchical ); ?> />
		<label for="<?php echo $this->get_field_id( 'hierarchical' ); ?>"><?php _e( 'Show hierarchy' ); ?></label></p>
		<?php
	}

}
