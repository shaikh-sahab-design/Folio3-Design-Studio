<?php
/**
 * Folio3 Design Studio: enqueue the small CSS layer used by the Elementor pages.
 */
add_action( 'wp_enqueue_scripts', function () {
	$file = WP_CONTENT_DIR . '/uploads/f3ds-static/f3ds.css';
	if ( file_exists( $file ) ) {
		wp_enqueue_style( 'f3ds', content_url( 'uploads/f3ds-static/f3ds.css' ), [ 'elementor-frontend' ], (string) filemtime( $file ) );
	}
}, 20 );
