<?php
/**
 * @package Boss Child Theme
 * The parent theme functions are located at /boss/buddyboss-inc/theme-functions.php
 * Add your own functions in this file.
 */

/**
 * Sets up theme defaults
 *
 * @since Boss Child Theme 1.0.0
 */
function boss_child_theme_setup()
{
  /**
   * Makes child theme available for translation.
   * Translations can be added into the /languages/ directory.
   * Read more at: http://www.buddyboss.com/tutorials/language-translations/
   */

  // Translate text from the PARENT theme.
  load_theme_textdomain( 'boss', get_stylesheet_directory() . '/languages' );

  // Translate text from the CHILD theme only.
  // Change 'boss' instances in all child theme files to 'boss_child_theme'.
  // load_theme_textdomain( 'boss_child_theme', get_stylesheet_directory() . '/languages' );

}
add_action( 'after_setup_theme', 'boss_child_theme_setup' );

/**
 * Enqueues scripts and styles for child theme front-end.
 *
 * @since Boss Child Theme  1.0.0
 */
function boss_child_theme_scripts_styles()
{
  /**
   * Scripts and Styles loaded by the parent theme can be unloaded if needed
   * using wp_deregister_script or wp_deregister_style.
   *
   * See the WordPress Codex for more information about those functions:
   * http://codex.wordpress.org/Function_Reference/wp_deregister_script
   * http://codex.wordpress.org/Function_Reference/wp_deregister_style
   **/

  /*
   * Styles
   */
  wp_enqueue_style( 'boss-child-custom', get_stylesheet_directory_uri().'/css/custom.css' );
}
add_action( 'wp_enqueue_scripts', 'boss_child_theme_scripts_styles', 9999 );



function boss_child_theme_scripts_js() {

	wp_enqueue_script( 'boss-child-customjs', get_stylesheet_directory_uri().'/js/custom.js', false );

}
add_action( 'wp_enqueue_scripts', 'boss_child_theme_scripts_js' );

/****************************** CUSTOM FUNCTIONS ******************************/

// Add your own custom functions here

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

