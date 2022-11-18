<?php

/**
 * Header file for the Twenty Twenty WordPress default theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

?>
<!DOCTYPE html>

<html class="no-js" <?php language_attributes(); ?>>

<head>

	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

	<?php
	wp_body_open();
	?>

	<header id="site-header" class="header-footer-group">
		<div class="container-fluid">
			<div class="header-inner plr-30">

				<div class="header-titles-wrapper">



					<div class="header-titles">

						<?php
						// Site title or logo.
						twentytwenty_site_logo();

						// Site description.
						twentytwenty_site_description();
						?>

					</div><!-- .header-titles -->


					<?php

					// Check whether the header search is activated in the customizer.
					$enable_header_search = get_theme_mod('enable_header_search', true);

					if (true === $enable_header_search) {

					?>

						<div class="section-inner">

							<?php
							get_search_form(
								array(
									'aria_label' => __('Search for:', 'twentytwenty'),
								)
							);
							?>


						</div><!-- .Search Box -->

					<?php } ?>



				</div><!-- .header-titles-wrapper -->

				<div class="header-navigation-wrapper top_menu">

					<?php
					if (has_nav_menu('primary') || !has_nav_menu('expanded')) {
					?>

						<nav class="primary-menu-wrapper" aria-label="<?php echo esc_attr_x('Horizontal', 'menu', 'twentytwenty'); ?>">

							<ul class="primary-menu reset-list-style">

								<?php
								if (has_nav_menu('primary')) {

									wp_nav_menu(
										array(
											'container'  => '',
											'items_wrap' => '%3$s',
											'theme_location' => 'primary',
										)
									);
								} elseif (!has_nav_menu('expanded')) {

									wp_list_pages(
										array(
											'match_menu_classes' => true,
											'show_sub_menu_icons' => true,
											'title_li' => false,
											'walker'   => new TwentyTwenty_Walker_Page(),
										)
									);
								}
								?>

							</ul>

						</nav><!-- .primary-menu-wrapper -->

					<?php
					}

					if (true === $enable_header_search || has_nav_menu('expanded')) {
					?>

						<div class="header-toggles hide-no-js">

							<?php
							if (has_nav_menu('expanded')) {
							?>

								<div class="toggle-wrapper nav-toggle-wrapper has-expanded-menu">

									<button class="toggle nav-toggle desktop-nav-toggle" data-toggle-target=".menu-modal" data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".close-nav-toggle">
										<span class="toggle-inner">
											<span class="toggle-text"><?php _e('Menu', 'twentytwenty'); ?></span>
											<span class="toggle-icon">
												<?php twentytwenty_the_theme_svg('ellipsis'); ?>
											</span>
										</span>
									</button><!-- .nav-toggle -->

								</div><!-- .nav-toggle-wrapper -->

							<?php
							}


							?>

						</div><!-- .header-toggles -->
					<?php
					}
					?>

				</div><!-- .header-navigation-wrapper -->

				<div class="in_mobile">
					<a href="tel:18332073433" class="phone_sec" title="Click to call us">
						<div class="phone_icon"><img src="<?php echo esc_url(home_url('/')); ?>wp-content/uploads/2022/05/call.png"></div>
						<div class="call_number">1-833-207-3433<small>Need help? Call Us</small></div>
					</a>
					<button class="toggle nav-toggle mobile-nav-toggle" data-toggle-target=".menu-modal" data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".close-nav-toggle">
						<span class="toggle-inner">
							<span class="toggle-icon">
								<?php twentytwenty_the_theme_svg('ellipsis'); ?>
							</span>
							<span class="toggle-text"><?php _e('Menu', 'twentytwenty'); ?></span>
						</span>
					</button><!-- .nav-toggle -->
				</div>
			</div><!-- .header-inner -->
		</div>
		<div class="header-main-wrapper">
			<div class="container-fluid">
				<div class="mobile_menu">
					<div class="menUtext">Product's Category</div>
					<div class="toggle-wrapper nav-toggle-wrapper has-expanded-menu">

						<button class="product_menu"><i class="fa fa-bars"></i></button><!-- .nav-toggle -->

					</div><!-- .nav-toggle-wrapper -->

				</div>
				<?php
				if (has_nav_menu('primary') || !has_nav_menu('expanded')) {
				?>

					<nav class="primary-menu-wrapper" aria-label="<?php echo esc_attr_x('Horizontal', 'menu', 'twentytwenty'); ?>">



						<?php wp_nav_menu(
							array(
								'theme_location' => 'main-menu',
								'menu_class' => 'primary-menu reset-list-style',
							)
						);
						?>



					</nav><!-- .primary-menu-wrapper -->



				<?php
				}

				if (true === $enable_header_search || has_nav_menu('expanded')) {
				?>

					<div class="header-toggles hide-no-js">

						<?php
						if (has_nav_menu('expanded')) {
						?>

							<div class="toggle-wrapper nav-toggle-wrapper has-expanded-menu">

								<button class="toggle nav-toggle desktop-nav-toggle" data-toggle-target=".menu-modal" data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".close-nav-toggle">
									<span class="toggle-inner">
										<span class="toggle-text"><?php _e('Menu', 'twentytwenty'); ?></span>
										<span class="toggle-icon">
											<?php twentytwenty_the_theme_svg('ellipsis'); ?>
										</span>
									</span>
								</button><!-- .nav-toggle -->

							</div><!-- .nav-toggle-wrapper -->

						<?php
						}


						?>

					</div><!-- .header-toggles -->
				<?php
				}
				?>
			</div>
		</div><!-- .header-navigation-wrapper -->


		<?php
		// Output the search modal (if it is activated in the customizer).
		if (true === $enable_header_search) {
			get_template_part('template-parts/modal-search');
		}
		?>

	</header><!-- #site-header -->

	<?php
	// Output the menu modal.
	get_template_part('template-parts/modal-menu');
