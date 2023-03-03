<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

?>

<div class="product__checkout">

	<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

		<?php if ( $checkout->get_checkout_fields() ) : ?>

			<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

			<div class="row" id="customer_details">

				<div class="col-lg-4 checkout__item checkout__item_customer d-flex">
					<div class="item__inner w-100">
						<h5 class="item__title">Покупатель</h5>

						<div class="woocommerce-billing-fields">
							<?php if ( wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>

								<h3><?php esc_html_e( 'Billing &amp; Shipping', 'woocommerce' ); ?></h3>

							<?php else : ?>

							<?php endif; ?>

							<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

							<div class="woocommerce-billing-fields__field-wrapper">
								<?php
								$fields = $checkout->get_checkout_fields( 'billing' );

								foreach($fields as $key=>$field) {
								 if ($key=='billing_first_name') {
								 	woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
								 } elseif ($key=='billing_phone') {
								 	woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
								 } elseif ($key=='billing_email') {
								 	woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
								 }
								
								}

								?>
							</div>

							<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
						</div>

					</div>
				</div>

				<div class="col-lg-4 checkout__item checkout__item_address d-flex">
					<div class="item__inner w-100">
						<h5 class="item__title">Адрес доставки</h5>

						<div class="woocommerce-billing-fields__field-wrapper">
							<?php
							$fields = $checkout->get_checkout_fields( 'billing' );

							foreach($fields as $key=>$field) {
							 if ($key=='billing_address_1') {
							 	woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
							 } elseif ($key=='billing_postcode') {
							 	woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
							 } elseif ($key=='billing_new_fild11') {
							 	woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
							 }
							
							}

							?>
						</div>

					</div>
				</div>

				<div class="col-lg-4 checkout__payment d-flex">
					<div class="item__inner w-100">
						<h5 class="item__title">Способ оплаты</h5>

						<!-- <div class="message__mobile">
							<p>
								<span>
									<textarea placeholder="Комментарий"></textarea>
								</span>
								Оформить заказ
							</p>
						</div> -->

						<?php do_action( 'woocommerce_checkout_order_review' ); ?>

					</div>
				</div>

				<div class="col-12 checkout__order">
					<div class="order__inner">
						<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_orderrr" value="Оформить заказ" data-value="Оформить заказ">Оформить заказ</button>' ); ?>
						<a href="/#maincatalog" class="btn btn__line">Перейти в каталог</a>
					</div>
				</div>

			</div>

			<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

		<?php endif; ?>
		
		<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
		
		<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

		<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

	</form>

</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
