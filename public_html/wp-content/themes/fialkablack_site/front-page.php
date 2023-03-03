<?php
/*
Template Name: Главная
*/
?>
<?php get_header(); ?>

<!--  mainblock  -->
<section id="mainblock" style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/1.jpg');">
	<div class="container">
		<div class="content center">
			
			<?php the_field('main_block_text', 8); ?>

			<a data-fancybox data-src="#modal_callback" href="javascript://" class="btn btn__white">Заказать звонок</a>
		</div>
	</div>
</section>

<!--  maincatalog  -->
<section id="maincatalog">
	<div class="container">
		<div class="heading center">
			<h2>Каталог</h2>
		</div>
		<div class="filter">
			<form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter">

				<div class="form__item">
					<label>
						<input type="radio" class="active" name="all" value="all">
						<span>Все</span>
					</label>
				</div>

				<?php
                $args = array(
                    'taxonomy' => 'product_cat',
                    'orderby'    => 'date',
                    'order'      => 'DESC',
                    'hide_empty' => false,
                    'exclude' => 15,
                );

                $product_categories = get_terms( $args );

                $count = count($product_categories);
              
                if ( $count > 0 ){
                    foreach ( $product_categories as $product_category ) {

                    	?>

                    	<div class="form__item">
                    		<label>
                    			<input type="radio" name="product_cat" value="<?php echo $product_category->slug; ?>">
                    			<span><?php echo $product_category->name; ?></span>
                    		</label>
                    	</div>

                    	<?php

                    }
                }
                ?>

				<input type="hidden" name="action" value="myfilter">

			</form>
			
			<!--  response  -->
			<div class="row" id="response">

				<?$query = new WP_Query( array(
                   'post_type' => 'product', 
                   'posts_per_page' => -1,
                   'orderby' => 'date',
                   'order' => 'ACS',
                   // 'product_cat' => 'course',
               ));
               if ( $query->have_posts() ) {
                while ( $query->have_posts() ) : $query->the_post();
                $thumb_medium = get_the_post_thumbnail( $post->ID, 'medium', array( 'itemprop' => 'image' ) );
                ?>

				<div class="col-lg-3 col-md-6 product__item">
					<div class="item w-100">
						<a href="<?php the_permalink() ?>" class="product__image">
							<?php if ( ! empty( $thumb_medium )) : 
                                echo $thumb_medium;
                            endif; ?>
						</a>
						<div class="product__content">
							<a href="<?php the_permalink() ?>" class="product__title">
								<h5><?php the_title( ); ?></h5>
							</a>
							<div class="product__desc"><?php echo excerpt( 12 ); ?></div>
							<div class="product_add">
								<?php 

								global $post;
								$terms = get_the_terms( $post->ID, 'product_cat' );
								foreach ($terms as $term) {
								    $product_cat_id = $term->name;
								    break;
								}
								 ?>
								<span class="category"><?php echo $product_cat_id; ?></span>
								<a href="<?php echo do_shortcode('[add_to_cart_url id="'.get_the_ID().'"]'); ?>" class="add_to_cart_button">Заказать</a>

								<?php woocommerce_template_single_price() ?>
							</div>
						</div>
					</div>
				</div>
				
			<?php
             endwhile;
            } else {
             echo 'No Pages with this template';
            }
            wp_reset_query(); ?>
				
			</div>
			<div class="box__btn center">
				<span class="btn-more btn">Смотреть ещё</span>
			</div>
		</div>
	</div>
</section>

<!--  delivery  -->
<section id="delivery">
	<div class="container">
		<div class="heading center">
			<h2>Доставка и оплата</h2>
		</div>
		<div class="row faq_tabs">
			
			<?php if( have_rows('delivery') ): $i = 1; ?>

				<?php while( have_rows('delivery') ): the_row(); 
					

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
	
<?php

get_footer();
