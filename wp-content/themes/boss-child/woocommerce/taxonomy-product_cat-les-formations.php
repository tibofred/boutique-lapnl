<?php
/**
 * The Template for displaying products in a product category. Simply includes the archive template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/taxonomy-product_cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' );
/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
$bool = 0;

do_action( 'woocommerce_before_main_content', 'test_func' );
?>
<header class="woocommerce-products-header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
	<?php endif; ?>

	<?php
	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );
	?>
</header>
<div class="menu_formations">
	<div class="mf_title">
		PNL
	</div>
	<div class="formations_lap">
		<a href="<?php echo user_logged_in_product_already_bought(12715) ?"https://boutique.lapnl.ca/courses/pnl-de-base_fr/":'#cat_pnl';?>">PNL Base </a>
		<a href="<?php echo user_logged_in_product_already_bought(15994) ?"https://boutique.lapnl.ca/courses/pnl-praticien/":'#cat_pnl';?>">PNL Praticien</a>
		<a href="<?php echo user_logged_in_product_already_bought(16002) ?"https://boutique.lapnl.ca/courses/pnl-maitre-praticien/":'#cat_pnl';?>">PNL Maitre Praticien </a>
	</div>
	<div class="mf_title">
		Hypnose
	</div>
	<div class="formations_lap">
		<a href="<?php echo user_logged_in_product_already_bought(13721) ?"https://boutique.lapnl.ca/courses/hypnose-ericksonienne-de-base/":'#cat_hypnose';?>">Hypnose Base </a>
		<a href="#cat_289">Hypnose Praticien </a>
		<a href="#cat_289">Hypnose Maître Praticien</a>
	</div>
	<div class="mf_title">
		EMA / AMO ™
	</div>
	<div class="formations_lap">
		<a href="<?php echo user_logged_in_product_already_bought(13720) ?"https://boutique.lapnl.ca/courses/ema-amo-1-decouvrir-le-pouvoir-cache-des-mouvements-oculaires/":'#cat_ema';?>">EMA / AMO™ 1</a>

		<a href="#cat_288">EMA / AMO™ 2</a>
	</div>
	<div class="mf_title"> 
		Aura Vision™ - Lecture d'Aura
	</div>
	<div class="formations_lap">
		<a href="<?php echo user_logged_in_product_already_bought(17432) ?"https://boutique.lapnl.ca/courses/aura-vision-1-lecture-daura/":'#cat_aura';?>">Aura Vision™ 1 - Lecture d’Aura</a>
		<a href="#cat_aura">Aura Vision™ 2 - Lecture d’Aura</a>
		<a href="#cat_aura">Aura Vision™ 3 - Lecture d’Aura</a>
	</div>
	<!--div class="mf_title">
		Série Jeunesse
	</div>
	<div class="formations_lap"> 
		<a href="<?php echo user_logged_in_product_already_bought(17408) ?"#cat_292":'#cat_jeunesse';?>">Communiquons de façon efficace avec les enfants</a>
		<a href="#cat_288">Stratégie et créativité avec les enfants</a>
	</div-->
	<div class="mf_title">
		Certification – Prolongation
	</div>
	<div class="formations_lap">
		<a href="<?php echo user_logged_in_product_already_bought(17408) ?"#cat_292":'#cat_prolong';?>">Aura Vision™ 1 - Lecture d’Aura</a>
	</div>
</div>

<h4 id="cat_pnl" style="font-size: 24px; color: #002D4F; border-bottom:1px solid #002D4F">PNL</h4>
<div class="formations_lap2">
	<?php echo  do_shortcode('[products ids="12715, 15994,16002"  orderby="date" order="DESC"]');?>
</div>
<h4 id="cat_hypnose" style="font-size: 24px; color: #002D4F; border-bottom:1px solid #002D4F">Hypnose</h4>
<div class="formations_lap2">
	<?php echo  do_shortcode('[products ids="13721,17231"]');?>
</div>
<h4 id="cat_ema" style="font-size: 24px; color: #002D4F; border-bottom:1px solid #002D4F">EMA / AMO ™</h4>
<div class="formations_lap2">	
	<?php echo  do_shortcode('[products ids="13720"]');?>
</div>
<h4 id="cat_aura" style="font-size: 24px; color: #002D4F; border-bottom:1px solid #002D4F">Aura Vision&#x2122; - Lecture d'Aura</h4>
<div class="formations_lap2">	
	<?php echo  do_shortcode('[products ids="17432"]');?>
</div>
<!--h4 id="cat_jeunesse" style="font-size: 24px; color: #002D4F; border-bottom:1px solid #002D4F">Série Jeunesse</h4>
<div class="formations_lap2">

</div-->
<h4 id="prolong" style="font-size: 24px; color: #002D4F; border-bottom:1px solid #002D4F">Certification – Prolongation</h4>
<div class="formations_lap2">
	<?php echo  do_shortcode('[products ids="17408"]');?>

</div>
<?php
//	get_categories_idcom(278);		

       

get_footer( 'shop' );