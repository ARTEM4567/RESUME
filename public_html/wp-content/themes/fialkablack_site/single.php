<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package fialkablack_site
 */

get_header();
?>

	<!--  page  -->
	<section id="page">

		<div class="page__heading" style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/1.jpg');">
			<div class="container">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</div>
		</div>
		<div class="container">

			<div class="article">
				<article>
					
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

						<?php the_content(); ?>

						<?php endwhile; else: ?>

						<p>Не найдено записей по вашему запросу</p>

					<?php endif; ?>

				</article>
			</div>

		</div>
	</section>

<?php
get_footer();
