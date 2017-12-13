<?php

namespace sofad\theme\mini;

include __DIR__ . '/sofadmini/functions.php';

/**
 * Styles and scripts
 */
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style( 'sofadmini-foundation', get_template_directory_uri() . '/assets/css/foundation-6.3.1.min.css' );
    wp_enqueue_style( 'sofadmini-main', get_stylesheet_uri(), [ 'sofadmini-foundation' ] );
    wp_enqueue_style( 'sofadmini-custom', get_template_directory_uri() . '/assets/css/style.css' );
    wp_enqueue_script( 'sofadmini-foundation', get_template_directory_uri() . '/assets/js/foundation-6.3.1.min.js', [ 'jquery' ] );
    wp_enqueue_script( 'sofadmini-plugins', get_template_directory_uri() . '/assets/js/plugins.js', [ 'sofadmini-foundation' ], '0.1', TRUE );
    wp_enqueue_script( 'sofadmini-main', get_template_directory_uri() . '/assets/js/main.js', [ 'sofadmini-foundation' ], '0.1', TRUE );

} );


/**
 * Theme Setup
 *
 */
add_action( 'after_setup_theme', function() {
    /*
	 * Make theme available for translation.
	 */
    load_theme_textdomain( 'sofadmini' );

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );
} );

add_action('wp_enqueue_scripts',function() {
  wp_register_style('googleFonts','https://fonts.googleapis.com/css?family=Roboto+Condensed');
  wp_enqueue_style('googleFonts');
});

?>
