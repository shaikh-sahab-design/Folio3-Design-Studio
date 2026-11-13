<?php
/**
 * Folio3 Design Studio: front-end CSS and motion layer for the Elementor pages.
 */
add_action( 'wp_enqueue_scripts', function () {
	$dir = WP_CONTENT_DIR . '/uploads/f3ds-static/';
	$url = content_url( 'uploads/f3ds-static/' );
	if ( ! file_exists( $dir . 'f3ds.css' ) ) { return; }
	wp_enqueue_style( 'f3ds-inter', 'https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400..900&display=swap', [], null );
	wp_enqueue_style( 'f3ds', $url . 'f3ds.css', [], (string) filemtime( $dir . 'f3ds.css' ) );
	if ( file_exists( $dir . 'f3ds-motion.js' ) ) {
		wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js', [], '3.13.0', true );
		wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollTrigger.min.js', [ 'gsap' ], '3.13.0', true );
		wp_enqueue_script( 'lenis', 'https://cdn.jsdelivr.net/npm/lenis@1.3.4/dist/lenis.min.js', [], '1.3.4', true );
		wp_enqueue_script( 'f3ds-motion', $url . 'f3ds-motion.js', [ 'gsap', 'gsap-scrolltrigger', 'lenis' ], (string) filemtime( $dir . 'f3ds-motion.js' ), true );
	}
}, 20 );
