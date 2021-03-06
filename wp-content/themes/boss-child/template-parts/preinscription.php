<?php
/**
 * Template Name: Preinscription

 */

?><!DOCTYPE html>

<html <?php language_attributes(); ?>>

	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="msapplication-tap-highlight" content="no"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<!-- BuddyPress and bbPress Stylesheets are called in wp_head, if plugins are activated -->
		<?php wp_head(); ?>
	</head>

	<?php
	global $rtl;
	$logo	 = ( boss_get_option( 'logo_switch' ) && boss_get_option( 'boss_logo', 'id' ) ) ? '1' : '0';
	$inputs	 = ( boss_get_option( 'boss_inputs' ) ) ? '1' : '0';
	$boxed	 = boss_get_option( 'boss_layout_style' );

    $header_style = boss_get_option('boss_header');
//    $boxed	 = 'fluid';
	?>

	<body <?php body_class(); ?> data-logo="<?php echo $logo; ?>" data-inputs="<?php echo $inputs; ?>" data-rtl="<?php echo ($rtl) ? 'true' : 'false'; ?>" data-header="<?php echo $header_style; ?>">

		<?php do_action( 'buddyboss_before_header' ); ?>

		<div id="scroll-to"></div>

		<header id="masthead" class="site-header" data-infinite="<?php echo ( boss_get_option( 'boss_activity_infinite' ) ) ? 'on' : 'off'; ?>">

			<div class="header-wrap">
				<div class="header-outher">
					<div class="header-inner">
						<?php get_template_part( 'template-parts/header-fluid-layout-column' ); ?>
						<?php if( '1' == $header_style ){ ?>
						<?php get_template_part( 'template-parts/header-middle-column' ); ?>
						<?php } ?>
						<?php get_template_part( 'template-parts/header-profile' ); ?>
					</div><!-- .header-inner -->
				</div><!-- .header-wrap -->
			</div><!-- .header-outher -->

			<div id="mastlogo">
				<?php get_template_part( 'template-parts/header-logo' ); ?>
				<p class="site-description"><?php bloginfo( 'description' ); ?></p>
			</div><!-- .mastlogo -->

		</header><!-- #masthead -->

		<?php do_action( 'buddyboss_after_header' ); ?>

		<?php get_template_part( 'template-parts/header-mobile' ); ?>

		<!-- #panels closed in footer-->
		<div id="panels" class="<?php echo (boss_get_option( 'boss_adminbar' )) ? 'with-adminbar' : ''; ?>">

				
					<div id="main-wrap"> <!-- Wrap for Mobile content -->
						<div id="inner-wrap"> <!-- Inner Wrap for Mobile content -->
							<?php do_action( 'buddyboss_inside_wrapper' ); ?>

							<div id="page" class="hfeed site">
								<div id="main" class="wrapper">

<div class="page-full-width">
	<div id="primary" class="site-content">
			<div id="content" role="main">
				<br/><br/><br/>
<iframe class="frame_prinscript" src="https://forms.zohopublic.com/idcominternational/form/ClientDetails/formperma/Ue5YAq02Ol4CiNCLHvH6k_Kt5kg1asaKcaTKkwB0ff8"></iframe>
</div>
</div>
</div>

<?php get_footer(); ?>