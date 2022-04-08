<?php
/**
 * A single course loop rating
 *
 * @since   v.1.0.0
 * @author  themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined('ABSPATH') || exit;

global $edumall_course;

$course_rating = $edumall_course->get_rating();
$rating_count = intval($course_rating->rating_count);

// Students count
$course_id = $edumall_course->get_id();
$students_count_field_value = get_field('students_count', $course_id);
$students_count = ($students_count_field_value > 0) ? $students_count_field_value : 0;

?>

<div class="course-loop-rating">
    <div class="tm-star-rating style-01"><span class="tm-star-full"></span><span class="tm-star-full"></span><span class="tm-star-full"></span><span class="tm-star-full"></span><span class="tm-star-full"></span></div>
    <span class="rating-count"><?php echo esc_html($students_count); ?>  STUDENTS</span>
</div>