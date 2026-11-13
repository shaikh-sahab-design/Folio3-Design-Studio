<?php
/**
 * Folio3 Design Studio: Elementor data helpers (build-time only, loaded on demand).
 */
if ( defined( 'F3DS_LIB' ) ) { return; }
define( 'F3DS_LIB', 1 );

const F3_INK = '#000000';
const F3_INK2 = '#171819';
const F3_MUTED = '#757575';
const F3_SOFT = '#444444';
const F3_LINE = '#EEEEEE';
const F3_S2 = '#F7F7F7';
const F3_YELLOW = '#F5CA00';
const F3_RED = '#EC2639';
const F3_BLUE = '#007EF6';
const F3_TEAL = '#00C3B2';

function f3_id() { return substr( md5( uniqid( '', true ) . mt_rand() ), 0, 7 ); }

function f3_img( $rel ) {
	static $map = null;
	if ( null === $map ) { $map = get_option( 'f3ds_media', [] ); }
	$id = $map[ $rel ] ?? 0;
	if ( is_int( $id ) && $id ) { return [ 'url' => wp_get_attachment_url( $id ), 'id' => $id, 'source' => 'library' ]; }
	$base = wp_upload_dir()['baseurl'] . '/f3ds-src/Folio3-Design-Studio-claude-project-thread-y4v2ar/';
	return [ 'url' => $base . $rel, 'id' => '', 'source' => 'url' ];
}
function f3_static( $file ) { return [ 'url' => wp_upload_dir()['baseurl'] . '/f3ds-static/' . $file, 'id' => '', 'source' => 'url' ]; }

function f3_u( $v, $unit = 'px' ) { return [ 'unit' => $unit, 'size' => $v, 'sizes' => [] ]; }
function f3_box( $t, $r = null, $b = null, $l = null, $unit = 'px' ) {
	$r = $r ?? $t; $b = $b ?? $t; $l = $l ?? $r;
	return [ 'unit' => $unit, 'top' => (string) $t, 'right' => (string) $r, 'bottom' => (string) $b, 'left' => (string) $l, 'isLinked' => ( $t == $r && $r == $b && $b == $l ) ];
}
function f3_gap( $col, $row = null ) { $row = $row ?? $col; return [ 'column' => (string) $col, 'row' => (string) $row, 'isLinked' => $col == $row, 'unit' => 'px', 'size' => $col ]; }

/** Typography settings. $s = [desktop, tablet, mobile] sizes in px. */
function f3_type( $prefix, $s, $weight = '400', $lh = null, $ls = null, $extra = [] ) {
	$s = (array) $s;
	$o = [
		$prefix . '_typography'  => 'custom',
		$prefix . '_font_family' => 'Inter',
		$prefix . '_font_size'   => f3_u( $s[0] ),
		$prefix . '_font_weight' => (string) $weight,
	];
	if ( isset( $s[1] ) ) { $o[ $prefix . '_font_size_tablet' ] = f3_u( $s[1] ); }
	if ( isset( $s[2] ) ) { $o[ $prefix . '_font_size_mobile' ] = f3_u( $s[2] ); }
	if ( null !== $lh ) { $o[ $prefix . '_line_height' ] = f3_u( $lh, 'em' ); }
	if ( null !== $ls ) { $o[ $prefix . '_letter_spacing' ] = f3_u( $ls, 'em' ); }
	foreach ( $extra as $k => $v ) { $o[ $prefix . '_' . $k ] = $v; }
	return $o;
}

function f3_w( $type, $settings = [] ) {
	return [ 'id' => f3_id(), 'elType' => 'widget', 'widgetType' => $type, 'settings' => $settings, 'elements' => [] ];
}

/**
 * Container. $o keys: dir (row|column), gap, pad [t,r,b,l], bg, radius, width (%), boxed (bool|int), wrap, justify, align,
 * grid (cols) grid_t grid_m, class, id, anim, minh, border, extra.
 */
