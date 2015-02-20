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
 * Version: 1.1.0
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
		if( 0 === count( $items ) )
			return $items;

		$current = array_pop( ( wp_filter_object_list( $items, array( 'current' => true ) ) ) );

		if( isset( $current ) )
			return $items;
		
		if( is_singular() || is_paged() || is_tax() ) {

			/**
			 * Determine post type
			 */
			if( ! is_tax() ) {
				$_post_type = get_post_type();
			} else {
				$_taxonomy = get_taxonomy( get_query_var( 'taxonomy' ) );

				/**
				 * Filter the preferred object type for a taxonomy
				 *
				 * @param string $post_type Default is the first associated post type.
				 * @param string $taxonomy
				 */
				$_post_type = apply_filters( 'tp-highlight-taxonomy-post_type', $_taxonomy->object_type[0], get_query_var( 'taxonomy' ) );
			}

			if( ! isset( $_post_type ) )
				return;

			/**
			 * Highlight post type
			 */
			foreach( $items as &$item ) {

				if( trailingslashit( $item->url ) === get_post_type_archive_link( $_post_type ) ) {
					$item->classes[] = 'current-menu-item';
					$item->current = true;

					break;
				}

			}

			/**
			 * Highlight parents
			 */
			$parent = $item;

			while( 0 < $parent->menu_item_parent ) {
				$parent_key = array_pop( ( array_keys( wp_filter_object_list( $items, array( 'ID' => $parent->menu_item_parent ) ) ) ) );

				$items[ $parent_key ]->classes[] = 'current-menu-ancestor';
				$items[ $parent_key ]->current_item_ancestor = true;

				$parent = $items[ $parent_key ];
			}

		}
		
		return $items;
	}

} new TP_Post_Type_Menu_Highlight;
