<?php
/**
 * WordPress plugin "Treitus Quote" main file, responsible for initiating the plugin
 *
 * @package Treitus Quote
 * @author Alpanet
 * @version 1.0.0
 */

/*
Plugin Name: Treitus Quote
Plugin URI: https://alpanet.com.ve/
Description: Treitus Quote
Version: 1.0.0
Author: Alpanet
Author URI: https://alpanet.com.ve/
Author email: info@alpanet.com.ve
Text Domain: treitusQuote
License: GPL 2
Donate URI: https://alpanet.com.ve/
*/
/* ================================================================================
Copyright 2012-2016 Alpanet

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  ================================================================================ */
require_once("define.php");
require TQT_ABSPATH . 'config/config.php';

require TQT_ABSPATH . 'classes/class-treitusQuoteNew.php';
require TQT_ABSPATH . 'treitusQuote-shortcodes.php';

/**BACK END**/
if (is_admin()) {

	register_activation_hook( __FILE__, array( 'treitusQuote', 'plugin_activation' ) );
	register_deactivation_hook( __FILE__, array( 'treitusQuote', 'plugin_deactivation' ) );
	add_action( 'init', array( 'treitusQuote', 'run' ) );
	add_action( 'admin_init', 'plugin_admin_init' );
	add_filter( 'wp_mail_from', function() {
    return 'admin@treitus.com';
} );
}
if (!is_admin()) {
	//require TQT_ABSPATH . 'views/frontend/ge-events.php';
}

add_action( 'admin_post_nopriv', 'tqt_process_forms' ); //Procesar cuando no esta logueado
add_action('admin_post','tqt_process_forms');//Procesar cuando esta logueado
function tqt_process_forms(){
	$quote = new treitusQuote();
	$quote->process_data($_POST,$_FILES);
}

function plugin_admin_init() {
    wp_enqueue_script('datetimepicker',TQT_URL.'views/backend/js/jquery.datetimepicker.full.min.js');
	wp_register_style( 'datetimepicker', TQT_URL.'views/backend/css/datetimepicker.css' );
	wp_register_style( 'tqt_admin_style', TQT_URL.'views/backend/css/style.css' );
	wp_register_style( 'alpage_filecomponent', TQT_URL.'views/backend/css/component.css' );

	wp_register_style( 'tqt_bootstrap', TQT_URL.'css/bootstrap.min.css' );

}


/**
 * PAGE VIEWS
 * @static
 */
function tqt_get_menu( ) {
    $current_page = isset($_REQUEST['page']) ? esc_html($_REQUEST['page']) : 'treitusQuote';

    if(isset($_REQUEST['action2']) && !empty($_REQUEST['action2']) && $_REQUEST['action2'] != -1 && $_REQUEST['action'] == -1)
        $_REQUEST['action'] = $_REQUEST['action2'];

    $action = isset($_REQUEST['action']) ? esc_html($_REQUEST['action']) : 'list';


    switch ($current_page) {
        case 'treitusQuote':
        	include('views/backend/view-quotes.php');
            $ObjList = new treitusQuoteList();
            $ObjList ->doAction($action);
            break;
        /*case 'treitusQuote':
            include('views/backend/view-generatorevents.php');
            $ObjList = new GeneratorEventList();
            $ObjList ->doAction($action);
            break;*/
    }
}

function howdy_to_spanish( ) {
global $wp_admin_bar;

	    $my_account = $wp_admin_bar->get_node('my-account');
	    $logout = $wp_admin_bar->get_node('logout');

	    $newtitle = str_replace( 'Howdy,', '¡Hola!', $my_account->title );
	    $newlogout = str_replace( 'Log Out', 'Salir', $logout->title );

	    $wp_admin_bar->add_node( array(
	        'id' => 'my-account',
	        'title' => $newtitle,
	         'href'   => esc_url( home_url() ),
	     ));

	    $wp_admin_bar->add_node( array(
	        'id' => 'logout',
	        'title' => $newlogout,
	     ));



	}

add_action('wp_before_admin_bar_render', 'howdy_to_spanish');


add_action('phpmailer_init','send_smtp_email');
function send_smtp_email( $phpmailer )
{
    // Define that we are sending with SMTP
    $phpmailer->isSMTP();

    // The hostname of the mail server
    $phpmailer->Host = "smtp.zoho.com";

    // Use SMTP authentication (true|false)
    $phpmailer->SMTPAuth = true;

    // SMTP port number - likely to be 25, 465 or 587
    $phpmailer->Port = "465";

    // Username to use for SMTP authentication
    $phpmailer->Username = "admin@treitus.com";

    // Password to use for SMTP authentication
    $phpmailer->Password = MAIL_PASSWD;

    // The encryption system to use - ssl (deprecated) or tls
    $phpmailer->SMTPSecure = "ssl";

    $phpmailer->From = "admin@treitus.com";
    $phpmailer->FromName = "Treitus";
}

/*
 * Editar el estilo del toolbar
 */
function personalizar_aspecto_toolbar() {
	$adminlogo = '/images/adminlogo.png'; // Especificar ruta (tamaño = 20 x 20 px)
	echo '<style>
	#wpadminbar { background: #093e56 !important; }
	#wpadminbar a.ab-item { color: #F5F5DC !important; font-size:15px }

	#wpadminbar .ab-sub-wrapper { background: #093e56 !important; font-size:15px }



	#wp-admin-bar-wp-logo > .ab-item .ab-icon {
	background-image: url('.get_bloginfo('template_directory').$adminlogo.') !important;
	background-position: 0 0;
	}
	</style>';
}
add_action('wp_before_admin_bar_render', 'personalizar_aspecto_toolbar');

/* Remover todos los nodos excepto
	los de mi cuenta etcetera
 */

function remove_all_nodes(){
global $wp_admin_bar;
	$all_toolbar_nodes = $wp_admin_bar->get_nodes();

	foreach ( $all_toolbar_nodes as $node  ) {
	if($node->id!='top-secondary' and $node->id!='my-account' and $node->id!='user-actions' and $node->id!='logout'){
			$wp_admin_bar->remove_node($node->id);
			}

		}

}


add_action( 'wp_before_admin_bar_render', 'remove_all_nodes' );
