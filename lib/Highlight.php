<?php
/**
 * Highlight menu
 */

namespace Trendwerk\PostTypeMenuHighlight;

final class Highlight
{
    private $postType;
    private $_cache = array();

    public function __construct()
    {
        add_filter('nav_menu_css_class', array($this, 'addClass'), 10, 2);
    }

    public function addClass($classes, $item)
    {
        if (! $this->postType = $this->getPostType()) {
            return $classes;
        }

        if ($this->isActive($item)) {
            $classes[] = 'current-menu-item';
        }

        /**
         * Parents & ancestors
         */
        if (count(array_intersect(array('current-menu-item', 'current-menu-parent', 'current-menu-ancestor'), $classes)) == 0) {
            $navMenu = wp_get_post_terms($item->ID, 'nav_menu');

            if (count($navMenu) > 0) {
                if (isset($this->_cache[$navMenu[0]->term_id])) {
                    $menuItems = $this->_cache[$navMenu[0]->term_id];
                } else {
                    $menuItems = wp_get_nav_menu_items($navMenu[0]->term_id);
                    $this->_cache[$navMenu[0]->term_id] = $menuItems;
                }

                foreach ($menuItems as $menuItem) {
                    if ($this->isActive($menuItem)) {
                        if ($depth = $this->findActiveDepth($menuItem, $item, $menuItems)) {
                            if ($depth == 1) {
                                $classes[] = 'current-menu-parent';
                            } elseif ($depth > 1) {
                                $classes[] = 'current-menu-ancestor';
                            }
                        }
                    }
                }
            }
        }

        return $classes;
    }

    private function getPostType()
    {
        if ($this->postType) {
            return $this->postType;
        }

        if (is_singular() || is_paged() || is_tax()) {
            if (! is_tax()) {
                return get_post_type();
            } else {
                $taxonomy = get_taxonomy(get_query_var('taxonomy'));

                /**
                 * Filter the preferred object type for a taxonomy
                 *
                 * @param string $post_type Default is the first associated post type.
                 * @param string $taxonomy
                 */
                return apply_filters('tp-highlight-taxonomy-post_type', $taxonomy->object_type[0], get_query_var('taxonomy'));
            }
        }
    }

    private function isActive($item)
    {
        return (trailingslashit($item->url) === get_post_type_archive_link($this->postType));
    }

    private function findActiveDepth($childItem, $parentItem, $allItems)
    {
        $depth = 0;

        while ($childItem->menu_item_parent) {
            $depth++;

            if ($childItem->menu_item_parent == $parentItem->ID) {
                return $depth;
            }

            $childItem = array_pop((wp_filter_object_list($allItems, array('ID' => $childItem->menu_item_parent))));
        }
    }
}
