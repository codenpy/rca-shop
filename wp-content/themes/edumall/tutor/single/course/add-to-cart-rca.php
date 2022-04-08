<?php

$product_id = tutor_utils()->get_course_product_id();
$product    = wc_get_product( $product_id );
?>

<button
    type="submit"
    id="rca-ajax-add-to-cart-button-<?php echo $product->get_id(); ?>"
    onclick="rcaAjaxBtnHandler(<?php echo $product->get_id(); ?>)"
    class="single_add_to_cart_button ajax_add_to_cart tutor-button alt"
>
    <i class="far fa-shopping-cart"></i>
    <?php echo esc_html( $product->single_add_to_cart_text() ); ?>
</button>
