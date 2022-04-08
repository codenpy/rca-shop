<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order">

    <?php
        if ( $order->get_user_id() != get_current_user_id() ) {
            return;
        }

    ?>

	<?php if ( $order ) : ?>
		<div class="row">
			<div class="col-lg-12">
				<?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

				<?php if ( $order->has_status( 'failed' ) ) : ?>

					<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'edumall' ); ?></p>

					<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
						<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>"
						   class="button pay"><?php esc_html_e( 'Pay', 'edumall' ); ?></a>
						<?php if ( is_user_logged_in() ) : ?>
							<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"
							   class="button pay"><?php esc_html_e( 'My account', 'edumall' ); ?></a>
						<?php endif; ?>
					</p>

				<?php else : ?>

					<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'edumall' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

					<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
						<li class="woocommerce-order-overview__order order">
							<span class="order-overview-label"><?php esc_html_e( 'Order number:', 'edumall' ); ?></span>
							<span
								class="order-overview-value"><?php echo '' . $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</li>

						<li class="woocommerce-order-overview__date date">
							<span class="order-overview-label"><?php esc_html_e( 'Date:', 'edumall' ); ?></span>
							<span
								class="order-overview-value"><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</li>

						<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
							<li class="woocommerce-order-overview__email email">
								<span class="order-overview-label"><?php esc_html_e( 'Email:', 'edumall' ); ?></span>
								<span
									class="order-overview-value"><?php echo '' . $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							</li>
						<?php endif; ?>

						<li class="woocommerce-order-overview__total total">
							<span class="order-overview-label"><?php esc_html_e( 'Total:', 'edumall' ); ?></span>
							<span
								class="order-overview-value"><?php echo '' . $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</li>

						<?php if ( $order->get_payment_method_title() ) : ?>
							<li class="woocommerce-order-overview__payment-method method">
								<span
									class="order-overview-label"><?php esc_html_e( 'Payment method:', 'edumall' ); ?></span>
								<span
									class="order-overview-value"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></span>
							</li>
						<?php endif; ?>
					</ul>
                    <br>

                    <!--Downloads ordered products-->
                    <?php do_action( 'edumall_woocommerce_after_thankyou', $order->get_id() ); ?>


                    <h2 class="woocommerce-column__title">Course successfully enrolled</h2>
                    <?php
                    $order = new WC_Order( $order->get_id() );
                    $items = $order->get_items();

                    foreach($items as $item) {
                        $product_id = $item->get_product_id();
                        $associated_courses = get_field('associated_courses', $product_id);
                        if (isset($associated_courses) && is_array($associated_courses) && count($associated_courses) && $associated_courses != '') {
                            foreach ($associated_courses as $course) {
                                if (($order->has_status('completed') == 'completed' || $order->has_status('complete'))) : ?>
                                    <div class="associated-courses">
                                        <a style="margin-top:10px" class="button" href="<?php echo get_permalink($course); ?>"><?php echo get_post_field('post_title', $course); ?></a>
                                    </div>

                                <?php
                                endif;
                            }
                        }

                    } ?>


				<?php endif; ?>

				<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
				<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

				<?php
				$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();

				if ( $show_customer_details ) {
					wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
				}
				?>
			</div>
		</div>


	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'edumall' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

	<?php endif; ?>

</div>
