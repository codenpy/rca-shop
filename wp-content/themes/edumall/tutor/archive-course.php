<?php
/**
 * Template for displaying courses
 *
 * @since   v.1.0.0
 *
 * @author  Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.5.8
 */

defined('ABSPATH') || exit;

get_header();



function bleye_update_column_width_condition( $control_stack, $args  ) {
//    echo '<pre>';
//    print_r($control_stack->get_active_settings());
//    echo '</pre>';
    //die();

    if (!is_front_page()) {
        $course_category = get_queried_object();
        $control_stack->set_settings('query_include_term_ids', [0 => $course_category->term_id]);
    }
}
add_action( 'elementor/element/tm-course/query_section/before_section_end', 'bleye_update_column_width_condition', 10, 2 );


echo do_shortcode('[elementor-template id="56184"]');
?>

<?php get_footer();