// Register Custom Taxonomy
function custom_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Types', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Type', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Type', 'text_domain' ),
		'all_items'                  => __( 'All Items', 'text_domain' ),
		'parent_item'                => __( 'Parent Item', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
		'new_item_name'              => __( 'New Item Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Item', 'text_domain' ),
		'edit_item'                  => __( 'Edit Item', 'text_domain' ),
		'update_item'                => __( 'Update Item', 'text_domain' ),
		'view_item'                  => __( 'View Item', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Items', 'text_domain' ),
		'search_items'               => __( 'Search Items', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No items', 'text_domain' ),
		'items_list'                 => __( 'Items list', 'text_domain' ),
		'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'type', array( 'materiel' ), $args );

}
add_action( 'init', 'custom_taxonomy', 0 );

function wc_shop_demo_button() {
	$link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );
    echo '<a class="button product_type_course add_to_cart_button" href="'.$link.'">Voir les détails</a>';
}
add_action( 'woocommerce_after_shop_loop_item', 'wc_shop_demo_button', 20 );

add_filter( 'add_to_cart_text', 'woo_custom_product_add_to_cart_text' );            // < 2.1
add_filter( 'woocommerce_product_add_to_cart_text', 'woo_custom_product_add_to_cart_text' );  // 2.1 +

function woo_custom_product_add_to_cart_text() {

    return __( 'Ajouter au panier', 'woocommerce' );

}

function name_ordering_args( $sort_args ) {
	$cate = get_queried_object();
	$cateID = $cate->term_id;
	if($cateID == 276) {
		$sort_args['orderby']  = 'title';
		$sort_args['order']    = 'ASC';
	}

	return $sort_args;
}
add_filter( 'woocommerce_get_catalog_ordering_args', 'name_ordering_args' );


function getFacebookfPost($atts ) {
	$a = shortcode_atts( array(
        'number' => ''
    ), $atts );
    $number = $a['number'] ;

	$url = "https://graph.facebook.com/184302354944624/posts?limit=3&access_token=EAADKTMr9BM8BACE3LJCWSg5fDLflPpCpfbhvWCbm0LkIvNlTpcldequ1AFPjZCZCT3ne8siCqY4ZCSWmMKrIpa9CorMuOcC7w8dm7rOTTODZAIb33ny7fBTnC2TLLIHHdBNfeDdvpnoebml2fgSUgX3XlS98IaUZD";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$result = curl_exec($ch);
	curl_close($ch);

	$obj = json_decode($result);
	if(empty($number)) {
		$id = explode('_',$obj->data[0]->id);
	} else {
		$id = explode('_',$obj->data[$number]->id);
	}
	echo '<iframe style="border: none; overflow: hidden;" src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2Fpnlidcom%2Fposts%2F'.$id[1].'&amp;width=500" width="500" height="513" frameborder="0" scrolling="no"></iframe>';

}
add_shortcode('facebookpost', 'getFacebookfPost');

function update_wc_order_status($posted) {
    $order_id = isset($posted['invoice']) ? $posted['invoice'] : '';
    if(!empty($order_id)) {
        $order = new WC_Order($order_id);
        $order->update_status('completed');
    }
}
add_action('paypal_ipn_for_wordpress_payment_status_completed', 'update_wc_order_status', 10, 1);



function getListMaterials() {
	// check if the repeater field has rows of data
	$return = "";
	if( have_rows('materiels') ):
		$return = '<div class="row">';
	 	// loop through the rows of data
	    while ( have_rows('materiels') ) : the_row();
	        $titre 		= get_sub_field('titre');
	        $fichier 	= get_sub_field('fichier');
	        $image 		= get_sub_field('images');
	        $return .= '<div class="col_md4">';
	        if(!empty($fichier['url'])) {
	        	$return .= '<a href="'.$fichier['url'].'">';
	        }
	        if(!empty($image['url'])) {
	        	$return .= '<img src="'.$image['url'].'" alt="" />';
	        } else {
	        	$return .= '<img src="https://lapnl.org/wp-content/plugins/woocommerce/assets/images/placeholder.png" alt="" />';
	        }
        		$return .= '<span>'.$titre.'</span>';

	        if(!empty($fichier['url'])) {
	        	$return .= '</a>';
	        }
	        $return .= '</div>';

	    endwhile;
	    $return .= '</div>';

	endif;

	return $return;
}
add_shortcode('list_materials', 'getListMaterials');

/* Function a probleme
function get_categories_idcom($term_id,$deb='') {
	$args = array(
			'parent'                   => $term_id,
			'hide_empty'               => 1,
			'orderby'                  => 'rand',
			'taxonomy'                 => 'product_cat',

	        'orderby' => 'order',
	        'order'=> 'ASC',

		);
	$terms = get_term_meta($term_id);
	$enfants = get_categories( $args );
    if(sizeof($enfants)>0) {
    	foreach($enfants as $enfant) {
    		get_categories_idcom($enfant->term_id,$deb=1);
		}
	} else {
		$cat = get_term_by('term_id', $term_id, 'product_cat');
		if(!empty($deb)) {
		?>
		<h3 id="cat_<?php echo $term_id;?>" class="h3_cat"><?php echo $cat->name; ?></h3>
		<?php
		}
		?>
		<ul class="products columns-3">
		<?php
		$args = array(
			'post_type' => 'product',
			'product_cat' => $cat->slug,
			'orderby' => 'publish_date',
			'order'   => 'DESC',
			'posts_per_page' => '50',
			);
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) {
				$loop->the_post();
				do_action( 'woocommerce_shop_loop' );
				wc_get_template_part( 'content', 'product' );
			}
		}
		?>
		</ul>
		<?

	}
}

*/

/**
 * Remove related products output
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );


/*if (!function_exists('woocommerce_template_loop_add_to_cart')) {
	function woocommerce_template_loop_add_to_cart() {
		global $product;
		$id = $product->get_id();
		echo $id."--------------";
		if ( $id == 12715 )  {
			?><a href="javascript:void(0);" class="button product_type_simple add_to_cart_button button_preinscript">Préinscription</a><?php
		} else {
			wc_get_template('loop/add-to-cart.php');
		}
	}
}*/

function my_acf_repeater($atts, $content='') {
  extract(shortcode_atts(array(
    "field" => null,
    "sub_fields" => null,
    "post_id" => null
  ), $atts));
  if (empty($field) || empty($sub_fields)) {
    // silently fail? is that the best option? idk
    return "";
  }
  $sub_fields = explode(",", $sub_fields);

  $_finalContent = '';
  if( have_rows($field, $post_id) ):
    while ( have_rows($field, $post_id) ) : the_row();

      $_tmp = $content;
      foreach ($sub_fields as $sub) {
        $subValue = get_sub_field(trim($sub));
        $_tmp = str_replace("%$sub%", $subValue, $_tmp);
      }
      $_finalContent .= do_shortcode( $_tmp );
    endwhile;
  endif;
  return $_finalContent;
}
add_shortcode("acf_repeater", "my_acf_repeater");





function my_acf_repeater_formation($atts, $content='') {
  extract(shortcode_atts(array(
    "field" => null,
    "sub_fields" => null,
    "post_id" => null
  ), $atts));
  if (empty($field) || empty($sub_fields)) {
    // silently fail? is that the best option? idk
    return "";
  }
  $sub_fields = explode(",", $sub_fields);
  $form_id = $_GET['form'];

  $_finalContent = '';
  $i = 0;
  if( have_rows($field, $form_id) ):
    while ( have_rows($field, $form_id) ) : the_row();

      $_tmp = $content;
      foreach ($sub_fields as $sub) {
        $subValue = get_sub_field(trim($sub));
        $_tmp = str_replace("%$sub%", $subValue, $_tmp);
        $_tmp = str_replace("%i%", $i, $_tmp);
      }
      $_finalContent .= do_shortcode( $_tmp );
      $i++;
    endwhile;

  endif;
  return $_finalContent;
}
add_shortcode("acf_repeater_formation", "my_acf_repeater_formation");



function materiel_formation($atts, $content='') {
	extract(shortcode_atts(array(
	"formation" => null
	), $atts));

	$var_field = '';
	if(!empty($formation)) {
		switch($formation)  {
			case '1824':
				$var_field = 'materiel_base_en_ligne';
				echo '<a class="single_add_to_cart_button button alt" style="padding: 12px 35px; width: auto; font-weight: bold;" href="https://s3.amazonaws.com/base-en-ligne/PNL_B_EL_MANUEL_11-07-2019c.pdf" target="_blank" rel="noopener">Téléchargez le manuel base en ligne</a><br/><br/><hr/><br/>';
			break;
			case '13269':
				$var_field = 'materiel_hypnose_ericksonienne';
			break;
			case '13257':
				$var_field = 'materiel_ema_amo1';
				echo '<a class="single_add_to_cart_button button alt" style="padding: 12px 35px; width: auto; font-weight: bold;" href="https://s3.amazonaws.com/base-en-ligne/EMA_1_MANUEL_21-01-2019_S.pdf" target="_blank" rel="noopener"><span style="font-size: 10pt;"><strong>Téléchargez le manuel</strong></span></a>';
			case '14351':
				$var_field = 'materiel_ema_amo2';
			break;
			case '13281':
				$var_field = 'materiel_pnl_praticien';
			break;
		}
	}
	$args = array(
        'post_type'      	=> 'product',
        'posts_per_page'	=>	-1
    );

    $loop = new WP_Query( $args );
    $i=0;
    while ( $loop->have_posts() ) : $loop->the_post();
        global $product;
        $var = get_field($var_field);
		if($var[0] == 1) {
	    	if($i==0) {
	    		echo '<div class="woocommerce"><ul class="products columns-3">';
	    	}
			//print_r($product);
			do_action( 'woocommerce_shop_loop' );
			wc_get_template_part( 'content', 'product' );

        	$i++;
        }
    endwhile;
	if($i>0) {
		echo '</ul></div>';
	}

    wp_reset_query();

}


add_shortcode("materiel_formation_produits", "materiel_formation");


/*add_action('wp_footer', 'get_custom_coupon_code_to_session');
function get_custom_coupon_code_to_session(){
    if( isset($_GET['coupon_code']) ){
    	WC()->cart->add_to_cart( 11572, 1 );
        $coupon_code = WC()->session->get('coupon_code');
        if(empty($coupon_code)){
            $coupon_code = esc_attr( $_GET['coupon_code'] );
            WC()->cart->add_discount( $coupon_code );
        }
    }
}*/

add_role(
    'salon',
    __( 'Abonné salon' ),
    array(
        'read'         => true
    )
);


add_action('mepr_account_nav_content', 'custom_account_nav_content', 10, 1);
function custom_account_nav_content( $action ) {
  if($action == 'content') {
  	if(current_user_can('mepr-active','rule: 16231')) {
  		echo '<a href="https://boutique.lapnl.ca/base-en-ligne/">Base en ligne</a>';
  	}
  	echo " ";

  }
}


// SHORT CODE FOR MEMBRESPRESS RULES
function materiel_formation_r( $atts , $content = null ) {
	// Attributes
	$atts = shortcode_atts(
		array(
			'rule' => '',
		),
		$atts,
		'materiel_formation_restrict'
	);

	// Return image HTML code
	// Return image HTML code
	if (is_admin() || is_super_admin()) {
		echo $content;
	} else {
		echo do_shortcode('[mepr-show if="rule: '.$rule.'"]'.$content.'[/mepr-show]');
	}
	echo "
<br/><br/>
<hr/>
<br/><br/>";

}
add_shortcode( 'materiel_formation_restrict', 'materiel_formation_r' );


function user_logged_in_product_already_bought($prod_id) {
	if ( ! is_user_logged_in() ) return false;
	$current_user = wp_get_current_user();
	if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $prod_id ) ) {
		return true;
	}
	return false;
}
