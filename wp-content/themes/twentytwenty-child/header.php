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

?><!DOCTYPE html>

<html class="no-js" <?php language_attributes(); ?>>

	<head>

		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >

		<link rel="profile" href="https://gmpg.org/xfn/11">

		<script src="https://cdnjs.cloudflare.com/ajax/libs/instafeed.js/1.4.1/instafeed.js" integrity="sha512-s3opS4QO/MUlcP5HoPYIwhk5gQ7QnhlmFP6mBhGANtfSC1hNRhaR1TvqmJEJHPSZG5+gxgZlHxW/H4dTaOntdQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,200;6..12,300;6..12,400;6..12,500;6..12,600;6..12,700&display=swap" rel="stylesheet">
		<?php wp_head(); ?>

	</head>

	<body <?php body_class(); ?>>

		<?php
		wp_body_open();
		?>

		<header id="site-header" class="header-footer-group">

			<div class="header-top"> 
				<div class="section-inner">
				<div class="header-top-left">
					<span><?php echo file_get_contents( get_stylesheet_directory_uri() . '/image/check.svg' ); ?><?php _e( 'Free shipping on all orders over $50', 'twentytwenty-child' ); ?></span>
				</div>
				<div class="header-top-right">
					<?php echo do_shortcode("[gtranslate]"); ?>
					<?php do_action('wcml_currency_switcher', array('format' => '%code%')); ?>
				</div>
				</div>
			</div>

			<div class="header-inner section-inner">

				<div class="header-titles-wrapper">

					<div class="header-titles">

						<?php
							// Site title or logo.
							twentytwenty_site_logo();

							// Site description.
							twentytwenty_site_description();
						?>

					</div><!-- .header-titles -->

				</div><!-- .header-titles-wrapper -->

				

				<div class="header-search">
				
					<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
						<label>
							<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
							<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search here…', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
						</label>
						<button type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" >
						<span class="toggle-inner">
										<?php twentytwenty_the_theme_svg( 'search' ); ?>
									</span></button>
					</form>
				</div>

				<div class="header-account-section">
					<?php
					// Check whether the header search is activated in the customizer.
					$enable_header_search = get_theme_mod( 'enable_header_search', true );

					if ( true === $enable_header_search ) {
						?>
						<div class="header-ac-icon search">
						<button class="toggle search-toggle mobile-search-toggle" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" data-set-focus=".search-modal .search-field" aria-expanded="false">
							<span class="toggle-inner">
								<span class="toggle-icon">
									<?php twentytwenty_the_theme_svg( 'search' ); ?>
								</span>
								<span class="toggle-text"><?php _ex( 'Search', 'toggle text', 'twentytwenty-child' ); ?></span>
							</span>
						</button><!-- .search-toggle -->
						</div>
					<?php } ?>
					<div class="header-ac-icon cart">
					<a href="<?php echo wc_get_cart_url() ?>"><?php echo file_get_contents( get_stylesheet_directory_uri() . '/image/cart.svg' ); ?>
					<?php _e( 'Cart', 'twentytwenty' ); ?><span class="counter"><?php echo WC()->cart->get_cart_contents_count() ?></span></a>
					</div>
					<div class="header-ac-icon wishlist">
					<a href="http://localhost/lowrance/wishlist/"><?php echo file_get_contents( get_stylesheet_directory_uri() . '/image/wishlist.svg' ); ?></a>
					</div>
					<div class="header-ac-icon account">
					<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php echo file_get_contents( get_stylesheet_directory_uri() . '/image/user.svg' ); ?></a>
					</div>	

					<button class="toggle nav-toggle header-ac-icon mobile-nav-toggle" data-toggle-target=".menu-modal"  data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".close-nav-toggle">
						<span class="toggle-inner">
							<span class="toggle-icon">
							<?php echo file_get_contents( get_stylesheet_directory_uri() . '/image/menu.svg' ); ?>
							</span>
							<span class="toggle-text"><?php _e( 'Menu', 'twentytwenty' ); ?></span>
						</span>
					</button><!-- .nav-toggle -->
				</div>	

			</div><!-- .header-inner -->

			<div class="header-bottom">
				<div class="header-inner section-inner">
					<button class="toggle nav-toggle mobile-nav-toggle" data-toggle-target=".menu-modal"  data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".close-nav-toggle">
						<span class="toggle-inner">
							<span class="toggle-icon">
							<?php echo file_get_contents( get_stylesheet_directory_uri() . '/image/menu.svg' ); ?>
							</span>
							<span class="toggle-text"><?php _e( 'Menu', 'twentytwenty' ); ?></span>
						</span>
					</button><!-- .nav-toggle -->
					<div class="header-navigation-wrapper">

						<?php
						if ( has_nav_menu( 'primary' ) || ! has_nav_menu( 'expanded' ) ) {
							?>

								<nav class="primary-menu-wrapper" aria-label="<?php echo esc_attr_x( 'Horizontal', 'menu', 'twentytwenty' ); ?>">

									<ul class="primary-menu reset-list-style">

									<?php
									if ( has_nav_menu( 'primary' ) ) {

										wp_nav_menu(
											array(
												'container'  => '',
												'items_wrap' => '%3$s',
												'theme_location' => 'primary',
											)
										);

									} elseif ( ! has_nav_menu( 'expanded' ) ) {

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

						if ( true === $enable_header_search || has_nav_menu( 'expanded' ) ) {
							?>

						<div class="header-toggles hide-no-js">

							<?php
							if ( has_nav_menu( 'expanded' ) ) {
								?>

								<div class="toggle-wrapper nav-toggle-wrapper has-expanded-menu">

									<button class="toggle nav-toggle desktop-nav-toggle" data-toggle-target=".menu-modal" data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".close-nav-toggle">
										<span class="toggle-inner">
											<span class="toggle-text"><?php _e( 'Menu', 'twentytwenty' ); ?></span>
											<span class="toggle-icon">
											<?php echo file_get_contents( get_stylesheet_directory_uri() . '/image/menu.svg' ); ?>
											</span>
										</span>
									</button><!-- .nav-toggle -->

								</div><!-- .nav-toggle-wrapper -->

								<?php
							}

							if ( true === $enable_header_search ) {
								?>

								<div class="toggle-wrapper search-toggle-wrapper">

									<button class="toggle search-toggle desktop-search-toggle" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" data-set-focus=".search-modal .search-field" aria-expanded="false">
										<span class="toggle-inner">
											<?php twentytwenty_the_theme_svg( 'search' ); ?>
											<span class="toggle-text"><?php _ex( 'Search', 'toggle text', 'twentytwenty' ); ?></span>
										</span>
									</button><!-- .search-toggle -->

								</div>

								<?php
							}
							?>

							</div><!-- .header-toggles -->
							<?php
						}
						?>

					</div><!-- .header-navigation-wrapper -->
					<div class="header-botton-contact">
					<?php _e( 'Call:', 'twentytwenty-child' ); ?> <span><?php _e( '(808) 555-0111', 'twentytwenty-child' ); ?></span>
					</div>
				</div>
			</div>

			<?php
			// Output the search modal (if it is activated in the customizer).
			if ( true === $enable_header_search ) {
				get_template_part( 'template-parts/modal-search' );
			}
			?>

		</header><!-- #site-header -->

		<?php
		// Output the menu modal.
		get_template_part( 'template-parts/modal-menu' );
