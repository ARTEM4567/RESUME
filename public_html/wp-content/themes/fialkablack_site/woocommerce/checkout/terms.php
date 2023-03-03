<?php
/**
 * Checkout terms and conditions area.
 *
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( apply_filters( 'woocommerce_checkout_show_terms', true ) && function_exists( 'wc_terms_and_conditions_checkbox_enabled' ) ) {
	do_action( 'woocommerce_checkout_before_terms_and_conditions' );

	?>
	<div class="policy">
		<label class="wrapper">
			Я согласен на обработку персональных данных
			<input type="checkbox" checked="checked" name="payment_policy" required>
			<span class="checkmark"></span>
		</label>
	</div>

	<?php

	do_action( 'woocommerce_checkout_after_terms_and_conditions' );
}
