=== WP Infusion Recent Posts ===

A plugin for listing recent / featured posts with a circular avatar

=== Usage ===

Filters:

- infusion_recent_posts_f5_closing_tag
- recent_posts_opening_markup
- infusion_recent_posts_query_args
```
function recent_posts_closing_markup(){

	$markup = array( 'markup' => '<div class="row"><div class="small-12 medium-12 columns">');

	return $markup;
}

add_filter( 'infusion_recent_posts_f5_closing_tag', 'recent_posts_closing_markup' );


function recent_posts_opening_markup(){

	$markup = array( 'markup' => '</div></div>' );

	return $markup;
}

add_filter( 'infusion_recent_posts_f5_opening_tag', 'recent_posts_opening_markup' );
```