function f3_c( $o = [], $children = [] ) {
	$s = [];
	$inner = $o['inner'] ?? true;
	if ( ! empty( $o['grid'] ) ) {
		$s['container_type'] = 'grid';
		$s['grid_columns_grid'] = f3_u( $o['grid'], 'fr' );
		$s['grid_columns_grid_tablet'] = f3_u( $o['grid_t'] ?? min( 2, $o['grid'] ), 'fr' );
		$s['grid_columns_grid_mobile'] = f3_u( $o['grid_m'] ?? 1, 'fr' );
		$s['grid_rows_grid'] = f3_u( $o['rows'] ?? 1, 'fr' );
		$s['grid_auto_flow'] = 'row';
		$g = $o['gap'] ?? 20;
		$s['grid_gaps'] = f3_gap( $g, $o['rgap'] ?? $g );
		$s['grid_align_items'] = $o['align'] ?? 'stretch';
	} else {
		$s['flex_direction'] = $o['dir'] ?? 'column';
		if ( isset( $o['dir_t'] ) ) { $s['flex_direction_tablet'] = $o['dir_t']; }
		$s['flex_direction_mobile'] = $o['dir_m'] ?? 'column';
		if ( isset( $o['gap'] ) ) { $s['flex_gap'] = f3_gap( $o['gap'], $o['rgap'] ?? $o['gap'] ); }
		if ( isset( $o['gap_m'] ) ) { $s['flex_gap_mobile'] = f3_gap( $o['gap_m'] ); }
		if ( ! empty( $o['wrap'] ) ) { $s['flex_wrap'] = 'wrap'; }
		if ( isset( $o['justify'] ) ) { $s['flex_justify_content'] = $o['justify']; }
		if ( isset( $o['align'] ) ) { $s['flex_align_items'] = $o['align']; }
		if ( isset( $o['align_m'] ) ) { $s['flex_align_items_mobile'] = $o['align_m']; }
	}
	if ( isset( $o['boxed'] ) && $o['boxed'] ) {
		$s['content_width'] = 'boxed';
		$s['boxed_width'] = f3_u( is_int( $o['boxed'] ) ? $o['boxed'] : 1360 );
	} else {
		$s['content_width'] = 'full';
	}
	if ( isset( $o['width'] ) ) {
		$s['width'] = f3_u( $o['width'], '%' );
		$s['width_tablet'] = f3_u( $o['width_t'] ?? 100, '%' );
		$s['width_mobile'] = f3_u( 100, '%' );
	}
	$pad = $o['pad'] ?? [ 0 ];
	$s['padding'] = f3_box( ...$pad );
	if ( isset( $o['pad_t'] ) ) { $s['padding_tablet'] = f3_box( ...$o['pad_t'] ); }
	if ( isset( $o['pad_m'] ) ) { $s['padding_mobile'] = f3_box( ...$o['pad_m'] ); }
	if ( isset( $o['margin'] ) ) { $s['margin'] = f3_box( ...$o['margin'] ); }
	if ( isset( $o['bg'] ) ) { $s['background_background'] = 'classic'; $s['background_color'] = $o['bg']; }
	if ( isset( $o['bg_img'] ) ) {
		$s['background_background'] = 'classic'; $s['background_image'] = $o['bg_img'];
		$s['background_position'] = 'center center'; $s['background_size'] = 'cover'; $s['background_repeat'] = 'no-repeat';
	}
	if ( isset( $o['radius'] ) ) { $s['border_radius'] = f3_box( $o['radius'] ); }
	if ( isset( $o['border'] ) ) {
		$s['border_border'] = 'solid'; $s['border_width'] = f3_box( ...( (array) ( $o['border_w'] ?? [ 1 ] ) ) ); $s['border_color'] = $o['border'];
	}
	if ( isset( $o['minh'] ) ) { $s['min_height'] = f3_u( $o['minh'] ); }
	if ( isset( $o['minh_m'] ) ) { $s['min_height_mobile'] = f3_u( $o['minh_m'] ); }
	if ( isset( $o['class'] ) ) { $s['css_classes'] = $o['class']; }
	if ( isset( $o['id'] ) ) { $s['_element_id'] = $o['id']; }
	if ( isset( $o['anim'] ) ) { $s['animation'] = $o['anim']; $s['animation_delay'] = $o['delay'] ?? 0; $s['animation_duration'] = 'normal'; }
	if ( isset( $o['tag'] ) ) { $s['html_tag'] = $o['tag']; }
	if ( isset( $o['link'] ) ) { $s['link'] = [ 'url' => $o['link'], 'is_external' => '', 'nofollow' => '' ]; $s['html_tag'] = 'a'; }
	if ( isset( $o['overflow'] ) ) { $s['overflow'] = $o['overflow']; }
	if ( isset( $o['hide'] ) ) { foreach ( (array) $o['hide'] as $d ) { $s[ 'hide_' . $d ] = 'hidden-' . $d; } }
	foreach ( $o['extra'] ?? [] as $k => $v ) { $s[ $k ] = $v; }
	return [ 'id' => f3_id(), 'elType' => 'container', 'settings' => $s, 'elements' => array_values( array_filter( $children ) ), 'isInner' => $inner ];
}

