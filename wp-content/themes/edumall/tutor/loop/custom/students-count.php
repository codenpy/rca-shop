<?php
/**
 * Course loop Students count
 */

defined( 'ABSPATH' ) || exit;

global $edumall_course;

$course_id = $edumall_course->get_id();
$students_count_field_value = get_field('students_count', $course_id);
$students_count = ($students_count_field_value > 0) ? $students_count_field_value : 0;

?>
<div class="course-loop-badge-level all_levels">
    <span class="badge-text"><?php echo esc_html( $students_count ); ?> Students</span>
</div>
