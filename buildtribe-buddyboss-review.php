<?php
/**
 * Plugin Name: BUILDTRIBE Buddyboss Review Add On
 * Description: This plugin using to show review detail on the profile page.
 * Version: 1.0
 * Author: Tom
 */

 if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

 // Define value default
define( 'BBR_REVIEW_VER', '1.0' );
define( 'BBR_REVIEW_URI', plugin_dir_url(__FILE__) );
define( 'BBR_REVIEW_PATH', plugin_dir_path( __FILE__ ) );

//Backend
require_once BBR_REVIEW_PATH . 'backend/init-option.php';

//Front End
require_once BBR_REVIEW_PATH . 'front-end/hook-template.php';

//Css front-end
add_action( 'wp_enqueue_scripts', 'bbr_review_scripts' );
function bbr_review_scripts() {
    wp_enqueue_script( 'bbr-main', BBR_REVIEW_URI . 'assets/js/main.js', array('jquery'), BBR_REVIEW_VER , true );
    wp_enqueue_style( 'bbr-main', BBR_REVIEW_URI . 'assets/css/main.css', array(), BBR_REVIEW_VER , false );
}
