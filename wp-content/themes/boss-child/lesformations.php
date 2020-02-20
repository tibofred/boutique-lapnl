<?php
/**
 * Template Name: Les formations

 */
get_header(); 
?>

<?php if ( is_active_sidebar('sidebar') ) : ?>
    <div class="page-right-sidebar">
<?php else : ?>
    <div class="page-full-width">
<?php endif; ?>

        <div id="primary" class="site-content">
			
	
            <div id="content" role="main">

                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        
                        <header class="entry-header <?php if(is_search()){ echo 'page-header'; }?>">
                            <h1 class="entry-title <?php if(is_search()){ echo 'main-title'; }?>"><?php the_title(); ?></h1>
                        </header>

                        <div class="entry-content">
                            
                            <h3>Mes formations IDCom</h3>
                                <div class="formations_m"></div>
                            <h3>Autres formations</h3>
                            <div class="formations_o">
                            </div>
                            <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'boss' ), 'after' => '</div>' ) ); ?>
                        </div><!-- .entry-content -->

                        <footer class="entry-meta">
                            <?php edit_post_link( __( 'Edit', 'boss' ), '<span class="edit-link">', '</span>' ); ?>
                        </footer><!-- .entry-meta -->

                    </article>
                <?php endwhile; // end of the loop. ?>

            </div><!-- #content -->
        </div><!-- #primary -->

    <?php if ( is_active_sidebar('sidebar') ) : 
        get_sidebar('sidebar'); 
    endif; ?>
    </div>
<?php get_footer(); ?>
