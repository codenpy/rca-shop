<?php

/**
 * @package       RCA|ajax add to cart
 * @version       1.4.3
 *
 * @theme-since   1.0.0
 * @theme-version 2.2.0
 */

defined('ABSPATH') || exit;

global $edumall_course;

$product_id = tutor_utils()->get_course_product_id();
$product = wc_get_product($product_id);
?>

    <div class="tutor-course-purchase-box">

        <?php
        if ($edumall_course->is_enrolled()) {
            $lesson_url = tutor_utils()->get_course_first_lesson();
            $completed_lessons = tutor_utils()->get_completed_lesson_count_by_course();
            if ($lesson_url) { ?>
                <a href="<?php echo esc_url($lesson_url); ?>" class="rca-start-course-button-loop-page">
                    <?php
                    if ($completed_lessons) {
                        esc_html_e('Continue to lesson', 'edumall');
                    } else {
                        esc_html_e('START COURSE', 'edumall');
                    }
                    ?>
                </a>
            <?php }
        } else { ?>

            <?php if (tutor_utils()->is_course_added_to_cart($product_id, true)) : ?>
                <a href="<?php echo wc_get_cart_url(); ?>">
                    <button type="submit" id="rca-ajax-add-to-cart-button" class="rca-add-to-cart-button">
                        VIEW CART
                    </button>
                </a>
            <?php else : ?>
                <button type="submit" id="rca-ajax-add-to-cart-button-<?php echo $product_id; ?>"
                        class="rca-add-to-cart-button"
                        onclick="rcaAjaxBtnHandler(<?php echo $product_id; ?>)"
                >
                    <i id="rca-ajax-loading-icon-<?php echo $product_id; ?>"
                       style="display: none; margin-right: 5px; font-weight: 800" class='fa fa-spinner fa-spin'></i>
                    ADD TO CART
                    <i style="margin-left: 5px;" class="fa fa-chevron-right"></i>
                </button>
            <?php endif; ?>

        <?php } ?>

    </div>
<?php

