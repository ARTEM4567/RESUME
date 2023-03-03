<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package fialkablack_site
 */

?>

<div class="main_content page" id="post-<?php the_ID(); ?>">
	<div class="article">
		<article>
			
			<?php
			the_content(); ?>

		</article>
	</div>
</div>
