<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package fialkablack_site
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- HEADER  -->
<header class="header">
	<div class="container">
		<div class="row align-items-center">
			
			<div class="col-xl-3 col-md-2 col-4 header__logo">
				<div class="logo">
					<a href="<?php echo home_url(''); ?>">
						<img src="<?php echo get_template_directory_uri(); ?>/img/logo.png" alt="">
					</a>
				</div>
			</div>

			<div class="col-xl-5 col-md-6 col-4 header__menu">
				<div class="menu sm-scroll">

					<?php if( is_front_page() ) : ?>
					   
					   <?php wp_nav_menu( array(
					   	'theme_location'  => '',
					   	'menu'            => 'main_menu',
					   	'container'       => false,
					   	'container_class' => '',
					   	'container_id'    => '',
					   	'menu_class'      => false,
					   	'menu_id'         => '',
					   	'echo'            => true,
					   	'fallback_cb'     => 'wp_page_menu',
					   	'before'          => '',
					   	'after'           => '',
					   	'link_before'     => '',
					   	'link_after'      => '',
					   	'items_wrap'      => '<ul id = "%1$s" class = "%2$s">%3$s</ul>',
					   	'depth'           => 0,
					   	'walker'          => '',
					   ) ); ?>
					   <?php wp_reset_postdata(); ?>

					<?php else : ?>
					    
					    <?php wp_nav_menu( array(
					    	'theme_location'  => '',
					    	'menu'            => 'main_menu_page',
					    	'container'       => false,
					    	'container_class' => '',
					    	'container_id'    => '',
					    	'menu_class'      => false,
					    	'menu_id'         => '',
					    	'echo'            => true,
					    	'fallback_cb'     => 'wp_page_menu',
					    	'before'          => '',
					    	'after'           => '',
					    	'link_before'     => '',
					    	'link_after'      => '',
					    	'items_wrap'      => '<ul id = "%1$s" class = "%2$s">%3$s</ul>',
					    	'depth'           => 0,
					    	'walker'          => '',
					    ) ); ?>
					    <?php wp_reset_postdata(); ?>
					    
					<?php endif; ?>

					

				</div>
				<div class="header_burger">
					<div class="burger_button">
						<span class="burger_color"></span>
						<span class="burger_color"></span>
					</div>
					<div class="menu_toggle sm-scroll" style="display: none;">
						
						<?php if( is_front_page() ) : ?>
						   
						   <?php wp_nav_menu( array(
						   	'theme_location'  => '',
						   	'menu'            => 'main_menu',
						   	'container'       => false,
						   	'container_class' => '',
						   	'container_id'    => '',
						   	'menu_class'      => false,
						   	'menu_id'         => '',
						   	'echo'            => true,
						   	'fallback_cb'     => 'wp_page_menu',
						   	'before'          => '',
						   	'after'           => '',
						   	'link_before'     => '',
						   	'link_after'      => '',
						   	'items_wrap'      => '<ul id = "%1$s" class = "%2$s">%3$s</ul>',
						   	'depth'           => 0,
						   	'walker'          => '',
						   ) ); ?>
						   <?php wp_reset_postdata(); ?>

						<?php else : ?>
						    
						    <?php wp_nav_menu( array(
						    	'theme_location'  => '',
						    	'menu'            => 'main_menu_page',
						    	'container'       => false,
						    	'container_class' => '',
						    	'container_id'    => '',
						    	'menu_class'      => false,
						    	'menu_id'         => '',
						    	'echo'            => true,
						    	'fallback_cb'     => 'wp_page_menu',
						    	'before'          => '',
						    	'after'           => '',
						    	'link_before'     => '',
						    	'link_after'      => '',
						    	'items_wrap'      => '<ul id = "%1$s" class = "%2$s">%3$s</ul>',
						    	'depth'           => 0,
						    	'walker'          => '',
						    ) ); ?>
						    <?php wp_reset_postdata(); ?>
						    
						<?php endif; ?>

						<div class="social">
							
							<?php if ( get_field( 'telegram', 8 )) : ?>
								<a href="<?php the_field('telegram', 8); ?>" target="_blank">
									<svg class="icon_1">
										<use xlink:href="<?php echo get_template_directory_uri(); ?>/img/svg/symbols.svg#icon_icon_1"></use>
									</svg>
								</a>
							<?php endif; ?>

							<?php if ( get_field( 'facebook', 8 )) : ?>
								<a href="<?php the_field('facebook', 8); ?>" target="_blank">
									<svg class="icon_1">
										<use xlink:href="<?php echo get_template_directory_uri(); ?>/img/svg/symbols.svg#icon_icon_2"></use>
									</svg>
								</a>
							<?php endif; ?>

							<?php if ( get_field( 'vk', 8 )) : ?>
								<a href="<?php the_field('vk', 8); ?>" target="_blank">
									<svg class="icon_1">
										<use xlink:href="<?php echo get_template_directory_uri(); ?>/img/svg/symbols.svg#icon_icon_3"></use>
									</svg>
								</a>
							<?php endif; ?>

							<?php if ( get_field( 'instagram', 8 )) : ?>
								<a href="<?php the_field('instagram', 8); ?>" target="_blank">
									<svg class="icon_1">
										<use xlink:href="<?php echo get_template_directory_uri(); ?>/img/svg/symbols.svg#icon_icon_4"></use>
									</svg>
								</a>
							<?php endif; ?>

						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-2 col-md-1 col-4 header__cart">
				<div class="cart">

					<?php
					global $woocommerce; ?>

					<a href="<?php echo wc_get_cart_url() ?>"" class="cart-content">
					 	<div class="img">
					 		<img src="<?php echo get_template_directory_uri(); ?>/img/cart.svg" alt="">
					 		<span class="count" id="mini-cart-header-count"><?php echo sprintf($woocommerce->cart->cart_contents_count); ?></span>
					 	</div>
					 </a>
					 <a href="tel:+71234567890" class="phone"><img src="<?php echo get_template_directory_uri(); ?>/img/icon_6.svg" alt=""></a>
				</div>
			</div>

			<div class="col-xl-2 col-md-3 header__contacts">
				<div class="contacts">
					<a href="tel:<?php the_field('main_phone_code', 8); ?>" class="phone"><?php the_field('main_phone', 8); ?></a>
					<div class="social">

						<?php if ( get_field( 'telegram', 8 )) : ?>
							<a href="<?php the_field('telegram', 8); ?>" target="_blank">
								<svg class="icon_1">
									<use xlink:href="<?php echo get_template_directory_uri(); ?>/img/svg/symbols.svg#icon_icon_1"></use>
								</svg>
							</a>
						<?php endif; ?>

						<?php if ( get_field( 'facebook', 8 )) : ?>
							<a href="<?php the_field('facebook', 8); ?>" target="_blank">
								<svg class="icon_1">
									<use xlink:href="<?php echo get_template_directory_uri(); ?>/img/svg/symbols.svg#icon_icon_2"></use>
								</svg>
							</a>
						<?php endif; ?>

						<?php if ( get_field( 'vk', 8 )) : ?>
							<a href="<?php the_field('vk', 8); ?>" target="_blank">
								<svg class="icon_1">
									<use xlink:href="<?php echo get_template_directory_uri(); ?>/img/svg/symbols.svg#icon_icon_3"></use>
								</svg>
							</a>
						<?php endif; ?>

						<?php if ( get_field( 'instagram', 8 )) : ?>
							<a href="<?php the_field('instagram', 8); ?>" target="_blank">
								<svg class="icon_1">
									<use xlink:href="<?php echo get_template_directory_uri(); ?>/img/svg/symbols.svg#icon_icon_4"></use>
								</svg>
							</a>
						<?php endif; ?>

					</div>
				</div>
			</div>

		</div>
	</div>
	
</header>

<!--  MAIN  -->
<main>