/** Outer section wrapper: full-width band with boxed inner content. */
function f3_section( $o, $children ) {
	$y = $o['y'] ?? 120;
	$outer = [
		'inner' => false, 'pad' => [ $y, 24, $o['yb'] ?? $y, 24 ], 'pad_t' => [ round( $y * .75 ), 24, round( ( $o['yb'] ?? $y ) * .75 ), 24 ],
		'pad_m' => [ round( $y * .55 ), 16, round( ( $o['yb'] ?? $y ) * .55 ), 16 ], 'gap' => $o['gap'] ?? 56, 'boxed' => $o['boxed'] ?? 1360,
	];
	foreach ( [ 'bg', 'id', 'class', 'bg_img', 'minh', 'justify', 'align' ] as $k ) { if ( isset( $o[ $k ] ) ) { $outer[ $k ] = $o[ $k ]; } }
	return f3_c( $outer, $children );
}

/* ---------- widgets ---------- */

function f3_heading( $text, $tag = 'h2', $sizes = [ 64, 48, 36 ], $o = [] ) {
	$weight = $o['weight'] ?? '700';
	$lh = $o['lh'] ?? 1.05;
	$ls = $o['ls'] ?? -0.04;
	$s = [ 'title' => $text, 'header_size' => $tag, 'title_color' => $o['color'] ?? F3_INK ] + f3_type( 'typography', $sizes, $weight, $lh, $ls, $o['type_extra'] ?? [] );
	if ( isset( $o['align'] ) ) { $s['align'] = $o['align']; }
	if ( isset( $o['link'] ) ) { $s['link'] = [ 'url' => $o['link'] ]; }
	if ( isset( $o['anim'] ) ) { $s['_animation'] = $o['anim']; $s['_animation_delay'] = $o['delay'] ?? 0; }
	if ( isset( $o['maxw'] ) ) { $s['_element_width'] = 'initial'; $s['_element_custom_width'] = f3_u( $o['maxw'] ); $s['_element_width_mobile'] = 'inherit'; }
	$cls = trim( ( in_array( $tag, [ 'h1', 'h2' ], true ) ? 'f3-split ' : '' ) . ( $o['class'] ?? '' ) );
	if ( '' !== $cls ) { $s['_css_classes'] = $cls; }
	if ( isset( $o['margin'] ) ) { $s['_margin'] = f3_box( ...$o['margin'] ); }
	return f3_w( 'heading', $s );
}
function f3_eyebrow( $text, $color = F3_MUTED, $o = [] ) {
	return f3_heading( $text, 'p', [ 12 ], [ 'weight' => '600', 'lh' => 1.4, 'ls' => 0.12, 'color' => $color, 'type_extra' => [ 'text_transform' => 'uppercase' ] ] + $o );
}
function f3_text( $html, $o = [] ) {
	$sizes = $o['sizes'] ?? [ 16 ];
	$s = [ 'editor' => ( strpos( $html, '<' ) === 0 ? $html : '<p>' . $html . '</p>' ), 'text_color' => $o['color'] ?? F3_MUTED ]
		+ f3_type( 'typography', $sizes, $o['weight'] ?? '400', $o['lh'] ?? 1.6, $o['ls'] ?? null );
	if ( isset( $o['align'] ) ) { $s['align'] = $o['align']; }
	if ( isset( $o['maxw'] ) ) { $s['_element_width'] = 'initial'; $s['_element_custom_width'] = f3_u( $o['maxw'] ); $s['_element_width_mobile'] = 'inherit'; }
	if ( isset( $o['anim'] ) ) { $s['_animation'] = $o['anim']; $s['_animation_delay'] = $o['delay'] ?? 0; }
	if ( isset( $o['class'] ) ) { $s['_css_classes'] = $o['class']; }
	return f3_w( 'text-editor', $s );
}
function f3_lead( $html, $o = [] ) { return f3_text( $html, $o + [ 'sizes' => [ 20, 18, 17 ], 'lh' => 1.5 ] ); }

