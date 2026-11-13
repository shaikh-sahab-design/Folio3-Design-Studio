<?php
/** Service pages, Work page, case study posts and Contact page. */

function f3_data() {
	static $d = null;
	if ( null === $d ) { $d = json_decode( file_get_contents( F3DS_DATA ), true ); }
	return $d;
}

function f3_page_service( $slug ) {
	$d = f3_data()['services'][ $slug ];
	$E = [ f3_header() ];

	/* Hero */
	$E[] = f3_section( [ 'y' => 72, 'yb' => 48, 'gap' => 48 ], [
		f3_eyebrow( $d['eyebrow'] ),
		f3_heading( $d['h1'], 'h1', [ 84, 60, 40 ], [ 'weight' => '800', 'lh' => 0.98, 'ls' => -0.05, 'anim' => 'fadeInUp', 'maxw' => 1100 ] ),
		f3_c( [ 'dir' => 'row', 'justify' => 'space-between', 'align' => 'flex-end', 'gap' => 32, 'align_m' => 'flex-start' ], [
			f3_lead( $d['lead'], [ 'maxw' => 640 ] ),
			f3_button( "Let's Collaborate", f3_url( 'contact' ), 'dark' ),
		] ),
		f3_image( f3_img( 'assets/services/hero.webp' ), [ 'class' => 'f3-cover', 'h' => 520, 'h_m' => 260, 'radius' => 16, 'anim' => 'fadeIn' ] ),
	] );

	/* Logo marquee */
	$logos = [];
	foreach ( [ 'bmc.png', 'neom.png', 'squaretrade.png', 'zoetis.png', 'boehringer.png', 'mit.png', 'huvepharma.png', 'allstate.png', 'cisco.png', 'standard-chartered.png', 'honda.png', 'mercedes-benz.png', 'jaguar.png', 'barnes-noble.png' ] as $l ) {
		$i = f3_img( 'assets/logos/' . $l );
		$logos[] = [ 'id' => $i['id'], 'url' => $i['url'] ];
	}
	$E[] = f3_section( [ 'y' => 48, 'gap' => 32 ], [
		f3_eyebrow( 'Trusted by 300+ global brands' ),
		f3_carousel( $logos ),
	] );

	/* What we do */
	$blocks = [];
	foreach ( $d['svc'] as $i => $b ) {
		$blocks[] = f3_c( [ 'dir' => 'row', 'gap' => 48, 'bg' => F3_S2, 'radius' => 16, 'pad' => [ 40 ], 'pad_m' => [ 20 ], 'align' => 'center', 'class' => 'f3-svc', 'anim' => 'fadeInUp' ], [
			f3_c( [ 'width' => 50, 'gap' => 20 ], [
				f3_heading( sprintf( '%02d/%02d', $i + 1, count( $d['svc'] ) ), 'span', [ 13 ], [ 'weight' => '700', 'ls' => 0.02 ] ),
				f3_heading( $b['title'], 'h3', [ 44, 36, 28 ], [ 'ls' => -0.035 ] ),
				f3_text( $b['desc'], [ 'maxw' => 520 ] ),
				f3_eyebrow( 'Includes', F3_INK ),
				f3_icon_list( $b['items'], [ 'class' => 'f3-includes', 'sizes' => [ 14 ], 'weight' => '600' ] ),
			] ),
			f3_c( [ 'width' => 50 ], [ f3_image( f3_img( 'assets/services/' . $d['img'] . '-' . ( $i + 1 ) . '.webp' ), [ 'class' => 'f3-cover', 'radius' => 12, 'h' => 520, 'h_m' => 280 ] ) ] ),
		] );
	}
	$E[] = f3_section( [ 'y' => 96, 'gap' => 56 ], [
		f3_c( [ 'gap' => 20 ], [
			f3_heading( $d['svc_h2'], 'h2', [ 64, 48, 36 ], [ 'anim' => 'fadeInUp' ] ),
			f3_lead( $d['svc_intro'], [ 'maxw' => 640 ] ),
		] ),
		f3_c( [ 'gap' => 20 ], $blocks ),
		f3_button( "Let's Collaborate", f3_url( 'contact' ), 'dark', [ 'align' => 'center' ] ),
	] );

	/* Human judgement band (UX page) */
	if ( ! empty( $d['band'] ) ) {
		$icons = [ 'fas fa-users', 'fas fa-magic', 'fas fa-pen', 'fas fa-code' ];
		$cards = [];
		foreach ( $d['band']['items'] as $i => $it ) {
			$cards[] = f3_c( [ 'bg' => '#1A1A1A', 'radius' => 10, 'pad' => [ 28 ], 'class' => 'f3-card', 'anim' => 'fadeInUp', 'delay' => $i * 100 ], [
				f3_icon_box( $icons[ $i ], $it[0], $it[1], [ 'icon_bg' => '#2A2A2A', 'icon_color' => F3_YELLOW, 'title_color' => '#FFFFFF', 'desc_color' => '#999999', 'title_sizes' => [ 24, 22, 20 ] ] ),
			] );
		}
		$E[] = f3_section( [ 'y' => 40, 'gap' => 0 ], [
			f3_c( [ 'bg' => F3_INK, 'radius' => 24, 'pad' => [ 72, 56 ], 'pad_m' => [ 40, 20 ], 'gap' => 48 ], [
				f3_c( [ 'gap' => 20 ], [
					f3_heading( $d['band']['h2'], 'h2', [ 64, 48, 36 ], [ 'color' => '#FFFFFF', 'anim' => 'fadeInUp' ] ),
					f3_lead( $d['band']['intro'], [ 'color' => '#999999', 'maxw' => 640 ] ),
				] ),
				f3_c( [ 'grid' => 4, 'grid_t' => 2, 'grid_m' => 1, 'gap' => 16 ], $cards ),
				f3_c( [ 'dir' => 'row', 'gap' => 24, 'align' => 'center', 'pad' => [ 32, 0 ], 'border' => '#2A2A2A', 'border_w' => [ 1, 0, 1, 0 ] ], [
					f3_w( 'counter', [ 'starting_number' => 0, 'ending_number' => 50, 'prefix' => 'Up to ', 'suffix' => '% faster', 'duration' => 1800, 'title' => 'from research to validated prototype', 'title_tag' => 'p', 'title_position' => 'after', 'number_position' => 'start', 'title_horizontal_alignment' => 'start', 'number_color' => '#FFFFFF', 'title_color' => '#999999', 'title_gap' => f3_u( 8 ) ] + f3_type( 'typography_number', [ 56, 44, 34 ], '700', 1, -0.04 ) + f3_type( 'typography_title', [ 15 ], '500', 1.4 ) ),
				] ),
				f3_icon_list( [ $d['band']['note'] ], [ 'icon' => 'fas fa-lock', 'color' => '#999999', 'sizes' => [ 13 ], 'weight' => '400' ] ),
			] ),
		] );
	}

	/* Fidelity ladder (Prototypes page): pricing-table style */
	if ( ! empty( $d['fid'] ) ) {
		$tiers = [];
		foreach ( $d['fid']['tiers'] as $i => $t ) {
			$dark = 1 === $i;
			$rows = [];
			foreach ( $t['rows'] as $r ) {
				$rows[] = f3_icon_box( '', rtrim( $r[0], ':' ), $r[1], [ 'icon' => 'none', 'tag' => 'h4', 'title_sizes' => [ 12, 12, 12 ], 'title_color' => $dark ? '#999999' : F3_MUTED, 'desc_color' => $dark ? '#FFFFFF' : F3_INK2, 'class' => 'f3-fidrow' ] );
			}
			$tiers[] = f3_c( [ 'bg' => $dark ? F3_INK : F3_S2, 'radius' => 12, 'pad' => [ 36 ], 'pad_m' => [ 24 ], 'gap' => 24, 'class' => 'f3-card f3-price', 'anim' => 'fadeInUp', 'delay' => $i * 120 ], [
				f3_heading( sprintf( '%02d', $i + 1 ), 'span', [ 13 ], [ 'weight' => '700', 'color' => $dark ? F3_YELLOW : F3_MUTED, 'ls' => 0 ] ),
				f3_heading( $t['title'], 'h3', [ 32, 28, 26 ], [ 'color' => $dark ? '#FFFFFF' : F3_INK, 'ls' => -0.03 ] ),
				f3_divider( $dark ? '#2A2A2A' : '#E3E4E5' ),
				f3_c( [ 'gap' => 18 ], $rows ),
				f3_button( 'Talk to us', f3_url( 'contact' ), $dark ? 'yellow' : 'dark' ),
			] );
		}
		$E[] = f3_section( [ 'y' => 96, 'gap' => 56 ], [
			f3_c( [ 'gap' => 20 ], [
				f3_heading( $d['fid']['h2'], 'h2', [ 64, 48, 36 ], [ 'anim' => 'fadeInUp', 'maxw' => 900 ] ),
				f3_lead( $d['fid']['intro'], [ 'maxw' => 640 ] ),
			] ),
			f3_c( [ 'grid' => 3, 'grid_t' => 1, 'grid_m' => 1, 'gap' => 16 ], $tiers ),
			f3_text( implode( ' ', $d['fid']['notes'] ), [ 'sizes' => [ 15 ] ] ),
		] );
	}

	/* Statement */
	if ( ! empty( $d['state'] ) ) {
		$E[] = f3_section( [ 'y' => 96, 'gap' => 40 ], [
			f3_heading( $d['state'], 'p', [ 56, 44, 30 ], [ 'lh' => 1.08, 'ls' => -0.04, 'anim' => 'fadeInUp', 'maxw' => 1040 ] ),
			f3_button( "Let's Collaborate", f3_url( 'contact' ), 'dark' ),
		] );
	}

	/* Testimonials with star ratings */
	$qs = [];
	foreach ( $d['quotes'] as $i => $q ) {
		$img = f3_img( $q['img'] ); $img['alt'] = $q['brand'];
		$qs[] = f3_c( [ 'bg' => F3_S2, 'radius' => 12, 'pad' => [ 32 ], 'pad_m' => [ 24 ], 'gap' => 16, 'class' => 'f3-quote', 'anim' => 'fadeInUp', 'delay' => ( $i % 2 ) * 120 ], [
			f3_rating( 5 ),
			f3_testimonial( $q['quote'], $q['name'], $q['job'], $img ),
		] );
	}
	$E[] = f3_section( [ 'y' => 96, 'gap' => 56 ], [
		f3_c( [ 'gap' => 20 ], [
			f3_heading( $d['q_h2'], 'h2', [ 64, 48, 36 ], [ 'anim' => 'fadeInUp', 'maxw' => 640 ] ),
			f3_lead( $d['q_intro'], [ 'maxw' => 600 ] ),
		] ),
		f3_c( [ 'grid' => 2, 'grid_t' => 2, 'grid_m' => 1, 'gap' => 16 ], $qs ),
	] );

	/* Benefits */
	$bi = [ 'fas fa-chart-bar', 'fas fa-shopping-bag', 'fas fa-users', 'fas fa-magic', 'fas fa-layer-group', 'fas fa-trophy' ];
	$bs = [];
	foreach ( $d['benefits'] as $i => $b ) {
		$bs[] = f3_c( [ 'bg' => F3_S2, 'radius' => 12, 'pad' => [ 32 ], 'pad_m' => [ 24 ], 'class' => 'f3-card', 'anim' => 'fadeInUp', 'delay' => ( $i % 3 ) * 100 ], [
			f3_icon_box( $bi[ $i % 6 ], $b[0], $b[1], [ 'icon_bg' => '#FFFFFF', 'title_sizes' => [ 26, 24, 22 ] ] ),
		] );
	}
	$E[] = f3_section( [ 'y' => 96, 'gap' => 56 ], [
		f3_c( [ 'gap' => 20 ], [
			f3_heading( str_replace( '. You', '.<br>You', $d['ben_h2'] ), 'h2', [ 64, 48, 36 ], [ 'anim' => 'fadeInUp' ] ),
			f3_lead( $d['ben_intro'], [ 'maxw' => 560 ] ),
		] ),
		f3_c( [ 'grid' => 3, 'grid_t' => 2, 'grid_m' => 1, 'gap' => 16 ], $bs ),
		f3_button( 'Connect to Learn More', f3_url( 'contact' ), 'dark', [ 'align' => 'center' ] ),
	] );

	/* Why us */
	$ws = [];
	foreach ( $d['why'] as $i => $w ) {
		if ( $i < 4 ) {
			$ws[] = f3_c( [ 'bg' => F3_S2, 'radius' => 12, 'pad' => [ 24 ], 'class' => 'f3-card', 'anim' => 'fadeInUp', 'delay' => ( $i % 3 ) * 100 ], [
				f3_image_box( f3_img( 'assets/services/why-' . ( $i + 1 ) . '.webp' ), $w[0], $w[1], [ 'img_h' => 240, 'img_radius' => 8 ] ),
			] );
		} else {
			$ws[] = f3_c( [ 'bg' => F3_INK, 'radius' => 12, 'pad' => [ 28 ], 'gap' => 24, 'justify' => 'space-between', 'class' => 'f3-card', 'anim' => 'fadeInUp', 'delay' => 200 ], [
				f3_heading( 'Unlimited', 'span', [ 72, 56, 48 ], [ 'weight' => '800', 'color' => '#8A8A8A', 'ls' => -0.05 ] ),
				f3_icon_box( '', str_replace( 'Unlimited ', '', $w[0] ), $w[1], [ 'icon' => 'none', 'title_color' => '#FFFFFF', 'desc_color' => '#999999', 'title_sizes' => [ 24, 22, 20 ] ] ),
			] );
		}
	}
	$E[] = f3_section( [ 'y' => 96, 'gap' => 56 ], [
		f3_c( [ 'gap' => 20 ], [
			f3_heading( $d['why_h2'], 'h2', [ 64, 48, 36 ], [ 'anim' => 'fadeInUp' ] ),
			f3_lead( $d['why_intro'], [ 'maxw' => 600 ] ),
		] ),
		f3_c( [ 'grid' => 3, 'grid_t' => 2, 'grid_m' => 1, 'gap' => 16 ], $ws ),
	] );

	$E[] = f3_cta();
	$E[] = f3_footer();
	return $E;
}

