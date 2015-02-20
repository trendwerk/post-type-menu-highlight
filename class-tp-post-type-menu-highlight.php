<?php
/**
 * Plugin Name: Post type menu highlight
 * Description: Fixes classes in main menu. Assigns the right classes to post type archives in a WordPress menu.
 *
 * Plugin URI: https://github.com/trendwerk/post-type-menu-highlight
 * 
 * Author: Trendwerk
 * Author URI: https://github.com/trendwerk
 * 
 * Version: 1.0.0
 */

class TP_Post_Type_Menu_Highlight {
	function __construct() {		
		add_filter( 'wp_nav_menu_objects', array( $this, 'highlight' ) );
	}

	/**
	 * Takes care of the highlighting in a navigation menu
	 *
	 * @param array $items
	 */
	function highlight( $items ) {
		if( $items ) {
			foreach( $items as $item ) {
				if( in_array( 'current-menu-item', $item->classes ) )
					return $items;
			}
			
			$nav = new TP_Nav();
			
			if( is_single() || is_tax() || is_paged() || is_author() ) {
				foreach( $items as &$item ) {
					if( $nav->current_item == $item->ID ) {
						$item->classes[] = 'current-menu-parent';
						$item->current = true;
					}
				}
			}
		}
		
		return $items;
	}
} new TP_Post_Type_Menu_Highlight;
