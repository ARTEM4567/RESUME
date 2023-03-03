<?php
/*
Plugin Name: WooCommerce - создание заказа
Plugin URI:  https://kwork.ru/user/nosocial
Description: 
Version:     0.1a
Author:      noSocial
Author URI:  https://kwork.ru/user/nosocial
*/

			
add_action( 'wp_ajax_RequestCreateOrderType', 'trueRequestCreateOrderType_ajax' ); // wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_RequestCreateOrderType', 'trueRequestCreateOrderType_ajax' );  // wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}
 
function trueRequestCreateOrderType_ajax(){
 
  global $woocommerce;
$price = $_POST[ 'price' ];
$email = $_POST[ 'email' ];
$phone = $_POST[ 'phone' ];
$payment_method = $_POST[ 'payment_method' ];

  $address = array(
      'email'      => $email,
      'phone'      => $phone,
  );

  $order = wc_create_order();

  $order->set_address( $address, 'billing' );
  
  $product = wc_get_product( 283 );

$product->set_price( $price );


$order->add_product( $product, 1);


  $order->calculate_totals();
  $order->update_status("Completed", 'Imported order', TRUE);  


    update_post_meta( $order->id, '_payment_method', $payment_method );
    update_post_meta( $order->id, '_payment_method_title', $payment_method );

    WC()->session->order_awaiting_payment = $order->id;
    $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
    $result = $available_gateways[ $payment_method ]->process_payment( $order->id );

    if ( $result['result'] == 'success' ) {

	$token = bin2hex(random_bytes(16)); 

    update_post_meta( $order->id, '_confirmation_token_secret', $token );
	
    update_post_meta( $order->id, '_request_update', 0);
	
        $result = apply_filters( 'woocommerce_payment_successful_result', $result, $order->id );
		$quick_checkout = $result['redirect'].'&quick_checkout=true';
		$arr = array('confirmation_token' => $token, 'order_id' => $order->id, 'payUrl' => $quick_checkout);
		echo json_encode($arr);
    }else{
				$arr = array('error' => 'Error payment');
		echo json_encode($arr);
	}



die;
}

function getRequest($status ,$order_id,$values_token,$url){


$url = $url ? $url['input'] : null;
	$PostData = array('confirmation_token'=>$values_token, 'order_id'=>$order_id, 'status'=>$status);

$ch = curl_init( $url );
$payload = json_encode( array('confirmation_token'=>$values_token, 'order_id'=>$order_id, 'status'=>$status) );
curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
$result = curl_exec($ch);
curl_close($ch);

}

    function request_update_hold($order_id) {
		$values_token = get_post_meta( $order_id, '_confirmation_token_secret', true);
		$url = get_option('callback_api_wv', true);
		$values = get_post_meta( $order_id, '_request_update', true);
		if(!$values){
		getRequest('fail',$order_id,$values_token,$url);
			update_post_meta( $order_id, '_request_update', 1);		
		}

    }
    function request_update_processing($order_id) {	
	$values = get_post_meta( $order_id, '_request_update', true);
			$values_token = get_post_meta( $order_id, '_confirmation_token_secret', true);
		$url = get_option('callback_api_wv', true);
		if(!$values){
			
		getRequest('success',$order_id,$values_token,$url);
			update_post_meta( $order_id, '_request_update', 1);
		}
    }
	    function request_update_completed($order_id) {
		$values_token = get_post_meta( $order_id, '_confirmation_token_secret', true);
		$url = get_option('callback_api_wv', true);
		$values = get_post_meta( $order_id, '_request_update', true);
		if(!$values){
			getRequest('success',$order_id,$values_token,$url);
			update_post_meta( $order_id, '_request_update', 1);
		}
    }

    add_action( 'woocommerce_order_status_on-hold', 'request_update_hold');
    add_action( 'woocommerce_order_status_processing', 'request_update_processing');
    add_action( 'woocommerce_order_status_completed', 'request_update_completed');












add_action('admin_menu', 'add_plugin_page');
function add_plugin_page(){
	add_options_page( 'Настройки плагина API WooCommerce', 'API WooCommerce', 'manage_options', 'api_woocommerce_token', 'api_woocommerce_token_page_output' );
}

function api_woocommerce_token_page_output(){
	?>
	<div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>

		<form action="options.php" method="POST">
			<?php
				settings_fields( 'option_group' );
				do_settings_sections( 'primer_page' );
				submit_button();
			?>
		</form>
	</div>
	<?php
}

add_action('admin_init', 'plugin_settings');
function plugin_settings(){
	register_setting( 'option_group', 'callback_api_wv', 'sanitize_callback' );

	add_settings_section( 'section_id', 'Основные настройки', '', 'primer_page' ); 

	add_settings_field('primer_field1', 'URL Callback', 'fill_primer_field1', 'primer_page', 'section_id' );
}

function fill_primer_field1(){
	$val = get_option('callback_api_wv');
	$val = $val ? $val['input'] : null;
	?>
	<input type="text" name="callback_api_wv[input]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}

function sanitize_callback( $options ){ 
	foreach( $options as $name => & $val ){
		if( $name == 'input' )
			$val = strip_tags( $val );


	}

	return $options;
}

function cartSubmit() {
	if ( is_cart() ) {
		if(isset($_GET['quick_checkout'])){
?>
<script>

//document.body.style.display = "none";

$('body > :not(.loader)').hide();
$('.loader').appendTo('body');

function getPayYUrl(){
var form = document.forms.paymentform;
/*
var form = document.forms.paymentform;
form.submit();
*/
var elems = form.getElementsByClassName("button");
elems[0].click();
}

setTimeout(getPayYUrl, 300);

</script>
<?php
include 'loader.html';
		}
}

?>
<?php

//$homepage = file_get_contents('https://fialka.black/wp-content/plugins/woocommerce-pay-url-order/loader.html');
//echo $homepage;

}
add_action( 'wp_footer', 'cartSubmit' );


function wph_hide_page_admin($query) {
	if (!is_admin()) return $query;
	global $pagenow;
	if('edit.php' == $pagenow && (get_query_var('post_type') 
        && 'product' == get_query_var('post_type')))
            $query->set('post__not_in', array(283) );
	return $query;
}
add_action('pre_get_posts' ,'wph_hide_page_admin');

function exc_post($query) {
if (1)
{$query->set('post__not_in', array(283) );} // в скобочках id поста (записи)
return $query; }
add_filter('pre_get_posts','exc_post');