function f3_case_card( $c, $delay = 0 ) {
	$post = get_page_by_path( $c['slug'], OBJECT, 'post' );
	return f3_c( [ 'gap' => 18, 'link' => $post ? get_permalink( $post ) : f3_url( 'work' ), 'class' => 'f3-workcard', 'anim' => 'fadeInUp', 'delay' => $delay ], [
		f3_c( [ 'radius' => 8, 'overflow' => 'hidden' ], [ f3_image( f3_img( $c['thumb'] ), [ 'class' => 'f3-cover', 'h' => 460, 'h_m' => 260 ] ) ] ),
		f3_c( [ 'dir' => 'row', 'dir_m' => 'row', 'justify' => 'space-between', 'align' => 'flex-start', 'gap' => 16 ], [
			f3_c( [ 'gap' => 6 ], [ f3_heading( $c['title'], 'h3', [ 28, 24, 22 ], [ 'ls' => -0.03 ] ), '' !== trim( $c['tag'] ) ? f3_text( $c['tag'], [ 'sizes' => [ 15 ] ] ) : null ] ),
			f3_chips( [ $c['chip'] ] ),
		] ),
	] );
}

function f3_page_work() {
	$cases = f3_data()['cases'];
	$cards = []; $i = 0;
	foreach ( $cases as $c ) { $cards[] = f3_case_card( $c, ( $i++ % 2 ) * 120 ); }
	return [
		f3_header(),
		f3_section( [ 'y' => 72, 'yb' => 64, 'gap' => 32 ], [
			f3_eyebrow( 'Work · ' . count( $cases ) . ' projects' ),
			f3_heading( 'We’re building new ventures, exploring emerging technologies, and uncovering untapped potential.', 'h1', [ 76, 56, 38 ], [ 'weight' => '800', 'lh' => 1, 'ls' => -0.05, 'anim' => 'fadeInUp', 'maxw' => 1200 ] ),
			f3_lead( 'AI brands. AI challenges. AI results. From startups to industry icons — we turn visions into interactive realities.', [ 'maxw' => 640 ] ),
		] ),
		f3_section( [ 'y' => 48, 'yb' => 96, 'gap' => 64 ], [
			f3_c( [ 'grid' => 2, 'grid_t' => 2, 'grid_m' => 1, 'gap' => 24, 'rgap' => 48 ], $cards ),
			f3_c( [ 'gap' => 24, 'align' => 'center' ], [
				f3_heading( 'Seen enough? Let’s work together', 'p', [ 36, 30, 24 ], [ 'align' => 'center', 'ls' => -0.03 ] ),
				f3_button( 'Start a project', f3_url( 'contact' ), 'dark', [ 'align' => 'center' ] ),
			] ),
		] ),
		f3_cta(),
		f3_footer(),
	];
}