/** Pill button. $style: dark | light | outline | yellow */
function f3_button( $text, $url, $style = 'dark', $o = [] ) {
	$pal = [
		'dark'    => [ F3_INK, '#FFFFFF', F3_YELLOW, F3_INK ],
		'light'   => [ '#FFFFFF', F3_INK, F3_YELLOW, F3_INK ],
		'yellow'  => [ F3_YELLOW, F3_INK, F3_INK, '#FFFFFF' ],
		'outline' => [ 'transparent', F3_INK, F3_INK, '#FFFFFF' ],
	][ $style ];
	$s = [
		'text' => $text, 'link' => [ 'url' => $url, 'is_external' => '', 'nofollow' => '' ],
		'selected_icon' => [ 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ], 'icon_align' => 'row-reverse', 'icon_indent' => f3_u( 10 ),
		'background_background' => 'classic', 'background_color' => $pal[0], 'button_text_color' => $pal[1],
		'button_background_hover_background' => 'classic', 'button_background_hover_color' => $pal[2], 'hover_color' => $pal[3],
		'button_hover_transition_duration' => f3_u( 0.3, 's' ),
		'border_radius' => f3_box( 999 ), 'text_padding' => f3_box( 15, 26, 15, 26 ),
		'align' => $o['align'] ?? 'left',
	] + f3_type( 'typography', [ 15, 15, 14 ], '600', 1.2, -0.01 );
	if ( 'outline' === $style ) { $s['border_border'] = 'solid'; $s['border_width'] = f3_box( 1 ); $s['border_color'] = F3_INK; }
	if ( isset( $o['align_m'] ) ) { $s['align_mobile'] = $o['align_m']; }
	if ( isset( $o['anim'] ) ) { $s['_animation'] = $o['anim']; $s['_animation_delay'] = $o['delay'] ?? 0; }
	$s['_css_classes'] = trim( 'f3-btn f3-btn--' . $style . ' ' . ( $o['class'] ?? '' ) );
	return f3_w( 'button', $s );
}

/** Text link (no pill) used in nav and footer. */
function f3_link( $text, $url, $o = [] ) {
	return f3_heading( $text, $o['tag'] ?? 'div', $o['sizes'] ?? [ 14 ], [
		'weight' => $o['weight'] ?? '500', 'lh' => 1.3, 'ls' => $o['ls'] ?? 0, 'color' => $o['color'] ?? F3_INK, 'link' => $url,
		'class' => 'f3-link ' . ( $o['class'] ?? '' ), 'type_extra' => $o['type_extra'] ?? [],
	] );
}

function f3_icon_box( $icon, $title, $desc, $o = [] ) {
	$s = [
		'selected_icon' => [ 'value' => $icon, 'library' => strpos( $icon, 'fab ' ) === 0 ? 'fa-brands' : ( strpos( $icon, 'far ' ) === 0 ? 'fa-regular' : 'fa-solid' ) ],
		'title_text' => $title, 'description_text' => $desc, 'title_size' => $o['tag'] ?? 'h3',
		'position' => $o['position'] ?? 'top', 'text_align' => $o['text_align'] ?? 'left',
		'view' => $o['view'] ?? 'stacked', 'shape' => $o['shape'] ?? 'square',
		'primary_color' => 'default' === ( $o['view'] ?? 'stacked' ) ? ( $o['icon_color'] ?? F3_INK ) : ( $o['icon_bg'] ?? F3_S2 ), 'secondary_color' => $o['icon_color'] ?? F3_INK,
		'icon_size' => f3_u( $o['icon_size'] ?? 18 ), 'icon_padding' => f3_u( $o['icon_pad'] ?? 12 ), 'border_radius' => f3_box( 8 ),
		'icon_space' => f3_u( $o['icon_space'] ?? 28 ), 'title_bottom_space' => f3_u( 10 ),
		'title_color' => $o['title_color'] ?? F3_INK, 'description_color' => $o['desc_color'] ?? F3_MUTED,
		'hover_animation' => $o['hover'] ?? '',
	] + f3_type( 'title_typography', $o['title_sizes'] ?? [ 22, 20, 20 ], '700', 1.2, -0.02 )
	  + f3_type( 'description_typography', [ 15, 15, 15 ], '400', 1.55 );
	if ( 'none' === ( $o['icon'] ?? '' ) || '' === $icon ) { $s['selected_icon'] = [ 'value' => '', 'library' => '' ]; $s['view'] = 'default'; }
	if ( isset( $o['link'] ) ) { $s['link'] = [ 'url' => $o['link'] ]; }
	if ( isset( $o['anim'] ) ) { $s['_animation'] = $o['anim']; $s['_animation_delay'] = $o['delay'] ?? 0; }
	$s['_css_classes'] = 'f3-iconbox ' . ( $o['class'] ?? '' );
	return f3_w( 'icon-box', $s );
}

