<?php
/**
 * Plugin Name: BUILDTRIBE Buddyboss Review Add On
 * Description: This plugin using to show review detail on the profile page.
 * Version: 1.0
 * Author: Tom
 */

 if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

 // Define value default
define( 'BBR_VERSION', '1.0' );
define( 'BBR_VERSION', plugin_dir_url(__FILE__) );
define( 'BBR_VERSION', plugin_dir_path( __FILE__ ) );

//Backend
require_once BSL_FLUENTU_PATH . 'backend/init-option.php';

//Front End
require_once BSL_FLUENTU_PATH . 'front-end/hook-template.php';
