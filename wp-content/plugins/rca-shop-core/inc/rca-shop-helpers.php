<?php
defined('ABSPATH') || exit;

if (!class_exists('RCA_Shop_Helper')) {
    class RCA_Shop_Helper
    {
        public static function rca_shop_top_image($template_position = 'below', $class)
        {
            $rca_top_image = carbon_get_theme_option('rca_top_image');
            $rca_show_top_image_home = carbon_get_theme_option('rca_show_top_image_home');

            if ($rca_show_top_image_home == 'yes') {
                if (is_front_page()) { ?>
                    <div id="page-slider" class="page-slider <?php echo $class; ?>">
                        <img src="<?php echo $rca_top_image; ?>" alt="">
                    </div>
                <?php } ?>

                <?php
            } else { ?>
                <div id="page-slider" class="page-slider <?php echo $class; ?>">
                    <img src="<?php echo $rca_top_image; ?>" alt="">
                </div>
            <?php }
        }


        public static function rca_single_course_enroll_box()
        {
            global $edumall_course;

            $is_administrator = current_user_can('administrator');
            $is_instructor = tutor_utils()->is_instructor_of_this_course();
            $course_content_access = (bool)get_tutor_option('course_content_access_for_ia');

            if ($edumall_course->is_enrolled()) {
                tutor_load_template('single.course.custom.enrolled-action-buttons');
            } elseif ($course_content_access && ($is_administrator || $is_instructor)) {
                tutor_load_template('single.course.continue-lesson');
            } else {
                tutor_load_template('single.course.add-to-cart');

            }

            return true;
        }


        public static function rca_course_price_html()
        {
            $is_purchasable = tutor_utils()->is_course_purchasable();
            $price = apply_filters('get_tutor_course_price', null, get_the_ID());
            ?>
            <?php if ($is_purchasable && $price) : ?>
            <div class="rca-single-course-price">
                <?php
                $product_id = tutor_utils()->get_course_product_id( get_the_ID() );
                $regular_price     = get_post_meta( $product_id, '_regular_price', true );
                $sale_price     = get_post_meta( $product_id, '_sale_price', true );
                $you_save_price = (int)$regular_price - (int)$sale_price;
                //$you_save_price = number_format((float)$you_save_price, 2, '.', '');

                echo '', $price;

                $badge_format = esc_html__('You Save %s', 'edumall');
                $badge_text = Edumall_Tutor::instance()->get_course_price_badge_text(get_the_ID(), $badge_format);
                if (!empty($badge_text)) {
                    echo '<span class="course-price-badge onsale">' . $badge_text . ' ($'.$you_save_price.'<span class="rca-decimals-separator">.00</span>)</span>';
                }
                ?>
            </div>
        <?php else : ?>
            <div class="tutor-price course-free">
                <?php esc_html_e('Free', 'edumall'); ?>
            </div>
        <?php
        endif;
        }


        public static function rca_single_course_add_to_cart_section_html()
        { ?>

            <div class="rca-single-course-section"
                 style="background-image: url(//i.imgur.com/ZCcncu9.jpg)">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="banner-content">
                                <h3 class="course-title">Click the <span style="color: #c99542">"Add To Cart"</span>
                                    button to order <span style="color: #c99542"><?php the_title(); ?></span> now.</h3>
                                <div class="course-add-to-cart" style="display: inline-block">
                                    <?php RCA_Shop_Helper::rca_single_course_enroll_box(); ?>
                                </div>

                                <div class="course-price">
                                    <?php RCA_Shop_Helper::rca_course_price_html(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php }
        
        
        public static function rca_learn_anytime_anywhere_any_device_html()
        {
            echo do_shortcode('[elementor-template id="57169"]');
        }


        public static function rca_add_to_cart_button_html( $product_id )
        { ?>
            <div class="rca-custom-add-to-cart-button">
                <button id="rca-ajax-add-to-cart-button-<?php echo $product_id; ?>" onclick="rcaAjaxBtnHandler(<?php echo $product_id; ?>)" type="submit"  class="action_button add_to_cart">
                    <span class="text">Add to Cart <i class="fa fa-chevron-right" style="margin-left: 15px"></i></span>
                </button>
            </div>
        <?php }




    } // Class end

    new RCA_Shop_Helper();

}
