<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>

<!--  page  -->
<section id="page">

	<div class="page__heading" style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/1.jpg');">
		<div class="container">
			<h2>Оформить заказ</h2>
		</div>
	</div>
	<div class="container">
		
		<!--  product_card  -->
		<div class="product_card">
			<div class="row">

				<!-- card_gallery  -->
				<div class="col-lg-6 card__gallery">
					<div class="gallery">
						<?php
						do_action( 'woocommerce_before_single_product_summary' );
						?>
					</div>
				</div>

				<!--  card_summary  -->
				<div class="col-lg-6 card__summary">
					<div class="summary">
						<h1 class="product__title"><?php the_title() ?></h1>
						<div class="product__article">
							<p><?php woocommerce_template_single_excerpt() ?></p>
						</div>
						<div class="product__cat">

							<span class="category">

								<?php
								    $terms = get_the_terms( $post->ID, 'product_cat' );
								    echo implode(', ', array_map(function($term) { 
								    	$cat_name = $term->name;
								    	return $cat_name; 
								    }, $terms));
								?>
								
							</span>
							<div class="product__price_modile">
								<?php woocommerce_template_single_price() ?>
							</div>
						</div>
						<form class="cart">

							<?php 

								if ( $product->is_purchasable() ) {

									echo "<h5>Выберите количество</h5>";
								    
								}   
							 ?>
							
							<div class="box_btn">

								<?php woocommerce_template_single_add_to_cart(); ?>

							</div>

						</form>
						
					</div>
				</div>

			</div>
		</div>

	</div>
</section>

<?php do_action( 'woocommerce_after_single_product' ); ?>

<!--  delivery  -->
<section id="delivery">
	<div class="container">
		<div class="heading center">
			<h2>Доставка и оплата</h2>
		</div>
		<div class="row faq_tabs">
			
			<?php if( have_rows('delivery', 8) ): $i = 1; ?>

				<?php while( have_rows('delivery', 8) ): the_row(); 
					

					// vars
					$delivery_q = get_sub_field('delivery_q');
					$delivery_a = get_sub_field('delivery_a');

					?>

					<div class="col-lg-6 tabs_item">
						<div class="title"><span><?php echo $i; ?>.</span><?php echo $delivery_q; ?></div>
						<div class="tab_content">
							<?php echo $delivery_a; ?>
						</div>
					</div>

				<?php $i++; endwhile; ?>

			<?php endif; ?>

		</div>
	</div>
</section>