function f3_page_case( $slug ) {
	$all = f3_data()['cases'];
	$c = $all[ $slug ];
	$chapter = function ( $n, $label ) {
		return f3_c( [ 'width' => 26, 'gap' => 8 ], [
			f3_heading( '/' . $n, 'span', [ 72, 56, 44 ], [ 'weight' => '800', 'color' => '#B9BDC2', 'ls' => -0.05, 'lh' => 1 ] ),
			f3_eyebrow( $label, F3_INK ),
		] );
	};
	$meta = [];
	foreach ( $c['meta'] as $k => $v ) {
		$meta[] = f3_icon_box( '', $k, $v, [ 'icon' => 'none', 'tag' => 'p', 'title_sizes' => [ 12, 12, 12 ], 'title_color' => F3_MUTED, 'desc_color' => F3_INK, 'class' => 'f3-meta' ] );
	}
	$E = [ f3_header() ];
	$E[] = f3_section( [ 'y' => 72, 'yb' => 64, 'gap' => 40 ], [
		f3_eyebrow( 'Work / ' . $c['title'] ),
		f3_heading( $c['title'], 'h1', [ 150, 104, 60 ], [ 'weight' => '800', 'lh' => 0.9, 'ls' => -0.06, 'anim' => 'fadeInUp' ] ),
		f3_c( [ 'dir' => 'row', 'justify' => 'space-between', 'align' => 'center', 'gap' => 24, 'align_m' => 'flex-start' ], [
			'' !== trim( $c['tag'] ) ? f3_lead( $c['tag'] ) : null,
			f3_button( "Let's Collaborate", f3_url( 'contact' ), 'dark' ),
		] ),
		f3_c( [ 'grid' => 3, 'grid_t' => 3, 'grid_m' => 1, 'gap' => 24, 'pad' => [ 24, 0, 0, 0 ], 'border' => F3_LINE, 'border_w' => [ 1, 0, 0, 0 ] ], $meta ),
		f3_image( f3_img( $c['hero'] ), [ 'class' => 'f3-cover', 'radius' => 16, 'h' => 720, 'h_m' => 320, 'anim' => 'fadeIn' ] ),
	] );

	$about = [];
	foreach ( $c['about'] as $i => $p ) {
		$about[] = $i === count( $c['about'] ) - 1 && count( $c['about'] ) > 1
			? f3_heading( $p, 'p', [ 48, 38, 28 ], [ 'lh' => 1.1, 'ls' => -0.035, 'anim' => 'fadeInUp' ] )
			: f3_lead( $p, [ 'color' => F3_SOFT ] );
	}
	$E[] = f3_section( [ 'y' => 80, 'gap' => 0 ], [
		f3_c( [ 'dir' => 'row', 'gap' => 40 ], [ $chapter( 1, 'About the project' ), f3_c( [ 'width' => 60, 'gap' => 40 ], $about ) ] ),
	] );

	$probs = [];
	foreach ( $c['problem'] as $h => $p ) {
		if ( '' === trim( $p ) ) { continue; }
		$probs[] = f3_icon_box( '', $h, $p, [ 'icon' => 'none', 'title_sizes' => [ 32, 28, 24 ], 'anim' => 'fadeInUp' ] );
	}
	$n = 2;
	if ( $probs ) { $E[] = f3_section( [ 'y' => 80, 'gap' => 0 ], [
		f3_c( [ 'dir' => 'row', 'gap' => 40 ], [
			$chapter( $n++, 'Problem statement' ),
			f3_c( [ 'width' => 70, 'grid' => count( $probs ) > 1 ? 2 : 1, 'grid_t' => 1, 'grid_m' => 1, 'gap' => 40 ], $probs ),
		] ),
	] ); }

	$imgs = [];
	foreach ( $c['process'] as $p ) {
		$imgs[] = f3_image( f3_img( $p[0] ), [ 'class' => 'f3-process-img', 'caption' => $p[1], 'anim' => 'fadeInUp', 'alt' => $c['title'] . ' ' . strtolower( $p[1] ) ] );
	}
	$E[] = f3_section( [ 'y' => 80, 'gap' => 48 ], [
		$chapter( $n, 'Our process' ),
		f3_c( [ 'gap' => 56 ], $imgs ),
	] );

	$others = [];
	$keys = array_keys( $all ); $pos = array_search( $slug, $keys, true );
	for ( $k = 1; $k <= 2; $k++ ) { $others[] = f3_case_card( $all[ $keys[ ( $pos + $k ) % count( $keys ) ] ], ( $k - 1 ) * 120 ); }
	$E[] = f3_section( [ 'y' => 80, 'gap' => 48 ], [
		f3_c( [ 'dir' => 'row', 'dir_m' => 'row', 'justify' => 'space-between', 'align' => 'flex-end', 'gap' => 24 ], [
			f3_heading( 'Browse Other Projects', 'h2', [ 56, 44, 32 ] ),
			f3_button( 'All work', f3_url( 'work' ), 'outline' ),
		] ),
		f3_c( [ 'grid' => 2, 'grid_t' => 2, 'grid_m' => 1, 'gap' => 24 ], $others ),
	] );
	$E[] = f3_cta();
	$E[] = f3_footer();
	return $E;
}

