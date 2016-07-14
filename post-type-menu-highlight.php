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
 * Version: 1.1.5
 */

require_once('lib/Highlight.php');

new Trendwerk\PostTypeMenuHighlight\Highlight();
