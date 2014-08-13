<?php
/**
 * Infusion Recent Posts Widget
 *
 * @package   Infusion Recent Posts Widget
 * @author    Tarei King
 * @license   GPL-2.0+
 * @link      http://tarei.me
 * @copyright 2014 Tarei King
 *
 * Plugin Name:       Infusion Recent Posts Widget
 * Plugin URI:        http://tarei.me/plugins/
 * Description:       Displays Sticky Posts with an avatar picture and excerpt in widget areas.
 * Version:           1.0.0
 * Author:            Tarei King
 * Author URI:        http://tarei.me
 * Text Domain:       infusion_recent_posts
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 */

class Infusion_Recent_Posts_Widget extends WP_Widget {

	protected $widget_slug = 'infusion-recent-posts-widget';

	public function __construct() {

		parent::__construct(
			$this->get_widget_slug(),
			__( 'Infusion Recent Posts', $this->get_widget_slug() ),
			array(
				'classname'  => $this->get_widget_slug(),
				'description' => __( 'Displays your recent posts with a featured image and an avatar in the center.', $this->get_widget_slug() )
			)
		);

		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );

	}

	/**
	 * Return the widget slug.
	 */
	public function get_widget_slug() {
	    return $this->widget_slug;
	}

	/**
	 * Outputs the content of the widget.
	 */
	public function widget( $args, $instance ) {

		/**
		 * Filter the arguments for the Recent Posts widget.
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

			<?php if ( '' != $widget_title ) : ?>
			<div class="featured-folio-header full-width-header">
				<div class="row">
					<div class="small-12">
						<h3><?php _e( $widget_title ); ?></h3>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<div class="row featured-folio">

				<?php while ( $r->have_posts() ) : $r->the_post(); ?>

				<div class="small-12 medium-6 large-3 columns featured-folio-item">
					<div class="carousel-overlay"></div>

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

	} // End widget

	/**
	 * Processes the widget's options to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;

	}

	/**
	 * Generates the administration form for the widget.
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = strip_tags( $instance['title'] ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<?php
	}

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts() {

		wp_enqueue_style( $this->get_widget_slug().'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ) );
		wp_enqueue_script( $this->get_widget_slug().'-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array('jquery') );

	}

	/**
	 * Registers and enqueues widget-specific scripts.
	 */
	public function register_widget_scripts() {

		wp_enqueue_style( $this->get_widget_slug().'-widget-styles', plugins_url( 'css/widget.css', __FILE__ ) );
		wp_enqueue_script( $this->get_widget_slug().'-script', plugins_url( 'js/widget.js', __FILE__ ), array('jquery') );

	}

}

add_action( 'widgets_init', create_function( '', 'register_widget("Infusion_Recent_Posts_Widget");' ) );
