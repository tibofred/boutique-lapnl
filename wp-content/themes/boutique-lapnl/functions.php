<?php
/**
 * Boutique Lapnl functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Boutique_Lapnl
 */

if ( ! function_exists( 'boutique_lapnl_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function boutique_lapnl_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Boutique Lapnl, use a find and replace
	 * to change 'boutique-lapnl' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'boutique-lapnl', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'menu-1' => esc_html__( 'Primary', 'boutique-lapnl' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'boutique_lapnl_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif;
add_action( 'after_setup_theme', 'boutique_lapnl_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function boutique_lapnl_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'boutique_lapnl_content_width', 640 );
}
add_action( 'after_setup_theme', 'boutique_lapnl_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function boutique_lapnl_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'boutique-lapnl' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'boutique-lapnl' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'boutique_lapnl_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function boutique_lapnl_scripts() {
	wp_enqueue_style( 'boutique-lapnl-style', get_stylesheet_uri() );

	wp_enqueue_script( 'boutique-lapnl-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'boutique-lapnl-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'boutique_lapnl_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


// Register Custom Post Type
function custom_matriel() {

	$labels = array(
		'name'                  => _x( 'Matériels', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Matériel', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Matériels publicitaire', 'text_domain' ),
		'name_admin_bar'        => __( 'Post Type', 'text_domain' ),
		'archives'              => __( 'Archive matériel', 'text_domain' ),
		'attributes'            => __( 'Matériel attibut', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent du matériel:', 'text_domain' ),
		'all_items'             => __( 'Tous le matériel', 'text_domain' ),
		'add_new_item'          => __( 'Ajouter un nouvel item', 'text_domain' ),
		'add_new'               => __( 'Ajouter', 'text_domain' ),
		'new_item'              => __( 'Nouveau matériel', 'text_domain' ),
		'edit_item'             => __( 'Éditer le matériel', 'text_domain' ),
		'update_item'           => __( 'Mettre à jour le matériel', 'text_domain' ),
		'view_item'             => __( 'Voir le matériel', 'text_domain' ),
		'view_items'            => __( 'Voir le matériels', 'text_domain' ),
		'search_items'          => __( 'Chercher dans matériel', 'text_domain' ),
		'not_found'             => __( 'Rien de trouvé', 'text_domain' ),
		'not_found_in_trash'    => __( 'Rien dans la poubelle', 'text_domain' ),
		'featured_image'        => __( 'Images vedettes', 'text_domain' ),
		'set_featured_image'    => __( 'Image vedette', 'text_domain' ),
		'remove_featured_image' => __( 'Suprimer l\'image à la une', 'text_domain' ),
		'use_featured_image'    => __( 'Image à la une', 'text_domain' ),
		'insert_into_item'      => __( 'Insérer dans items', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Téléverser cette item', 'text_domain' ),
		'items_list'            => __( 'Liste des items', 'text_domain' ),
		'items_list_navigation' => __( 'Navigation des items', 'text_domain' ),
		'filter_items_list'     => __( 'Filtre des items', 'text_domain' ),
	);
	$rewrite = array(
		'slug'                  => 'materiel',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => true,
	);
	$args = array(
		'label'                 => __( 'Matériel', 'text_domain' ),
		'description'           => __( 'Matériel publicitaire', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'page',
	);
	register_post_type( 'materiel', $args );

}
add_action( 'init', 'custom_matriel', 0 );
