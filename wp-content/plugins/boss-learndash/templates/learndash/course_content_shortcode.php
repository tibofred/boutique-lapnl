<?php
/**
 * Displays content of course
 *
 * Available Variables:
 * $course_id 		: (int) ID of the course
 * $course 		: (object) Post object of the course
 * $course_settings : (array) Settings specific to current course
 *
 * $courses_options : Options/Settings as configured on Course Options page
 * $lessons_options : Options/Settings as configured on Lessons Options page
 * $quizzes_options : Options/Settings as configured on Quiz Options page
 *
 * $user_id 		: Current User ID
 * $logged_in 		: User is logged in
 * $current_user 	: (object) Currently logged in user object
 *
 * $course_status 	: Course Status
 * $has_access 	: User has access to course or is enrolled.
 * $has_course_content		: Course has course content
 * $lessons 		: Lessons Array
 * $quizzes 		: Quizzes Array
 * $lesson_progression_enabled 	: (true/false)
 *
 * @since 2.1.0
 *
 * @package LearnDash\Course
 */
?>

<?php
/**
 * Show Lesson List
 */
if ( $has_course_content ) {
	?>
	<div id="learndash_course_content"><?php
		if ( !empty( $lessons ) ) {
			?>
			<div id="learndash_lessons">

				<div id='lesson_heading'>
					<span><?php echo LearnDash_Custom_Label::get_label( 'lessons' ) ?></span>
					<?php if ( $has_topics ) : ?>
						<div class='expand_collapse'>
							<a href='#' onClick='jQuery("#learndash_post_<?php echo $course_id; ?> .learndash_topic_dots").slideDown(); return false;'><?php _e( 'Expand All', 'boss-learndash' ); ?></a> | <a href='#' onClick='jQuery("#learndash_post_<?php echo $course_id; ?> .learndash_topic_dots").slideUp(); return false;'><?php _e( 'Collapse All', 'boss-learndash' ); ?></a>
						</div>
					<?php endif; ?>
				</div>

				<div id="lessons_list"><?php
					foreach ( $lessons as $lesson ) {

						/* Lesson Topis */
						$topics		 = @$lesson_topics[ $lesson[ 'post' ]->ID ];
						$in_progress = false;

						if ( !empty( $topics ) ) {
							foreach ( $topics as $key => $topic ) {
								if ( !empty( $topic->completed ) )
									$in_progress = true;
							}
						}
						?>

						<div class="lesson post-<?php echo $lesson[ "post" ]->ID; ?> <?php echo $lesson[ "sample" ]; ?> <?php echo (empty( $topics )) ? 'no-topics' : 'has-topics' ?>">
							<h4>
								<a class="<?php echo ($in_progress && $lesson[ "status" ] == 'notcompleted') ? 'in-progress' : $lesson[ "status" ]; ?>" href="<?php echo learndash_get_step_permalink( $lesson['post']->ID, $course_id ) ?>"><?php echo $lesson[ "post" ]->post_title; ?></a>
							</h4>

                            <?php  /* Not available message for drip feeding lessons */ ?>
                            <?php if ( ! empty( $lesson['lesson_access_from'] ) ) : ?>
                                <?php
                                SFWD_LMS::get_template(
                                    'learndash_course_lesson_not_available',
                                    array(
                                        'user_id'					=>	$user_id,
                                        'course_id'					=>	learndash_get_course_id( $lesson['post']->ID ),
                                        'lesson_id'					=>	$lesson['post']->ID,
                                        'lesson_access_from_int'	=>	$lesson['lesson_access_from'],
                                        'lesson_access_from_date'	=>	learndash_adjust_date_time_display( $lesson['lesson_access_from'] ),
                                        'context'					=>	'course_content_shortcode'
                                    ), true
                                );
                            ?>
                            <?php endif; ?>

							<?php
							if ( !empty( $topics ) ) {
								?>
								<div id="learndash_topic_dots-<?php echo $lesson[ 'post' ]->ID; ?>" class="learndash_topic_dots type-list">
									<ul>
										<?php
										$odd_class = '';
										foreach ( $topics as $key => $topic ) {
											$odd_class		 = empty( $odd_class ) ? 'nth-of-type-odd' : '';
											$completed_class = empty( $topic->completed ) ? 'topic-notcompleted' : 'topic-completed';
											?>
											<li class="<?php echo $odd_class; ?>">
												<span class="topic_item">
													<a class="<?php echo $completed_class; ?>" href="<?php echo learndash_get_step_permalink( $topic->ID, $course_id ); ?>" title="<?php echo $topic->post_title; ?>">
														<?php echo $topic->post_title; ?>
													</a>
												</span>
											</li><?php
										}
										?>
									</ul>
								</div><?php
							}
							?>
						</div><?php
					}
					?>
				</div>

			</div><?php

			global $course_lessons_results;
			if ( isset( $course_lessons_results['pager'] ) ) {
				echo SFWD_LMS::get_template(
					'learndash_pager.php',
					array(
						'pager_results' => $course_lessons_results['pager'],
						'pager_context' => 'course_content'
					)
				);
			}
		}

	if ( ( isset( $course_lessons_results['pager'] ) ) && ( !empty( $course_lessons_results['pager'] ) ) ) {
		if ( $course_lessons_results['pager']['paged'] == $course_lessons_results['pager']['total_pages'] ) {
			$show_course_quizzes = true;
		} else {
			$show_course_quizzes = false;
		}
	} else {
		$show_course_quizzes = true;
	}

		/* Show Quiz List */
	if ( $show_course_quizzes == true ) {
		if (!empty($quizzes)) {
			?>
            <div id='learndash_quizzes'>
                <div id="quiz_heading"><span><?php echo LearnDash_Custom_Label::get_label('quizzes') ?></span></div>
                <div id="quiz_list">
					<?php foreach ($quizzes as $quiz) { ?>
                        <div id="post-<?php echo $quiz["post"]->ID; ?>" class="<?php echo $quiz["sample"]; ?>">
                            <h4>
                                <a class="<?php echo $quiz["status"]; ?>"
                                   href="<?php echo learndash_get_step_permalink($quiz['post']->ID, $course_id); ?>"><?php echo $quiz["post"]->post_title; ?></a>
                            </h4>
                        </div>
					<?php } ?>
                </div>
            </div>
			<?php
		}
	}
		?>

	</div><?php
}
