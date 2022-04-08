<?php
defined('ABSPATH') || exit;

global $edumall_course;

//Redirect non logged in users
$gm_igor_super_pack = 55639;
$gm_igor_super_pack_red = 'https://chess-teacher.net/gm-igor-super-pack-v3/';

$grand_positional_understanding = 55543;
$grand_positional_understanding_red = 'https://chess-teacher.net/the-grandmasters-positional-understanding/';

$secrets_strong_chess_players = 55610;
$secrets_strong_chess_players_red = 'https://chess-teacher.net/the-secrets-of-strong-players/';

$secrets_strong_players = 54913;
$secrets_strong_players_red = 'https://chess-teacher.net/the-secrets-of-strong-players/';

$calculate_till_mate = 54044;
$calculate_till_mate_red = 'https://chess-teacher.net/calculate-till-mate/';

// Utility function to check if a customer has bought a product (Order with "completed" status only)
function customer_has_bought_product($product_id, $user_id = 0)
{
    global $wpdb;
    $customer_id = $user_id == 0 || $user_id == '' ? get_current_user_id() : $user_id;
    $status = 'wc-completed';

    if (!$customer_id)
        return false;

    // Count the number of products
    $count = $wpdb->get_var("
        SELECT COUNT(woim.meta_value) FROM {$wpdb->prefix}posts AS p
        INNER JOIN {$wpdb->prefix}postmeta AS pm ON p.ID = pm.post_id
        INNER JOIN {$wpdb->prefix}woocommerce_order_items AS woi ON p.ID = woi.order_id
        INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS woim ON woi.order_item_id = woim.order_item_id
        WHERE p.post_status = '$status'
        AND pm.meta_key = '_customer_user'
        AND pm.meta_value = $customer_id
        AND woim.meta_key IN ('_product_id','_variation_id')
        AND woim.meta_value = $product_id
    ");

    // Return a boolean value if count is higher than 0
    return $count > 0 ? true : false;
}

if (!customer_has_bought_product(tutor_utils()->get_course_product_id())) {
    // customer has not bought the product
    if (get_the_ID() === $gm_igor_super_pack) {
        wp_redirect($gm_igor_super_pack_red);
        exit();
    }
    if (get_the_ID() === $grand_positional_understanding) {
        wp_redirect($grand_positional_understanding_red);
        exit();
    }
    if (get_the_ID() === $secrets_strong_chess_players) {
        wp_redirect($secrets_strong_chess_players_red);
        exit();
    }
    if (get_the_ID() === $secrets_strong_players) {
        wp_redirect($secrets_strong_players_red);
        exit();
    }
    if (get_the_ID() === $calculate_till_mate) {
        wp_redirect($calculate_till_mate_red);
        exit();
    }
}


$title_bar_type = Edumall_Global::instance()->get_title_bar_type();
$top_info_classes = 'tutor-full-width-course-top tutor-course-top-info';

$isLoggedIn = is_user_logged_in();

$monetize_by = tutils()->get_option('monetize_by');
$enable_guest_course_cart = tutor_utils()->get_option('enable_guest_course_cart');

$is_public = get_post_meta(get_the_ID(), '_tutor_is_public_course', true) == 'yes';
$is_purchasable = tutor_utils()->is_course_purchasable();

$required_loggedin_class = '';
if (!$isLoggedIn && !$is_public) {
    $required_loggedin_class = apply_filters('tutor_enroll_required_login_class', 'open-popup-login');
}
if ($is_purchasable && $monetize_by === 'wc' && $enable_guest_course_cart) {
    $required_loggedin_class = '';
}

$tutor_form_class = apply_filters('tutor_enroll_form_classes', ['tutor-enroll-form']);

$tutor_course_sell_by = apply_filters('tutor_course_sell_by', null);

$product_id = tutor_utils()->get_course_product_id();
$product = wc_get_product($product_id);

// Students count
$course_id = $edumall_course->get_id();
$students_count_field_value = get_field('students_count', $course_id);
$students_count = ($students_count_field_value > 0) ? $students_count_field_value : 0;

// Subtitle acf field
$course_sub_title = get_field('course_sub_title', $course_id);

$disable_course_duration = get_tutor_option('disable_course_duration');
$course_duration = Edumall_Tutor::instance()->get_course_duration_context();

$course_image = wp_get_attachment_image_src(get_post_thumbnail_id($course_id), 'full');

$current_category_id = wp_get_post_terms( get_the_ID(), 'course-category' )[0]->term_id;
$thumbnail_id = get_term_meta( $current_category_id, 'thumbnail_id', true );

$category_image = '';
$banner_overlay = true;
if ( $thumbnail_id ) {
    $category_image = wp_get_attachment_image_src( $thumbnail_id, 'full' );
    $banner_overlay = false;
}
?>

<div class="page-content" style="margin-top: 0; margin-bottom: 0;">

    <?php do_action('tutor_course/single/before/wrap'); ?>

    <div <?php tutor_post_class(); ?>>


        <!-- <div class="rca-single-course-top-banner"
             style="background-image: url(<?php // echo $course_image[0]; ?>)"> -->
        <div class="rca-single-course-top-banner" style="background-image: url(<?php echo $category_image[0]; ?>)">
            <?php if ($banner_overlay) { ?>
                <div class="banner-overlay"></div>
            <?php } ?>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="banner-content">
                            <h1 class="course-title"><?php the_title(); ?></h1>
                            <?php if (!empty($course_sub_title))  { ?>
                                <div class="course_sub_title"><?php echo esc_html($course_sub_title);?></div>
                            <?php } ?>
                            <div class="course-reviews">
                                <div class="tm-star-rating style-01 review-rating"
                                     style="margin-right: 8px; margin-bottom: 15px;">
                                    <span class="tm-star-full"></span>
                                    <span class="tm-star-full"></span>
                                    <span class="tm-star-full"></span>
                                    <span class="tm-star-full"></span>
                                    <span class="tm-star-full"></span>
                                </div>
                                <span class="rating-count"
                                      style="position: relative; top: 2px; color: #f7c04d"><?php echo esc_html($students_count); ?>  STUDENTS</span>
                            </div>
                            <div class="course-add-to-cart" style="display: inline-block">
                                <?php //Edumall_Tutor::instance()->course_enroll_box(); ?>
                                <?php //tutor_load_template( 'single.course.custom.enrolled-action-buttons' ); ?>
                                <?php RCA_Shop_Helper::rca_single_course_enroll_box(); ?>
                            </div>

                            <div class="course-price">
                                <?php RCA_Shop_Helper::rca_course_price_html(); ?>
                                <!--<span class="course-save">You Save 80% ($402.00)</span>-->
                            </div>

                            <div class="separate-line"></div>

                            <div class="course-features">
                                <ul>
                                    <li>
                                        <p>COURSE FORMAT</p>
                                        <span>Download + streaming</span>
                                    </li>
                                    <?php
                                    if (!empty($course_duration) && !$disable_course_duration) { ?>
                                        <li>
                                            <p>COURSE LENGTH</p>
                                            <span><?php echo esc_html($course_duration); ?></span>
                                        </li>
                                    <?php }
                                    ?>
                                    <li>
                                        <p>PRACTICE</p>
                                        <span>Included</span>
                                    </li>
                                    <li>
                                        <p>100% SATISFACTION</p>
                                        <span>Money-back guarantee</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="tutor-full-width-course-body" style="padding-bottom: 40px">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="tutor-single-course-main-content tm-sticky-column">

                            <?php do_action('tutor_course/single/before/inner-wrap'); ?>

                            <?php if ($edumall_course->is_enrolled()) : ?>
                                <?php tutor_course_completing_progress_bar(); ?>
                            <?php endif; ?>

                            <?php Edumall_Tutor::instance()->course_prerequisites(); ?>
                            <?php tutor_course_content(); ?>
                            <?php RCA_Shop_Helper::rca_add_to_cart_button_html($product_id); ?>
                            <?php tutor_course_benefits_html(); ?>
                            <?php RCA_Shop_Helper::rca_learn_anytime_anywhere_any_device_html(); ?>
                            <?php tutor_course_requirements_html(); ?>
                            <?php tutor_course_target_audience_html(); ?>
                            <?php tutor_course_topics(); ?>

                            <?php if ($edumall_course->is_viewable()) : ?>
                                <?php get_tutor_posts_attachments(); ?>
                                <?php tutor_course_question_and_answer(); ?>
                            <?php endif; ?>

                            <?php //tutor_course_instructors_html(); ?>

                            <?php if ($edumall_course->is_viewable()) : ?>
                                <?php tutor_course_announcements(); ?>
                            <?php endif; ?>

                            <?php if ($edumall_course->is_viewable() && $edumall_course->has_classroom_stream()) : ?>
                                <?php do_action('tutor_course/single/enrolled/google-classroom-stream', get_the_ID()); ?>
                            <?php endif; ?>

                            <?php if ($edumall_course->is_enrolled()): ?>
                                <?php do_action('tutor_course/single/enrolled/gradebook', get_the_ID()); ?>
                            <?php endif; ?>


                            <?php do_action('tutor_course/single/after/inner-wrap'); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php RCA_Shop_Helper::rca_single_course_add_to_cart_section_html(); ?>

        <div class="rca-single-course-reviews" style="padding-top: 80px">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <?php tutor_course_target_reviews_html(); ?>
                    </div>
                </div>
            </div>
        </div>

        <!--Related course template-->
        <?php echo do_shortcode('[elementor-template id="56215"]'); ?>


    </div>

    <?php do_action('tutor_course/single/after/wrap'); ?>

</div>