function f3_page_contact() {
	$needs = [
		[ 'fas fa-pen-nib', 'UX & Product Design', 'Research, flows, UI and design systems.', f3_url( 'ux-and-product-design' ) ],
		[ 'fas fa-video', 'AI Video Design', 'Marketing videos and product animation.', f3_url( 'ai-video-design' ) ],
		[ 'fas fa-mouse-pointer', 'Interactive & AI-Built Prototypes', 'Working prototypes users can try.', f3_url( 'interactive-prototypes' ) ],
		[ 'fas fa-comment-dots', 'Something else', 'Tell us where you are and what you need.', 'https://www.folio3.com/' ],
	];
	$cards = [];
	foreach ( $needs as $i => $n ) {
		$cards[] = f3_c( [ 'bg' => F3_S2, 'radius' => 12, 'pad' => [ 28 ], 'class' => 'f3-card', 'anim' => 'fadeInUp', 'delay' => $i * 100 ], [
			f3_icon_box( $n[0], $n[1], $n[2], [ 'icon_bg' => '#FFFFFF', 'title_sizes' => [ 22, 20, 20 ], 'link' => $n[3] ] ),
		] );
	}
	return [
		f3_header(),
		f3_section( [ 'y' => 96, 'gap' => 40 ], [
			f3_eyebrow( 'Start a project' ),
			f3_heading( 'Have an idea, a prototype, or a product that needs fixing?', 'h1', [ 88, 64, 40 ], [ 'weight' => '800', 'lh' => 0.98, 'ls' => -0.05, 'anim' => 'fadeInUp', 'maxw' => 1100 ] ),
			f3_lead( "Tell us where you are. We'll bring the right mix of designers, AI and engineering to the first call.", [ 'maxw' => 640 ] ),
			f3_heading( 'What do you need?', 'h2', [ 28, 24, 22 ], [ 'ls' => -0.02 ] ),
			f3_c( [ 'grid' => 4, 'grid_t' => 2, 'grid_m' => 1, 'gap' => 16 ], $cards ),
			f3_text( 'United States · Canada · Mexico · Bulgaria · UAE · Australia · Pakistan', [ 'sizes' => [ 14 ] ] ),
		] ),
		f3_footer(),
	];
}
