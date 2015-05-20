<?php
/**
 * Create a custom HTML list of nav menu input items.
 *
 * @see Walker_Nav_Menu_Edit
 * @see Walker_Nav_Menu
 */
class FM_Menu_Fields_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {
	/**
	 * Start the element output.
	 *
	 * The default walker is only amended, not replaced. Content can be added to
	 * elements with a filter.
	 *
	 * @see Walker_Nav_Menu_Edit::start_el();
	 * @link https://github.com/kucrut/wp-menu-item-custom-fields
	 *
	 * @param string $output Passed by reference. Used to append additional
	 *                       content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   Not used.
	 * @param int    $id     Not used.
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$item_output = '';
		parent::start_el( $item_output, $item, $depth, $args, $id );
		$output .= preg_replace(
			'/(?=<p[^>]+class="[^"]*field-move)/',
			apply_filters( 'fmmfd_walker_nav_menu_edit_start_el', '', $item, $depth ),
			$item_output
		);
	}
}