function f3_image_box( $img, $title, $desc, $o = [] ) {
	$s = [
		'image' => $img, 'title_text' => $title, 'description_text' => $desc, 'title_size' => $o['tag'] ?? 'h3',
		'position' => 'top', 'text_align' => $o['text_align'] ?? 'left', 'image_space' => f3_u( $o['space'] ?? 24 ),
		'image_size' => f3_u( $o['img_w'] ?? 100, '%' ), 'title_bottom_space' => f3_u( 8 ),
		'image_border_radius' => f3_box( $o['img_radius'] ?? 6 ),
		'title_color' => $o['title_color'] ?? F3_INK, 'description_color' => $o['desc_color'] ?? F3_MUTED,
		'hover_animation' => $o['hover'] ?? '', 'thumbnail_size' => 'full',
	] + f3_type( 'title_typography', $o['title_sizes'] ?? [ 24, 22, 20 ], '700', 1.2, -0.02 )
	  + f3_type( 'description_typography', [ 15, 15, 15 ], '400', 1.55 );
	if ( isset( $o['img_h'] ) ) { $s['image_height'] = f3_u( $o['img_h'] ); $s['image_object_fit'] = 'cover'; }
	if ( isset( $o['link'] ) ) { $s['link'] = [ 'url' => $o['link'] ]; }
	if ( isset( $o['anim'] ) ) { $s['_animation'] = $o['anim']; $s['_animation_delay'] = $o['delay'] ?? 0; }
	$s['_css_classes'] = 'f3-imagebox ' . ( $o['class'] ?? '' );
	return f3_w( 'image-box', $s );
}

function f3_image( $img, $o = [] ) {
	$s = [ 'image' => $img, 'image_size' => 'full', 'align' => $o['align'] ?? 'left' ];
	if ( isset( $o['w'] ) ) { $s['width'] = f3_u( $o['w'], $o['w_unit'] ?? '%' ); }
	if ( isset( $o['maxw'] ) ) { $s['space'] = f3_u( $o['maxw'], '%' ); }
	if ( isset( $o['h'] ) ) { $s['height'] = f3_u( $o['h'] ); $s['object-fit'] = 'cover'; }
	if ( isset( $o['h_m'] ) ) { $s['height_mobile'] = f3_u( $o['h_m'] ); }
	if ( isset( $o['radius'] ) ) { $s['image_border_radius'] = f3_box( $o['radius'] ); }
	if ( isset( $o['link'] ) ) { $s['link_to'] = 'custom'; $s['link'] = [ 'url' => $o['link'] ]; }
	if ( isset( $o['caption'] ) ) { $s['caption_source'] = 'custom'; $s['caption'] = $o['caption']; }
	if ( isset( $o['hover'] ) ) { $s['hover_animation'] = $o['hover']; }
	if ( isset( $o['anim'] ) ) { $s['_animation'] = $o['anim']; $s['_animation_delay'] = $o['delay'] ?? 0; }
	if ( isset( $o['alt'] ) ) { $s['image']['alt'] = $o['alt']; }
	$s['_css_classes'] = 'f3-img ' . ( $o['class'] ?? '' );
	return f3_w( 'image', $s );
}

function f3_counter( $end, $suffix, $title, $o = [] ) {
	return f3_w( 'counter', [
		'starting_number' => 0, 'ending_number' => $end, 'suffix' => $suffix, 'prefix' => '', 'duration' => 2000,
		'thousand_separator' => 'yes', 'thousand_separator_char' => ',', 'title' => $title, 'title_tag' => 'p',
		'title_position' => 'after', 'number_position' => 'start', 'title_horizontal_alignment' => 'start',
		'number_color' => $o['color'] ?? F3_INK, 'title_color' => $o['title_color'] ?? F3_MUTED, 'title_gap' => f3_u( 12 ),
	] + f3_type( 'typography_number', [ 100, 72, 56 ], '700', 1, -0.05 ) + f3_type( 'typography_title', [ 15 ], '500', 1.4 )
	  + [ '_animation' => 'fadeInUp', '_animation_delay' => $o['delay'] ?? 0 ] );
}

