<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package fialkablack_site
 */

get_header();
?>

	<div class="container center">
		<br>
	  <h2 class="page-title" style="padding-top: 30px; padding-bottom: 30px;"><?php esc_html_e( '404! Страница не найдена.', 'fialkablack_site' ); ?></h2>
	  <br>
	  <a href="/#maincatalog" class="btn">Перейти в каталог</a>
	</div>

<?php
get_footer();
