<?php
/**
 * Template Name: Les formations 2019

 */
get_header(); 
?>

<?php if ( is_active_sidebar('sidebar') ) : ?>
    <div class="page-right-sidebar">
<?php else : ?>
    <div class="page-full-width">
<?php endif; ?>

        <div id="primary" class="site-content">

            <div id="woo-content" role="main">

                <?php while ( have_posts() ) : the_post(); ?>
                    

<div class="menu_formations">
    <div class="mf_title">
        PNL
    </div>
    <div class="formations_lap">
        <a href="<?php echo user_logged_in_product_already_bought(12715) ?"https://boutique.lapnl.ca/courses/pnl-de-base_fr/":'#cat_pnl';?>">PNL Base </a>
        <a href="<?php echo user_logged_in_product_already_bought(15994) ?"https://boutique.lapnl.ca/courses/pnl-praticien/":'#cat_pnl';?>">PNL Praticien</a>
        <a href="<?php echo user_logged_in_product_already_bought(16002) ?"https://boutique.lapnl.ca/courses/pnl-maitre-praticien/":'#cat_pnl';?>">PNL Base </a>
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
    <?php
    if (user_logged_in_product_already_bought(12715)) {
        echo  do_shortcode('[ld_course_list mycourses="true" show_thumbnail="false" tag="Base en ligne" order="ASC" orderby="order"]');
    } else {
        echo  do_shortcode('[products ids="12715"  orderby="date" order="DESC"]');
    }
    if (user_logged_in_product_already_bought(15994)) {
        echo  do_shortcode('[ld_course_list mycourses="true" show_thumbnail="false" tag="Praticien PNL en ligne" order="ASC" orderby="order"]');
    } else {
        echo  do_shortcode('[products ids="15994"  orderby="date" order="DESC"]');
    }
    if (user_logged_in_product_already_bought(16002)) {
        echo  do_shortcode('[ld_course_list mycourses="true" show_thumbnail="false" tag="Maître Praticien en PNL en ligne" order="ASC" orderby="order"]');
    } else {
        echo  do_shortcode('[products ids="16002"  orderby="date" order="DESC"]');
    }
    ?>
</div>
<h4 id="cat_hypnose" style="font-size: 24px; color: #002D4F; border-bottom:1px solid #002D4F">Hypnose</h4>
<div class="formations_lap2">
    <?php    
    if (user_logged_in_product_already_bought(13721)) {
        echo  do_shortcode('[ld_course_list mycourses="true" show_thumbnail="false" tag="Hypnose Ericksonienne de Base" order="ASC" orderby="order"]');
    } else {
        echo  do_shortcode('[products ids="13721"  orderby="date" order="DESC"]');
    }  
    if (user_logged_in_product_already_bought(17231)) {
        echo  do_shortcode('[ld_course_list mycourses="true" show_thumbnail="false" tag="Hypnose Praticien" order="ASC" orderby="order"]');
    } else {
        echo  do_shortcode('[products ids="17231"  orderby="date" order="DESC"]');
    }
    ?>
</div>
<h4 id="cat_ema" style="font-size: 24px; color: #002D4F; border-bottom:1px solid #002D4F">EMA / AMO ™</h4>
<div class="formations_lap2">   
    <?php
    if (user_logged_in_product_already_bought(13720)) {
        echo  do_shortcode('[ld_course_list mycourses="true" show_thumbnail="false" tag="EMA / AMO™ 2" order="ASC" orderby="order"]');
    } else {
        echo  do_shortcode('[products ids="13720"  orderby="date" order="DESC"]');
    }  
    ?>
</div>
<h4 id="cat_aura" style="font-size: 24px; color: #002D4F; border-bottom:1px solid #002D4F">Aura Vision&#x2122; - Lecture d'Aura</h4>
<div class="formations_lap2"> 
    <?php
    if (user_logged_in_product_already_bought(17432)) {
        echo  do_shortcode('[ld_course_list mycourses="true" show_thumbnail="false" tag="Aura Vision™ 1" order="ASC" orderby="order"]');
    } else {
        echo  do_shortcode('[products ids="17432"  orderby="date" order="DESC"]');
    }  
    ?>  
</div>
<!--h4 id="cat_jeunesse" style="font-size: 24px; color: #002D4F; border-bottom:1px solid #002D4F">Série Jeunesse</h4>
<div class="formations_lap2">

</div-->
<h4 id="prolong" style="font-size: 24px; color: #002D4F; border-bottom:1px solid #002D4F">Certification – Prolongation</h4>
<div class="formations_lap2">
    <?php echo  do_shortcode('[products ids="17408"]');?>

</div>
                    
                <?php endwhile; // end of the loop. ?>

            </div><!-- #content -->
        </div><!-- #primary -->

    <?php if ( is_active_sidebar('sidebar') ) : 
        get_sidebar('sidebar'); 
    endif; ?>
    </div>
<?php get_footer(); ?>