function f3_rating( $value = 5, $o = [] ) {
	return f3_w( 'rating', [
		'rating_scale' => f3_u( 5, '' ), 'rating_value' => $value, 'rating_icon' => [ 'value' => 'eicon-star', 'library' => 'eicons' ],
		'icon_size' => f3_u( $o['size'] ?? 16 ), 'icon_gap' => f3_u( 3 ), 'icon_color' => $o['color'] ?? F3_YELLOW, 'icon_unmarked_color' => '#DDDDDD',
		'icon_alignment' => 'start',
	] );
}

function f3_icon_list( $items, $o = [] ) {
	$list = [];
	foreach ( $items as $it ) {
		$it = (array) $it;
		$row = [ '_id' => f3_id(), 'text' => $it[0], 'selected_icon' => [ 'value' => $o['icon'] ?? 'fas fa-check-circle', 'library' => 'fa-solid' ] ];
		if ( isset( $it[1] ) ) { $row['link'] = [ 'url' => $it[1] ]; }
		$list[] = $row;
	}
	$s = [
		'icon_list' => $list, 'view' => $o['view'] ?? 'traditional', 'space_between' => f3_u( $o['space'] ?? 12 ),
		'icon_color' => $o['icon_color'] ?? F3_YELLOW, 'icon_size' => f3_u( $o['icon_size'] ?? 14 ), 'text_indent' => f3_u( 10 ),
		'text_color' => $o['color'] ?? F3_INK2, 'icon_self_vertical_align' => 'center',
	] + f3_type( 'icon_typography', $o['sizes'] ?? [ 14 ], $o['weight'] ?? '500', 1.45 );
	if ( ! empty( $o['divider'] ) ) { $s['divider'] = 'yes'; $s['divider_color'] = $o['divider_color'] ?? F3_LINE; $s['divider_weight'] = f3_u( 1 ); }
	if ( isset( $o['hover_color'] ) ) { $s['text_color_hover'] = $o['hover_color']; }
	if ( isset( $o['no_icon'] ) ) { foreach ( $s['icon_list'] as &$r ) { $r['selected_icon'] = [ 'value' => '', 'library' => '' ]; } unset( $r ); }
	return f3_w( 'icon-list', $s );
}

function f3_divider( $color = F3_LINE ) { return f3_w( 'divider', [ 'color' => $color, 'weight' => f3_u( 1 ), 'gap' => f3_u( 2 ) ] ); }
function f3_spacer( $h ) { return f3_w( 'spacer', [ 'space' => f3_u( $h ) ] ); }

function f3_testimonial( $quote, $name, $job, $img, $o = [] ) {
	return f3_w( 'testimonial', [
		'testimonial_content' => $quote, 'testimonial_name' => $name, 'testimonial_job' => $job,
		'testimonial_image' => $img, 'testimonial_image_position' => 'top', 'testimonial_alignment' => 'left',
		'content_content_color' => F3_INK2, 'name_text_color' => F3_INK, 'job_text_color' => F3_MUTED,
		'image_size' => f3_u( 120 ), 'image_border_radius' => f3_box( 0 ),
	] + f3_type( 'content_typography', [ 15 ], '500', 1.6 ) + f3_type( 'name_typography', [ 15 ], '700', 1.3 ) + f3_type( 'job_typography', [ 13 ], '400', 1.4 ) );
}

function f3_carousel( $imgs, $o = [] ) {
	$c = [];
	foreach ( $imgs as $i ) { $c[] = $i; }
	return f3_w( 'image-carousel', [
		'carousel' => $c, 'thumbnail_size' => 'full', 'slides_to_show' => (string) ( $o['show'] ?? 6 ), 'slides_to_show_tablet' => (string) ( $o['show_t'] ?? 4 ),
		'slides_to_show_mobile' => (string) ( $o['show_m'] ?? 2 ), 'slides_to_scroll' => '1', 'navigation' => 'none', 'autoplay' => 'yes',
		'autoplay_speed' => $o['speed_ms'] ?? 0, 'speed' => $o['speed'] ?? 3000, 'pause_on_hover' => 'no', 'pause_on_interaction' => 'no', 'infinite' => 'yes',
		'image_spacing' => 'custom', 'image_spacing_custom' => f3_u( $o['spacing'] ?? 56 ), 'image_stretch' => 'no', 'link_to' => 'none',
		'_css_classes' => 'f3-marquee ' . ( $o['class'] ?? '' ), 'gallery_vertical_align' => 'center',
	] );
}

/* ---------- shared blocks ---------- */

