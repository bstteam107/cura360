<?php
/**
 * The template for displaying single posts and pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

get_header();
?>

<main id="site-content">
<div class="main-container">
<?php
if (!is_front_page()) {
	echo '<div class="container-fluid"><div class="row"><div class="col-sm-12 col-lg-8 col-xl-9">';
}
?>
	<?php

	if ( have_posts() ) {
		
		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );
			
		}
	}

	?>
		<?php if (!is_front_page()) {echo'</div>'; }?>
		
			<?php
			if (!is_front_page()) {
				echo '<div class="col-sm-12 col-lg-4 col-xl-3">'; 
				get_template_part( 'template-parts/sidebar' );
				echo '</div>';
			}
			?>
		<?php if (!is_front_page()) {echo'</div>'; }?>
</div>
</main><!-- #site-content -->



<?php get_footer(); ?>
