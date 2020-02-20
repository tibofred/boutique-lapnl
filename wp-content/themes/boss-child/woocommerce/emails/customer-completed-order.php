<?php
/**
 * Customer completed order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-completed-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @hooked WC_Emails::email_header() Output the email header
 */

$email_heading = '<h1 style="text-align:center"><img src="https://boutique.lapnl.ca/wp-content/uploads/2016/03/Logo.png" alt="" width="250" /><h1><h1>'.$email_heading.'</h1><h1 style="color:#FFF; font-size:18px;">Pour tout problème technique, vous pouvez nous écrire à <a href="mailto:technique@lapnl.ca" style="color:#FFF;">technique@lapnl.ca</a></h1>';
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>


<?php /* translators: %s: Customer first name */ ?>
<p>Bonjour tech,<?php echo esc_html( $order->get_billing_first_name() );?></p>
<p>Nous avons terminé de traiter votre commande.</p>
<?php

/**
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/* AJOUT DE TEXT UNIQUEMENT POUR LES FORMATIONS EN LIGNE */
$bool_enligne = 0;
foreach( $order->get_items() as $items ){
    $terms = wp_get_post_terms( $items->get_product_id(), 'product_cat' );
    if(sizeof($terms)>0) {
	    foreach( $terms as $wp_term ){
	        $term_id = $wp_term->term_id;
	        if($term_id==323) {
	        	$bool_enligne = 1;	
	        }
	    }
	}    
}
if($bool_enligne == 1) {
?>
<hr/>
<p>Vous avez accès à cette formation en ligne pour une période de 1 an sauf en ce qui concerne le processus certifiant qui lui doit être complété sur une période de six mois après le début de votre formation. Cependant, nous nous réservons le droit de réduire cette durée ou de limiter l’accès à certains éléments du programme</p>
<?php
}
/* END AJOUT DE TEXT UNIQUEMENT POUR LES FORMATIONS EN LIGNE */

/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
