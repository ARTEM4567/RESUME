<?php
/**
 * fialkablack_site functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package fialkablack_site
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'fialkablack_site_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function fialkablack_site_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on fialkablack_site, use a find and replace
		 * to change 'fialkablack_site' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'fialkablack_site', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'fialkablack_site' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'fialkablack_site_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'fialkablack_site_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function fialkablack_site_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'fialkablack_site_content_width', 640 );
}
add_action( 'after_setup_theme', 'fialkablack_site_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function fialkablack_site_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'fialkablack_site' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'fialkablack_site' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'fialkablack_site_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function my_site_styles() {
	wp_register_style( 'libs-css', get_template_directory_uri() .
		'/css/libs.css');
	wp_enqueue_style( 'libs-css');

	wp_register_style( 'main-css', get_template_directory_uri() .
		'/css/main.css');
	wp_enqueue_style( 'main-css');
}
add_action('wp_enqueue_scripts', 'my_site_styles');


function my_site_scripts() {

	// wp_deregister_script('jquery');

	wp_register_script( 'libs_js', get_template_directory_uri() .
		'/js/libs.min.js');
	wp_enqueue_script( 'libs_js');

	wp_register_script( 'main-js', get_template_directory_uri() .
		'/js/main.js');
	wp_enqueue_script( 'main-js');
	
}
add_action( 'wp_enqueue_scripts', 'my_site_scripts' );


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}



//  remove breadcrumbs
 add_action( 'init', 'jk_remove_wc_breadcrumbs' );
 function jk_remove_wc_breadcrumbs() {
 remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
 }


 // remove zoom on gallery zoom
  add_action('after_setup_theme', 'remove_zoom_theme_support', 100);
  function remove_zoom_theme_support() {
      remove_theme_support('wc-product-gallery-zoom');
  }


  add_filter('woocommerce_get_image_size_thumbnail','add_thumbnail_size',1,10);
  function add_thumbnail_size($size){

      $size['width'] = 400;
      $size['height'] = 400;
      $size['crop']   = 0; //0 - не обрезаем, 1 - обрезка
      return $size;
  }


  add_filter( 'woocommerce_get_image_size_single', 'true_single_image_size' ); // woocommerce_single
   
  function true_single_image_size( $size_options ){
   
  	return array(
  		'width' => 800,
  		'height' => 800,
  		'crop' => 0, // 1 – жёсткая обрезка, 0 – сохранение пропорций
  	);
   
  }


  // Отключаем фильтр и сколько товаров
  remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
  remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
  remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10);


  //  Отключаем сайдбар
  remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);


  // gallery dots on mobile
  add_filter( 'woocommerce_single_product_carousel_options', 'custom_update_woo_flexslider_options' );
  function custom_update_woo_flexslider_options( $options ) {

      $options['controlNav'] = wp_is_mobile() ? true : 'thumbnails';
      
      return $options;
  }


  // Add to cart 
  add_filter( 'woocommerce_product_single_add_to_cart_text', 'tb_woo_custom_cart_button_text' );
  add_filter( 'woocommerce_product_add_to_cart_text', 'tb_woo_custom_cart_button_text' );   
  function tb_woo_custom_cart_button_text() {
          return __( 'Добавить в корзину', 'woocommerce' );
  }



  //  redirect to modal after ok
   add_action( 'template_redirect', 'truemisha_redirect_to_thank_you' );
    
   function truemisha_redirect_to_thank_you() {
    
   	// если не страница "Заказ принят", то ничего не делаем
   	if( ! is_wc_endpoint_url( 'order-received' ) ) {
   		return;
   	}
    
   	// неплохо бы проверить статус заказа, не редиректим зафейленные заказы
   	if( isset( $_GET[ 'key' ] ) ) {
   		$order_id = wc_get_order_id_by_order_key( $_GET[ 'key' ] );
   		$order = wc_get_order( $order_id );
   		if( $order->has_status( 'failed' ) ) {
   			return;
   		}
   	}
    
    
   	wp_redirect( site_url( '/?link_ok' ) );
   	exit;
    
   }


   //WooCommerce 3.4.5<br>//Уведомление о добавлении товара в корзину
   add_filter( 'wc_add_to_cart_message', 'custom_add_to_cart_message' );
   function custom_add_to_cart_message( $message ) {
       $message = ''; //здесь можно задать свой текст при добавлении товара в корзину, если оставите пустым то уведомление не будет выводиться
       return $message;
   }


   // меняем текст кнопки на странице checkout
  add_filter( 'woocommerce_order_button_text', 'truemisha_order_button_text' );
   
  function truemisha_order_button_text( $button_text ) {
  	return 'Оформить заказ';
  }



  add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
    
  function custom_override_checkout_fields( $fields ) {
    unset($fields['billing']['billing_country']);  //удаляем! тут хранится значение страны оплаты
    unset($fields['shipping']['shipping_country']); ////удаляем! тут хранится значение страны доставки
   
    return $fields;
  }


  // add_filter( 'woocommerce_get_remove_url', 'custom_item_remove_url', 10, 1 );
  // function custom_item_remove_url( $remove_url ) {
  // $cart_page_url   = wc_get_page_permalink( 'cart' );
  // $replacement_url = wc_get_page_permalink( 'checkout' ); // Shop page

  // // Change URL to shop page + remove Url query vars
  // $remove_url = str_replace($cart_page_url, $replacement_url, $remove_url);

  // return $remove_url;
  // }


  //  excerpt length
  function excerpt($limit) {
        $excerpt = explode(' ', get_the_excerpt(), $limit);

        if (count($excerpt) >= $limit) {
            array_pop($excerpt);
            $excerpt = implode(" ", $excerpt) . '...';
        } else {
            $excerpt = implode(" ", $excerpt);
        }

        $patterns = array (
          '`\[[^\]]*\]`',
          "'([\r\n])[\s]+'",  
           "'<[\/\!]*?[^<>]*?>'si"
         );

        $excerpt = preg_replace($patterns, '', $excerpt);
        $excerpt = str_replace("\xc2\xa0", ' ', $excerpt);

        return $excerpt;
  }

  function content($limit) {
      $content = explode(' ', get_the_content(), $limit);

      if (count($content) >= $limit) {
          array_pop($content);
          $content = implode(" ", $content) . '...';
      } else {
          $content = implode(" ", $content);
      }

      $content = preg_replace('/\[.+\]/','', $content);
      $content = apply_filters('the_content', $content); 
      $content = str_replace(']]>', ']]&gt;', $content);

      return $content;
  }



  /*********************  FILTER  *********************/


  add_action('wp_ajax_myfilter', 'my_filter_function'); // wp_ajax_{ACTION HERE} 
  add_action('wp_ajax_nopriv_myfilter', 'my_filter_function');
   
  function my_filter_function(){


  	if( isset( $_POST['product_cat'] ) && $_POST['product_cat'] )
  		$args = array(
  			'post_type' => 'product',
  			'orderby' => 'date',
  			'product_cat' => $_POST['product_cat'],
  		);

  	if( isset( $_POST['all'] ) && $_POST['all'] )
  		$args = array(
  			'post_type' => 'product',
  			'orderby'    => 'date',
  			'order' => 'ACS',
  		);

  	//  вывод
  	$query = new WP_Query( $args );
  	
  	if( $query->have_posts() ) :
  		while( $query->have_posts() ): $query->the_post(); 
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
  		
  	<?php endwhile;
  	wp_reset_postdata();
  	else :
  		echo 'No posts found';
  	endif;
  	
  	die();
   

  }