function f3_url( $slug = '' ) { return $slug ? home_url( '/' . trim( $slug, '/' ) . '/' ) : home_url( '/' ); }

function f3_header() {
	$nav = f3_c( [ 'dir' => 'row', 'gap' => 32, 'align' => 'center', 'hide' => [ 'mobile' ], 'class' => 'f3-nav' ], [
		f3_link( 'Home', f3_url(), [ 'sizes' => [ 13 ], 'weight' => '600', 'ls' => 0.06, 'type_extra' => [ 'text_transform' => 'uppercase' ] ] ),
		f3_link( 'UX & Product Design', f3_url( 'ux-and-product-design' ), [ 'sizes' => [ 13 ], 'weight' => '600', 'ls' => 0.06, 'type_extra' => [ 'text_transform' => 'uppercase' ] ] ),
		f3_link( 'AI Video', f3_url( 'ai-video-design' ), [ 'sizes' => [ 13 ], 'weight' => '600', 'ls' => 0.06, 'type_extra' => [ 'text_transform' => 'uppercase' ] ] ),
		f3_link( 'Prototypes', f3_url( 'interactive-prototypes' ), [ 'sizes' => [ 13 ], 'weight' => '600', 'ls' => 0.06, 'type_extra' => [ 'text_transform' => 'uppercase' ] ] ),
		f3_link( 'Work', f3_url( 'work' ), [ 'sizes' => [ 13 ], 'weight' => '600', 'ls' => 0.06, 'type_extra' => [ 'text_transform' => 'uppercase' ] ] ),
	] );
	$brand = f3_c( [ 'dir' => 'row', 'dir_m' => 'row', 'gap' => 12, 'align' => 'center', 'link' => f3_url(), 'class' => 'f3-brand' ], [
		f3_image( f3_static( 'folio3-mark.svg' ), [ 'w' => 64, 'w_unit' => 'px', 'alt' => 'Folio3' ] ),
		f3_heading( 'Design<br>Studio', 'div', [ 13 ], [ 'weight' => '600', 'lh' => 1.1, 'ls' => -0.01, 'class' => 'f3-brand-type' ] ),
	] );
	return f3_c( [ 'inner' => false, 'dir' => 'row', 'dir_m' => 'row', 'justify' => 'space-between', 'align' => 'center', 'align_m' => 'center', 'boxed' => 1360,
		'pad' => [ 18, 24 ], 'pad_m' => [ 14, 16 ], 'bg' => '#FFFFFFEB', 'class' => 'f3-header', 'tag' => 'header' ], [
		$brand,
		f3_c( [ 'dir' => 'row', 'dir_m' => 'row', 'gap' => 36, 'align' => 'center', 'align_m' => 'center' ], [ $nav, f3_button( 'Start a project', f3_url( 'contact' ), 'dark' ) ] ),
	] );
}

function f3_cta() {
	return f3_section( [ 'y' => 80, 'id' => 'contact' ], [
		f3_c( [ 'bg' => F3_INK, 'radius' => 24, 'pad' => [ 96, 72 ], 'pad_t' => [ 64, 40 ], 'pad_m' => [ 48, 24 ], 'gap' => 40, 'minh' => 520, 'minh_m' => 0,
			'justify' => 'flex-end', 'class' => 'f3-cta', 'anim' => 'fadeInUp', 'overflow' => 'hidden' ], [
			f3_c( [ 'dir' => 'row', 'justify' => 'space-between', 'align' => 'flex-end', 'gap' => 40, 'align_m' => 'flex-start' ], [
				f3_c( [ 'width' => 60, 'gap' => 28 ], [
					f3_heading( 'Have an idea, a prototype, or a product that needs fixing?', 'h2', [ 96, 64, 42 ], [ 'color' => '#FFFFFF', 'lh' => 0.95, 'ls' => -0.05, 'weight' => '800' ] ),
					f3_text( "Tell us where you are. We'll bring the right mix of designers, AI and engineering to the first call.", [ 'color' => '#999999', 'sizes' => [ 18, 17, 16 ] ] ),
				] ),
				f3_button( 'Start a project', f3_url( 'contact' ), 'light' ),
			] ),
		] ),
	] );
}

