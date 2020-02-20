<?php
$show_copyright	 = boss_get_option( 'footer_copyright_content' );

if ( $show_copyright ) {
	?>

	<div class="footer-credits <?php if ( !has_nav_menu( 'secondary-menu' ) ) : ?>footer-credits-single<?php endif; ?>">
		© 2018 - Tous droits réservés I.DCom 
	</div>

	<?php
}