<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */
?>
</div><!-- #main .wrapper -->

</div><!-- #page -->

</div> <!-- #inner-wrap -->

</div><!-- #main-wrap (Wrap For Mobile) -->

<footer id="colophon" role="contentinfo">

	<?php get_template_part( 'template-parts/footer-widgets' ); ?>

	<div class="footer-inner-bottom">

		<div class="footer-inner">
			<?php get_template_part( 'template-parts/footer-copyright' ); ?>
			<?php get_template_part( 'template-parts/footer-links' ); ?>
		</div><!-- .footer-inner -->

	</div><!-- .footer-inner-bottom -->

	<?php do_action( 'bp_footer' ) ?>

</footer><!-- #colophon -->
</div><!-- #right-panel-inner -->
</div><!-- #right-panel -->

</div><!-- #panels -->

<?php wp_footer(); ?>
<?php $current_user = wp_get_current_user();$logi = $current_user->user_login;?>
<!--script>
jQuery( document ).ready(function($) {
	if($('.item-list-tabs').length) {
			
		<?php
		/*if($_GET['action'] == 'content') {
			?>
			$('.item-list-tabs ul').append('<li id="mepr-custom-li"><a id="mepr-bp-custom" href="<?php echo get_site_url();?>/membres/<?php echo $logi;?>/membership/?action=content" style="font-weight:bold">Gratuité</a></li>');	
			$('#mepr-bp-info-personal-li, #mepr-bp-subscriptions-personal-li, #mepr-bp-subscriptions').removeClass('current');
			$('#mepr-bp-info-personal-li, #mepr-bp-subscriptions-personal-li, #mepr-bp-subscriptions').removeClass('selected');
			<?php
		} else {
			?>
			$('.item-list-tabs ul').append('<li id="mepr-custom-li"><a id="mepr-bp-custom" href="<?php echo get_site_url();?>/membres/<?php echo $logi;?>/membership/?action=content">Gratuité</a></li>');
			<?php
		}*/
		?>
	}
});

</script-->	
<?php
$postid = get_the_id();
if($postid == 2226) {
	?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>

<!-- jQuery Modal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

<style>
.blocker {
	z-index: 1000;
}
.blocker .modal {
	max-width: 800px;
}
</style>
	<!-- Modal HTML embedded directly into document -->

<!-- Link to open the modal -->
	<?php
	$args= array('post_type' => 'sfwd-courses',
            'post_status' => 'publish',
            'order' => 'DESC',
            'orderby' => 'ID',
            'mycourses' => false);								
	$loop = new WP_Query( $args );
	while ( $loop->have_posts() ) : $loop->the_post();
		$lesson_id = get_the_ID();
		?>		
		<div id="modal_<?php echo $lesson_id;?>" class="modal">
			<img style="width: 100%;" src="<?php echo get_the_post_thumbnail_url();?>">	
			<div style="font-size: 18px; font-weight: bold;"><?php echo get_the_title();?></div>
			<div><?php echo get_the_excerpt() ;?></div>	
		</div>
		
		<?php
	endwhile;
}
?>			
</body>
</html>