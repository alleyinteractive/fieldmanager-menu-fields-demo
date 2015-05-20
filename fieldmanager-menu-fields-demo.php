<?php
/*
	Plugin Name: Fieldmanager Menu Fields Demo
	Plugin URI: https://github.com/alleyinteractive/fieldmanager-menu-fields-demo
	Description: A demo of adding Fieldmanager fields to navigation menu items.
	Version: 0.1
	Author: Alley Interactive
	Author URI: http://www.alleyinteractive.com/
*/
/*  This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


require_once dirname( __FILE__ ) . '/class-fm-menu-fields.php';

/**
 * Use a custom walker to render a menu formatted for editing. This can only be
 * included when the Walker_Nav_Menu_Edit class exists, so we'll include it when
 * the `wp_edit_nav_menu_walker` filter fires.
 *
 * @return string The class name of the custom walker.
 */
add_filter( 'wp_edit_nav_menu_walker', function() {
	require_once dirname( __FILE__ ) . '/class-fm-menu-fields-walker-nav-menu-edit.php';
	return 'FM_Menu_Fields_Walker_Nav_Menu_Edit';
} );