<?php
/**
 * Filter the arguments for the Recent Posts widget.
 *
 * @since 3.4.0
 *
 * @see WP_Query::get_posts()
 *
 * @param array $args An array of arguments used to retrieve the recent posts.
 */

$r = new WP_Query( apply_filters( 'infusion_recent_posts_args', array(
	'posts_per_page'      => 4,
	'no_found_rows'       => true,
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true
) ) );

if ($r->have_posts()) :

	echo $before_widget;

	if ( $name ) echo $before_title . $name . $after_title; ?>

	<div class="row featured-folio">

		<?php while ( $r->have_posts() ) : $r->the_post(); ?>

		<div class="small-12 medium-6 large-3 columns featured-folio-item">

			<div class="folio-image" style="background: url(<?php echo $large_image ?>) ">
				<?php if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'featured_folio_thumbnail', array( 'class' => ' small-12' ) );
				} else { ?>
					<img class="small-12" src="<?php echo get_template_directory_uri() . '/assets/img/c02.jpg' ?>" alt="">
				<?php } ?>
			</div>

			<div class="folio-avatar">
				<?php echo get_avatar( '60' ); ?>
			</div>

			<div class="folio-content">
				<h3>
					<a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
				</h3>
				<p><?php the_excerpt(); ?></p>
			</div>

		</div>

		<!-- Old -->
		<?php endwhile; ?>

	</div><!-- #featured-portfolio -->

<?php echo $after_widget; ?>

<?php
// Reset the global $the_post as this query will have stomped on it
wp_reset_postdata();

endif;

if ( ! $this->is_preview() ) {
	$cache[ $args['widget_id'] ] = ob_get_flush();
	wp_cache_set( 'widget_recent_posts', $cache, 'widget' );
} else {
	ob_end_flush();
}