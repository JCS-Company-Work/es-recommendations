<?php
/**
 * Plugin Name: ES Recommendations
 * Description: Simple form + shortcode to create product comparison URLs.
 * Version: 0.1.0
 * Author: Emporio Surfaces
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Constants
define( 'ESRE_PATH', plugin_dir_path( __FILE__ ) );
define( 'ESRE_URL',  plugin_dir_url( __FILE__ ) );
define( 'ESRE_VERSION', '1.1' );

// Composer autoload
if ( file_exists( ESRE_PATH . 'vendor/autoload.php' ) ) {
    require_once ESRE_PATH . 'vendor/autoload.php';
}

use EsRecommendations\ESRE_Create;
use EsRecommendations\ESRE_Compare;
use EsRecommendations\ESRE_RegisterTemplates;

// Register shortcode on init.
add_action( 'init', function() {
    
    new ESRE_Create();
    new ESRE_Compare();
    new ESRE_RegisterTemplates();

});