function f3_footer() {
	$col = function ( $title, $links ) {
		$items = [];
		foreach ( $links as $l ) { $items[] = f3_link( $l[0], $l[1], [ 'sizes' => [ 15 ], 'weight' => '500', 'color' => F3_INK ] ); }
		return f3_c( [ 'gap' => 14 ], array_merge( [ f3_eyebrow( $title ) ], $items ) );
	};
	return f3_c( [ 'inner' => false, 'boxed' => 1360, 'pad' => [ 96, 24, 40, 24 ], 'pad_m' => [ 64, 16, 32, 16 ], 'gap' => 64, 'tag' => 'footer', 'class' => 'f3-footer' ], [
		f3_c( [ 'dir' => 'row', 'gap' => 48, 'justify' => 'space-between' ], [
			f3_c( [ 'width' => 32, 'gap' => 20 ], [
				f3_image( f3_static( 'folio3-mark.svg' ), [ 'w' => 90, 'w_unit' => 'px', 'alt' => 'Folio3' ] ),
				f3_text( 'The product design studio inside Folio3. We design alongside the teams that build and run what we make.', [ 'maxw' => 340 ] ),
			] ),
			f3_c( [ 'grid' => 3, 'grid_t' => 3, 'grid_m' => 1, 'gap' => 40, 'width' => 60 ], [
				$col( 'Design', [ [ 'UX & Product Design', f3_url( 'ux-and-product-design' ) ], [ 'AI-Built Prototypes', f3_url( 'interactive-prototypes' ) ], [ 'AI Video Design', f3_url( 'ai-video-design' ) ] ] ),
				$col( 'Design + Build', [ [ 'Prototype to Product', f3_url() . '#offers' ], [ 'AI Experience Design', f3_url() . '#offers' ], [ 'Platform UX', f3_url() . '#offers' ], [ 'Design Systems That Ship', f3_url() . '#offers' ] ] ),
				$col( 'Studio', [ [ 'Work', f3_url( 'work' ) ], [ 'Process', f3_url() . '#process' ], [ 'Contact', f3_url( 'contact' ) ], [ 'Folio3.com', 'https://www.folio3.com/' ] ] ),
			] ),
		] ),
		f3_heading( 'Folio3', 'div', [ 300, 200, 110 ], [ 'weight' => '800', 'lh' => 0.8, 'ls' => -0.07, 'class' => 'f3-mega', 'color' => F3_INK ] ),
		f3_c( [ 'dir' => 'row', 'justify' => 'space-between', 'gap' => 16, 'pad' => [ 24, 0, 0, 0 ], 'border' => F3_LINE, 'border_w' => [ 1, 0, 0, 0 ] ], [
			f3_text( '© 2026 Folio3. All rights reserved.', [ 'sizes' => [ 13 ] ] ),
			f3_text( 'United States · Canada · Mexico · Bulgaria · UAE · Australia · Pakistan', [ 'sizes' => [ 13 ] ] ),
		] ),
	] );
}

/** Save Elementor data to a post and set canvas template. */
function f3_strip_anim( $els ) {
	foreach ( $els as &$el ) {
		foreach ( [ 'animation', '_animation', 'animation_delay', '_animation_delay', 'animation_duration', 'hover_animation' ] as $k ) { unset( $el['settings'][ $k ] ); }
		if ( ! empty( $el['elements'] ) ) { $el['elements'] = f3_strip_anim( $el['elements'] ); }
	}
	return $els;
}
function f3_save( $post_id, $elements, $type = 'wp-page' ) {
	$elements = f3_strip_anim( array_values( $elements ) );
	$json = wp_json_encode( array_values( $elements ) );
	update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
	update_post_meta( $post_id, '_elementor_template_type', $type );
	update_post_meta( $post_id, '_elementor_version', ELEMENTOR_VERSION );
	update_post_meta( $post_id, '_wp_page_template', 'elementor_canvas' );
	update_post_meta( $post_id, '_elementor_data', wp_slash( $json ) );
	update_post_meta( $post_id, '_elementor_page_settings', [ 'hide_title' => 'yes', 'background_background' => 'classic', 'background_color' => '#FFFFFF' ] );
	delete_post_meta( $post_id, '_elementor_css' );
	delete_post_meta( $post_id, '_elementor_element_cache' );
	return strlen( $json );
}

function f3_page( $slug, $title, $parent = 0, $type = 'page' ) {
	$existing = get_page_by_path( $slug, OBJECT, $type );
	$args = [ 'post_type' => $type, 'post_status' => 'publish', 'post_title' => $title, 'post_name' => $slug, 'post_parent' => $parent, 'post_content' => '' ];
	if ( $existing ) { $args['ID'] = $existing->ID; return wp_update_post( $args ); }
	return wp_insert_post( $args );
}
