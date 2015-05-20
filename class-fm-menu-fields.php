<?php

/**
 * Submenu Management
 */

class FM_Menu_Fields {

	private static $instance;

	/**
	 * Post meta key for storing the submenu layout.
	 *
	 * @var string
	 */
	private $layout_key = '_fmmfd_submenu_layout';

	/**
	 * Post meta key for storing the highlight value of a menu item.
	 *
	 * @var string
	 */
	private $highlight_key = '_fmmfd_highlight';

	/**
	 * Display names and values for the layouts.
	 *
	 * @var array
	 */
	private $layout_options;

	private function __construct() {
		/* Don't do anything, needs to be initialized via instance() method */
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new FM_Menu_Fields;
			self::$instance->setup();
		}
		return self::$instance;
	}

	public function setup() {
		$this->layout_options = array(
			'one_col' => __( 'One Column', 'fmmfd' ),
			'two_col' => __( 'Two Column', 'fmmfd' ),
			'three_col' => __( 'Three Column', 'fmmfd' ),
			'four_col' => __( 'Four Column', 'fmmfd' ),
		);

		if ( class_exists( 'Fieldmanager_Field' ) ) {
			add_filter( 'fmmfd_walker_nav_menu_edit_start_el', array( $this, 'add_fields'), 10, 3 );
			add_action( 'wp_update_nav_menu_item', array( $this, 'save_fields' ), 10, 3 );
		}
	}

	/**
	 * Add fields to the editor of a nav menu item.
	 *
	 * @param string $content Any content to be appended from other filters.
	 * @param object $item The nav menu item.
	 * @param int $depth Depth of menu item.
	 * @return string HTML to append to the item.
	 */
	public function add_fields( $content, $item, $depth ) {
		// The layout field is only added to top-level items
		if ( '0' == $depth ) {
			// Layout
			$layout = new Fieldmanager_Select( array(
				'label' => __( 'Submenu Layout', 'fmmfd' ),
				'name' => "{$item->ID}_{$this->layout_key}",
				'options' => $this->layout_options,
				'skip_save' => true,
			) );
			$content .= $layout->single_element_markup( get_post_meta( $item->ID, $this->layout_key, true ) );
		}

		// The Highlight field is added to menu items at all depths
		$highlight = new Fieldmanager_Checkbox( array(
			'label' => __( 'Highlight', 'fmmfd' ),
			'name' => "{$item->ID}_{$this->highlight_key}",
			'skip_save' => true,
			'checked_value' => '1',
			'unchecked_value' => '0',
		) );
		$content .= $highlight->single_element_markup( get_post_meta( $item->ID, $this->highlight_key, true ) );

		return $content;
	}

	/**
	 * Save post meta for nav menu items.
	 *
	 * @param int $menu_id The ID of the menu
	 * @param int $menu_item_db_id The ID of the menu item
	 * @param array $args Menu item args
	 * @return void
	 */
	public function save_fields( $menu_id, $menu_item_db_id, $menu_item_args ) {
		// Layout field
		if ( isset( $_POST[ "{$menu_item_db_id}_{$this->layout_key}" ] ) ) {
			$submitted = sanitize_text_field( $_POST[ "{$menu_item_db_id}_{$this->layout_key}" ] );
			if ( ! empty( $this->layout_options[ $submitted ] ) ) {
				update_post_meta( $menu_item_db_id, $this->layout_key, $submitted );
			} else {
				delete_post_meta( $menu_item_db_id, $this->layout_key );
			}
		}

		// Highlight field
		if ( isset( $_POST[ "{$menu_item_db_id}_{$this->highlight_key}" ] ) ) {
			$submitted = sanitize_text_field( $_POST[ "{$menu_item_db_id}_{$this->highlight_key}" ] );
			if ( in_array( $submitted, array( '0', '1' ) ) ) {
				update_post_meta( $menu_item_db_id, $this->highlight_key, $submitted );
			} else {
				delete_post_meta( $menu_item_db_id, $this->highlight_key );
			}
		}
	}

}

function FM_Menu_Fields() {
	return FM_Menu_Fields::instance();
}
add_action( 'after_setup_theme', 'FM_Menu_Fields' );