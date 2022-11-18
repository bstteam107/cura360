<?php
/**
 * Displays the menus and widgets at the end of the main element.
 * Visually, this output is presented as part of the footer element.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

$has_footer_menu = has_nav_menu( 'footer' );
$has_social_menu = has_nav_menu( 'social' );

$has_sidebar_3 = is_active_sidebar( 'sidebar-3' );

// Only output the container if there are elements to display.
if ( $has_sidebar_3 ) {
	
	?>

	<div class="blog-nav-widgets-wrapper header-footer-group">

		<div class="blog-inner">			

			<?php if ( $has_sidebar_3 ) { ?>

				<aside class="blog-widgets-outer-wrapper">

					<div class="blog-widgets-wrapper">

					<?php if ( $has_sidebar_3 ) { ?>

					<div class="blog-widgets">
						<?php dynamic_sidebar( 'sidebar-3' ); ?>
						<?php get_template_part('template-parts/related-post', 'posts'); // related posts ?>
					</div>

					<?php } ?>						

					</div><!-- .footer-widgets-wrapper -->

				</aside><!-- .footer-widgets-outer-wrapper -->

			<?php } ?>

		</div><!-- .footer-inner -->

	</div><!-- .footer-nav-widgets-wrapper -->

<?php } ?>
