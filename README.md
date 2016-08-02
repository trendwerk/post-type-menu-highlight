Post type menu highlight
========================

Fixes classes in main menu. Made for WordPress.

Assigns the right classes to post type archives in a WordPress menu.

### Installation
If you're using Composer to manage WordPress, add this plugin to your project's dependencies. Run:
```sh
composer require trendwerk/post-type-menu-highlight
```

### Hooks

```php
apply_filters( 'tp-highlight-taxonomy-post_type', $post_type, $taxonomy );
```

Filters the preferred post type to be highlighted when viewing a taxonomy term.
