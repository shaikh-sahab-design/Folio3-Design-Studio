<?php
/** Home page Elementor tree. */
function f3_chip( $text, $bg = F3_S2, $color = F3_INK ) {
	return f3_heading( $text, 'span', [ 12 ], [ 'weight' => '600', 'lh' => 1, 'ls' => 0, 'color' => $color, 'class' => 'f3-chip', 'type_extra' => [] ] + [ 'margin' => [ 0 ] ] )
		+ [];
}
function f3_chips( $list ) {
	$colors = [ 'Design' => F3_YELLOW, 'AI' => F3_RED, 'Engineering' => F3_BLUE, 'Platforms' => F3_TEAL ];
	$out = [];
	foreach ( $list as $c ) {
		$w = f3_chip( $c );
		$w['settings']['_css_classes'] = 'f3-chip f3-chip--' . strtolower( $c );
		$out[] = $w;
	}
	return f3_c( [ 'dir' => 'row', 'dir_m' => 'row', 'gap' => 6, 'wrap' => true ], $out );
}

function f3_page_home() {
	$E = [];
	$E[] = f3_header();

	/* Hero */
	$caps = [
		[ 'Studio', 'D', 'Design', 'Research, UX, UI, design systems', F3_YELLOW, F3_INK, 'fas fa-pen-nib' ],
		[ 'Studio + AI', 'AI', 'Prototype with AI', 'Coded prototypes users can try', F3_RED, '#FFFFFF', 'fas fa-magic' ],
		[ 'Engineering', 'E', 'Build', 'Web, mobile, AI agents, cloud', F3_BLUE, '#FFFFFF', 'fas fa-code' ],
		[ 'Platforms', 'P', 'Integrate and run', 'NetSuite, Dynamics 365, Salesforce, AWS', F3_TEAL, F3_INK, 'fas fa-plug' ],
	];
	$cards = [];
	foreach ( $caps as $i => $c ) {
		$cards[] = f3_c( [ 'bg' => $c[4], 'radius' => 14, 'pad' => [ 24 ], 'gap' => 40, 'justify' => 'space-between', 'minh' => 240, 'minh_m' => 180,
			'class' => 'f3-hero-card', 'anim' => 'fadeInUp', 'delay' => 150 + $i * 120 ], [
			f3_c( [ 'dir' => 'row', 'dir_m' => 'row', 'justify' => 'space-between', 'align' => 'center' ], [
				f3_eyebrow( $c[0], $c[5] ),
				f3_heading( $c[1], 'span', [ 13 ], [ 'weight' => '700', 'color' => $c[4], 'ls' => 0, 'class' => 'f3-badge f3-badge--' . ( $c[5] === F3_INK ? 'dark' : 'light' ) ] ),
			] ),
			f3_c( [ 'gap' => 8 ], [
				f3_heading( $c[2], 'h3', [ 30, 26, 24 ], [ 'color' => $c[5], 'ls' => -0.03, 'lh' => 1.05 ] ),
				f3_text( $c[3], [ 'color' => $c[5], 'sizes' => [ 15 ], 'lh' => 1.4 ] ),
			] ),
		] );
	}
	$E[] = f3_section( [ 'y' => 72, 'yb' => 96, 'gap' => 64, 'class' => 'f3-hero' ], [
		f3_heading( 'One team, from first sketch to shipped product.', 'h1', [ 150, 96, 54 ], [ 'weight' => '800', 'lh' => 0.92, 'ls' => -0.055, 'maxw' => 1240, 'class' => 'f3-hero-title' ] ),
		f3_c( [ 'grid' => 4, 'grid_t' => 2, 'grid_m' => 1, 'gap' => 16, 'class' => 'f3-hero-deck' ], $cards ),
	] );

	/* Statement */
	$E[] = f3_section( [ 'y' => 40, 'yb' => 120, 'gap' => 40 ], [
		f3_heading( 'We’re Folio3’s design studio, turning ideas into reality through AI-powered design and collaboration.', 'p', [ 72, 52, 34 ], [ 'weight' => '700', 'lh' => 1.02, 'ls' => -0.045, 'maxw' => 1200, 'class' => 'f3-statement' ] ),
		f3_c( [ 'dir' => 'row', 'dir_m' => 'row', 'gap' => 12, 'wrap' => true ], [
			f3_button( 'Start a project', f3_url( 'contact' ), 'dark' ),
			f3_button( 'See the work', f3_url( 'work' ), 'outline' ),
		] ),
	] );

	/* Proof: counters */
	$E[] = f3_section( [ 'y' => 64, 'gap' => 0 ], [
		f3_c( [ 'grid' => 4, 'grid_t' => 2, 'grid_m' => 2, 'gap' => 24, 'rgap' => 48, 'pad' => [ 48, 0, 0, 0 ], 'border' => F3_LINE, 'border_w' => [ 1, 0, 0, 0 ] ], [
			f3_counter( 20, '+', 'years building software' ),
			f3_counter( 700, '+', 'Folio3 team members', [ 'delay' => 100 ] ),
			f3_counter( 5000, '+', 'projects delivered', [ 'delay' => 200 ] ),
			f3_counter( 1000, '+', 'companies served', [ 'delay' => 300 ] ),
		] ),
	] );

	/* Clients logo wall */
	$logos = [ 'mispay.svg', 'bmc.png', 'neom.png', 'squaretrade.png', 'zoetis.png', 'boehringer.png', 'mit.png', 'squaretrade-2.png', 'huvepharma.png', 'allstate.png', 'cisco.png', 'engro.png', 'standard-chartered.png', 'angus.png', 'zareens.png', 'face-reality.png', 'barnes-noble.png', 'hiplink.png', 'honda.png', 'mercedes-benz.png', 'jaguar.png' ];
	$cells = [];
	foreach ( $logos as $i => $l ) {
		$cells[] = f3_c( [ 'align' => 'center', 'justify' => 'center', 'minh' => 96, 'minh_m' => 72, 'pad' => [ 12 ], 'class' => 'f3-logo-cell' ], [
			f3_image( f3_img( 'assets/logos/' . $l ), [ 'class' => 'f3-logo', 'align' => 'center', 'anim' => 'fadeIn', 'delay' => ( $i % 7 ) * 60 ] ),
		] );
	}
	$E[] = f3_section( [ 'y' => 96, 'gap' => 48, 'class' => 'f3-clients' ], [
		f3_heading( 'Folio3 has built for', 'h2', [ 28, 24, 22 ], [ 'ls' => -0.02 ] ),
		f3_c( [ 'grid' => 7, 'grid_t' => 4, 'grid_m' => 3, 'gap' => 16, 'rgap' => 16, 'class' => 'f3-logos' ], $cells ),
	] );

	/* Capabilities */
	$capdata = [
		[ 'Design', 'Design Studio', 'design.jpg', F3_YELLOW, [ [ 'UX & Product Design', 'Research, flows, UI, design systems' ], [ 'Interactive & AI-Built Prototypes', 'Testable, coded prototypes' ], [ 'AI Video Design', 'Product and marketing video' ] ], 'Led by the studio', [ 'figma.png' => 'Figma', 'figjam.png' => 'FigJam', 'framer.png' => 'Framer', 'after-effects.png' => 'After Effects' ] ],
		[ 'AI', 'AI & Data', 'ai.jpg', '#FFFFFF', [ [ 'Phase 0 AI Discovery', 'Find the highest-ROI workflows' ], [ 'AI agents and copilots', 'For ERP, healthcare, retail, support' ], [ 'Talk to Your Data, AI Guardian', 'Data access and AI governance' ] ], 'Folio3 AI practice', [ 'openai.png' => 'OpenAI', 'azure-openai.png' => 'Azure OpenAI', 'copilot-studio.png' => 'Copilot Studio', 'python.png' => 'Python' ] ],
		[ 'Engineering', 'Product Engineering', 'eng.jpg', '#FFFFFF', [ [ 'Web and mobile apps', 'MVPs to scaled products' ], [ 'Ecommerce builds', 'Storefronts and commerce ops' ], [ 'Cloud, security, managed services', 'Run and support after launch' ] ], 'Folio3 engineering', [ 'react.png' => 'React', 'react-native.png' => 'React Native', 'nodejs.png' => 'Node.js', 'aws.png' => 'AWS' ] ],
		[ 'Platforms', 'Enterprise Platforms', 'platform.jpg', '#FFFFFF', [ [ 'Oracle NetSuite', 'Implementation and SuiteCommerce' ], [ 'Microsoft Dynamics 365', 'ERP and Copilot Studio' ], [ 'Salesforce and AWS', 'CRM and cloud infrastructure' ] ], 'Folio3 platform practices', [ 'oracle-netsuite.png' => 'Oracle NetSuite', 'dynamics-365.png' => 'Dynamics 365', 'salesforce.png' => 'Salesforce', 'aws.png' => 'AWS' ] ],
	];
	$capcards = [];
	foreach ( $capdata as $i => $c ) {
		$items = [];
		foreach ( $c[4] as $it ) {
			$items[] = f3_icon_box( '', $it[0], $it[1], [ 'icon' => 'none', 'title_sizes' => [ 16, 16, 15 ], 'class' => 'f3-capitem' ] );
		}
		$tools = [];
		foreach ( $c[6] as $file => $name ) {
			$tools[] = f3_c( [ 'dir' => 'row', 'dir_m' => 'row', 'gap' => 10, 'align' => 'center', 'bg' => '#FFFFFF', 'radius' => 8, 'pad' => [ 8, 12 ], 'class' => 'f3-tool' ], [
				f3_image( f3_img( 'assets/tools/' . $file ), [ 'w' => 22, 'w_unit' => 'px' ] ),
				f3_heading( $name, 'span', [ 13 ], [ 'weight' => '600', 'ls' => 0 ] ),
			] );
		}
		$capcards[] = f3_c( [ 'dir' => 'row', 'gap' => 40, 'bg' => F3_S2, 'radius' => 12, 'pad' => [ 24, 40, 24, 24 ], 'pad_m' => [ 16, 16, 28, 16 ], 'anim' => 'fadeInUp', 'class' => 'f3-capcard', 'align' => 'center' ], [
			f3_c( [ 'width' => 50, 'bg_img' => f3_img( 'assets/caps/' . $c[2] ), 'radius' => 8, 'minh' => 360, 'minh_m' => 240, 'pad' => [ 16 ], 'justify' => 'flex-end' ], [
				f3_c( [ 'grid' => 2, 'grid_t' => 2, 'grid_m' => 2, 'gap' => 8 ], $tools ),
			] ),
			f3_c( [ 'width' => 50, 'gap' => 16 ], array_merge(
				[ f3_chips( [ $c[0] ] ), f3_heading( $c[1], 'h3', [ 44, 36, 30 ], [ 'ls' => -0.035 ] ) ],
				[ f3_c( [ 'gap' => 0, 'class' => 'f3-caplist' ], $items ) ],
				[ f3_eyebrow( $c[5], F3_INK ) ]
			) ),
		] );
	}
	$E[] = f3_section( [ 'y' => 120, 'gap' => 56, 'bg' => '#FAFAFA', 'id' => 'capabilities' ], [
		f3_c( [ 'dir' => 'row', 'justify' => 'space-between', 'align' => 'flex-end', 'gap' => 40, 'align_m' => 'flex-start' ], [
			f3_c( [ 'width' => 55, 'gap' => 16 ], [
				f3_eyebrow( 'One team, four capabilities' ),
				f3_heading( 'Start with design. Bring in all of Folio3 when the work needs it.', 'h2', [ 64, 48, 36 ], [ 'anim' => 'fadeInUp' ] ),
			] ),
			f3_text( 'Each color marks one Folio3 capability. The studio leads the project and brings in the rest of the company as the work needs it.', [ 'sizes' => [ 18, 17, 16 ], 'maxw' => 440 ] ),
		] ),
		f3_c( [ 'gap' => 16, 'class' => 'f3-capsdeck' ], $capcards ),
	] );

	/* Offers */
$offers = [
		[ [ 'Design', 'Engineering' ], 'Prototype to Product', 'Validate an idea with an AI-built prototype real users can try, then take it to production with our engineers. No rebuild from zero, no lost context.', 'Prototype → production, one team', F3_BLUE ],
		[ [ 'Design', 'AI' ], 'AI Experience Design', 'Copilots, agents and AI features people trust. Our designers shape the experience while our AI practice builds the models, agents and guardrails.', 'Phase 0 discovery → designed and built AI feature', F3_RED ],
		[ [ 'Design', 'Platforms' ], 'Platform UX', 'Customer portals, storefronts and back-office screens on NetSuite, Dynamics 365 and Salesforce that feel like products, not admin panels.', 'UX → platform implementation, one team', F3_TEAL ],
		[ [ 'Design', 'Engineering' ], 'Design Systems That Ship', 'Tokens, Figma libraries and a coded component library delivered together, so design and engineering work from one source.', 'Figma library + coded components', F3_BLUE ],
	];
	$oc = [];
	foreach ( $offers as $i => $o ) {
		$card = f3_c( [ 'width' => 44, 'bg' => '#FFFFFF', 'radius' => 12, 'pad' => [ 36 ], 'pad_m' => [ 24 ], 'gap' => 20, 'border' => '#E3E4E5', 'class' => 'f3-offer' ], [
			f3_c( [ 'dir' => 'row', 'dir_m' => 'row', 'justify' => 'space-between', 'align' => 'center' ], [
				f3_w( 'divider', [ 'color' => $o[4], 'weight' => f3_u( 3 ), 'width' => f3_u( 28, 'px' ), 'gap' => f3_u( 2 ) ] ),
				f3_chips( $o[0] ),
			] ),
			f3_heading( $o[1], 'h3', [ 30, 26, 24 ], [ 'ls' => -0.03 ] ),
			f3_text( $o[2] ),
			f3_icon_list( [ $o[3] ], [ 'icon' => 'fas fa-circle', 'icon_size' => 7, 'icon_color' => $o[4], 'sizes' => [ 13 ], 'weight' => '600' ] ),
			f3_button( 'Connect with Us', f3_url( 'contact' ), 'dark' ),
		] );
		$num = f3_c( [ 'width' => 44, 'hide' => [ 'mobile' ], 'class' => 'f3-flow__numwrap' ], [
			f3_heading( sprintf( '%02d', $i + 1 ), 'span', [ 150, 110, 80 ], [ 'weight' => '800', 'color' => '#EEEEEE', 'ls' => -0.06, 'lh' => 0.9, 'class' => 'f3-flow__num', 'align' => $i % 2 ? 'right' : 'left' ] ),
		] );
		$oc[] = f3_c( [ 'dir' => 'row', 'justify' => 'space-between', 'align' => 'center', 'gap' => 0, 'class' => 'f3-flow__step f3-flow__step--' . ( $i % 2 ? 'r' : 'l' ) ], $i % 2 ? [ $num, $card ] : [ $card, $num ] );
	}
	$E[] = f3_section( [ 'y' => 120, 'gap' => 56, 'id' => 'offers' ], [
		f3_c( [ 'gap' => 16 ], [ f3_eyebrow( 'Ways to work with us' ), f3_heading( 'Design-led offers that end in a working product', 'h2', [ 64, 48, 36 ], [ 'maxw' => 900 ] ) ] ),
		f3_c( [ 'gap' => 56, 'class' => 'f3-flow' ], $oc ),
	] );

	/* Stages */
	$stages = [
		[ '01/03', 'Startups', 'First product', [ 'Design', 'AI', 'Engineering' ], 'Prove the idea before you fund the build', 'A small team, a tight runway, and a product that has to win its first users.', [ [ 'Test the idea first', 'Coded, AI-built prototypes real users can click' ], [ 'Design an MVP that can grow', "A lean design system you won't throw away" ], [ 'Ship with the same team', 'Folio3 engineers build the MVP you designed' ], [ 'Tell the story', 'Product visuals and video for launch and investors' ] ], 'Start with Launch Lab' ],
		[ '02/03', 'Mid-size', 'Scaling product', [ 'Design', 'Engineering', 'AI' ], 'Grow the product without slowing the team', 'Real customers, a growing roadmap, and UX and code debt that piles up with every release.', [ [ 'Fix the journeys that matter', 'UX audit and redesign of your core flows' ], [ 'One system for design and code', 'Shared tokens, Figma library and coded components' ], [ 'Add AI where it pays off', 'Copilots and agents inside the product you have' ], [ 'Capacity when you need it', 'Design and engineering squads that join your sprints' ] ], 'Start with Growth Studio' ],
		[ '03/03', 'Enterprise', 'Product portfolio', [ 'Design', 'Platforms', 'AI' ], 'Modernize the systems the business runs on', 'Many teams, many systems, and NetSuite, Dynamics 365 or Salesforce at the core, with security and governance to match.', [ [ 'Portals people want to use', 'Customer and back-office UX on your platforms' ], [ 'AI with guardrails', 'Governed agents with cost and audit controls' ], [ 'Consistency across products', 'Design systems shared by every product line' ], [ 'Teams inside your teams', 'Embedded designers and forward deployed engineers' ] ], 'Start with Platform Refresh' ],
	];
	$sc = [];
	foreach ( $stages as $i => $s ) {
		$pts = [];
		foreach ( $s[6] as $p ) { $pts[] = f3_icon_box( '', $p[0], $p[1], [ 'icon' => 'none', 'title_sizes' => [ 15, 15, 15 ], 'class' => 'f3-capitem f3-capitem--sm' ] ); }
		$sc[] = f3_c( [ 'bg' => F3_S2, 'radius' => 12, 'pad' => [ 32 ], 'pad_m' => [ 24 ], 'gap' => 20, 'justify' => 'space-between', 'class' => 'f3-card f3-stage', 'anim' => 'fadeInUp', 'delay' => $i * 120 ], [
			f3_c( [ 'gap' => 16 ], [
				f3_heading( $s[0], 'span', [ 13 ], [ 'weight' => '700', 'ls' => 0.02 ] ),
				f3_heading( '<strong>' . strtoupper( $s[1] ) . '</strong>  ' . $s[2], 'p', [ 12 ], [ 'weight' => '500', 'color' => F3_MUTED, 'ls' => 0.06 ] ),
				f3_chips( $s[3] ),
				f3_heading( $s[4], 'h3', [ 26, 24, 22 ], [ 'ls' => -0.03, 'lh' => 1.12 ] ),
				f3_text( $s[5], [ 'sizes' => [ 15 ] ] ),
				f3_c( [ 'gap' => 0, 'class' => 'f3-caplist' ], $pts ),
			] ),
			f3_button( $s[7], f3_url( 'contact' ), 'dark' ),
		] );
	}
	$E[] = f3_section( [ 'y' => 120, 'gap' => 56 ], [
		f3_c( [ 'gap' => 16 ], [
			f3_eyebrow( 'Startups · Mid-size · Enterprise' ),
			f3_heading( 'Built for every stage of growth', 'h2', [ 64, 48, 36 ], [ 'anim' => 'fadeInUp' ] ),
			f3_lead( "A startup needs proof. A growing company needs speed. An enterprise needs change it can trust. We shape each engagement around where you are, and bring in Folio3's engineering, AI and platform teams as you grow.", [ 'maxw' => 820 ] ),
		] ),
		f3_c( [ 'grid' => 3, 'grid_t' => 1, 'grid_m' => 1, 'gap' => 16, 'class' => 'f3-picks' ], $sc ),
	] );

	/* Big type band */
	$E[] = f3_c( [ 'inner' => false, 'pad' => [ 40, 0 ], 'overflow' => 'hidden', 'class' => 'f3-ticker' ], [
		f3_heading( 'ideas in work.', 'div', [ 260, 180, 110 ], [ 'weight' => '800', 'lh' => 1, 'ls' => -0.06, 'class' => 'f3-ticker__text' ] ),
	] );

	/* Work */
	$E[] = f3_section( [ 'y' => 96, 'gap' => 56, 'id' => 'work' ], [
		f3_c( [ 'gap' => 16 ], [ f3_eyebrow( 'Case studies' ), f3_heading( 'Proven work, real products', 'h2', [ 64, 48, 36 ], [ 'anim' => 'fadeInUp' ] ) ] ),
		f3_c( [ 'grid' => 2, 'grid_t' => 2, 'grid_m' => 1, 'gap' => 24, 'rgap' => 48 ], [
			f3_work_card( 'mispay', 'MISPAY', 'One payment app for the whole shopping journey', 'UX & UI', 'assets/work/mispay.jpg' ),
			f3_work_card( 'risely', 'Risely', 'Student growth platform for schools', 'Product design', 'assets/work/risely.jpg', 150 ),
			f3_work_card( 'genopalate', 'GenoPalate', 'DNA-based nutrition, web experience', 'Web design', 'assets/work/genopalate.jpg' ),
			f3_work_card( 'scottdale-bachelorette', 'Scottdale Bachelorette', 'Website redesign', 'Web design', 'assets/work/scottdale.jpg', 150 ),
		] ),
		f3_button( 'View All Case Studies', f3_url( 'work' ), 'dark', [ 'align' => 'center' ] ),
	] );

	/* Industries */
	$ind = [
		[ 'fas fa-heartbeat', 'Healthcare', 'Clinical note summarization, patient intake agents, PACScribe imaging software' ],
		[ 'fas fa-shopping-bag', 'Retail & eCommerce', 'Storefronts, shopping assistants, Burq delivery orchestration' ],
		[ 'fas fa-seedling', 'Agriculture & Livestock', 'AgriERP and Cattlytics, computer vision for herd health' ],
		[ 'fas fa-chart-bar', 'ERP & Finance Ops', 'NetSuite and Dynamics 365, AP automation and forecasting agents' ],
		[ 'fas fa-credit-card', 'Fintech & Payments', 'Payment and checkout experiences like MISPAY' ],
		[ 'fas fa-graduation-cap', 'Education', 'Learning and student growth platforms like Risely' ],
		[ 'fas fa-trophy', 'Sports', 'Video analysis, player tracking, highlight generation' ],
		[ 'fas fa-shield-alt', 'Security & Mobility', 'License plate recognition, access control, occupancy analytics' ],
	];
	$ic = [];
	foreach ( $ind as $i => $d ) {
		$ic[] = f3_c( [ 'pad' => [ 28 ], 'pad_m' => [ 20 ], 'class' => 'f3-ind', 'minh' => 220, 'minh_m' => 0 ], [
			f3_icon_box( $d[0], $d[1], $d[2], [ 'view' => 'default', 'icon_size' => 22, 'icon_space' => 40, 'icon_color' => F3_INK, 'title_sizes' => [ 18, 18, 17 ], 'anim' => 'fadeInUp', 'delay' => ( $i % 4 ) * 80, 'hover' => 'float' ] ),
		] );
	}
	$E[] = f3_section( [ 'y' => 120, 'gap' => 56, 'id' => 'industries' ], [
		f3_c( [ 'gap' => 16 ], [
			f3_eyebrow( 'Industries' ),
			f3_heading( 'Design informed by the products we already build and run', 'h2', [ 64, 48, 36 ], [ 'anim' => 'fadeInUp', 'maxw' => 820 ] ),
			f3_lead( 'Folio3 builds and runs its own industry products, so our designers start every project with that domain knowledge.', [ 'maxw' => 640 ] ),
		] ),
		f3_c( [ 'grid' => 4, 'grid_t' => 2, 'grid_m' => 1, 'gap' => 0, 'border' => F3_LINE, 'radius' => 4, 'class' => 'f3-indgrid', 'rows' => 2 ], $ic ),
	] );

	/* Process */
	$steps = [ [ 'Discover', 'Goals, users, constraints, and where AI pays off.' ], [ 'Design', 'Flows, UI and a design system tied to real data.' ], [ 'Prototype', 'Coded, AI-built prototypes tested with users.' ], [ 'Build', 'Production engineering with designers in the sprint.' ], [ 'Run', 'Launch, integrate, support and improve.' ] ];
	$bars = []; $pc = [];
	foreach ( $steps as $i => $s ) {
		$bars[] = f3_c( [ 'dir' => 'row', 'dir_m' => 'row', 'class' => 'f3-gantt__row', 'extra' => [ 'margin' => f3_box( 0, 0, 0, 0 ) ] ], [
			f3_c( [ 'bg' => F3_INK2, 'radius' => 3, 'pad' => [ 10, 14 ], 'class' => 'f3-gantt__bar f3-gantt__bar--' . ( $i + 1 ), 'anim' => 'fadeInLeft', 'delay' => $i * 150 ], [
				f3_heading( $s[0], 'span', [ 14 ], [ 'color' => '#FFFFFF', 'weight' => '600', 'ls' => 0 ] ),
			] ),
		] );
		$pc[] = f3_icon_box( '', $s[0], $s[1], [ 'icon' => 'none', 'title_sizes' => [ 28, 24, 22 ], 'class' => 'f3-step' ] );
	}
	$E[] = f3_section( [ 'y' => 120, 'gap' => 56, 'id' => 'process' ], [
		f3_c( [ 'gap' => 16 ], [
			f3_eyebrow( 'How a project runs' ),
			f3_heading( 'One relay,<br>no handoff gap', 'h2', [ 64, 48, 36 ], [ 'anim' => 'fadeInUp' ] ),
			f3_lead( 'The studio stays on the project after design. Engineers join early, so what we design is what gets built.', [ 'maxw' => 560 ] ),
		] ),
		f3_c( [ 'gap' => 10, 'class' => 'f3-gantt', 'hide' => [ 'mobile' ] ], array_reverse( $bars ) ),
		f3_c( [ 'grid' => 5, 'grid_t' => 3, 'grid_m' => 1, 'gap' => 24 ], $pc ),
	] );

	/* Engagement models (pricing-table style) */
	$models = [
		[ 'Model 1', 'Design only', 'Research, UX, UI and a handoff package any engineering team can build from.', [ 'User research and UX strategy', 'UI design and design system', 'Dev-ready handoff package' ], true ],
		[ 'Model 2', 'Design + Build', 'One contract, one team: designers and engineers work together from discovery to launch.', [ 'Everything in Design only', 'Folio3 engineering squad', 'Launch, integrate and support' ], false ],
		[ 'Model 3', 'Embedded team', 'Designers and forward deployed engineers who work inside your team and process.', [ 'Designers in your sprints', 'Forward deployed engineers', 'Scale up or down as needed' ], false ],
	];
	$mc = [];
	foreach ( $models as $i => $m ) {
		$dark = false;
		$mc[] = f3_c( [ 'bg' => $dark ? F3_INK : F3_S2, 'radius' => 8, 'pad' => [ 36 ], 'pad_m' => [ 24 ], 'gap' => 24, 'minh' => 440, 'minh_m' => 0, 'justify' => 'space-between', 'class' => 'f3-card f3-price' . ( $dark ? ' f3-price--dark' : '' ), 'anim' => 'fadeInUp', 'delay' => $i * 120 ], [
			f3_c( [ 'gap' => 16 ], [
				f3_eyebrow( $m[0], $dark ? '#999999' : F3_MUTED ),
				f3_heading( $m[1], 'h3', [ 40, 34, 30 ], [ 'color' => $dark ? '#FFFFFF' : F3_INK, 'ls' => -0.035 ] ),
				f3_text( $m[2], [ 'color' => $dark ? '#999999' : F3_MUTED ] ),
			] ),
			f3_divider( $dark ? '#2A2A2A' : '#E3E4E5' ),
			f3_icon_list( $m[3], [ 'color' => $dark ? '#FFFFFF' : F3_INK2, 'icon_color' => F3_YELLOW, 'space' => 14 ] ),
			f3_button( 'Talk to us', f3_url( 'contact' ), $dark ? 'yellow' : 'dark' ),
		] );
	}
	$E[] = f3_section( [ 'y' => 120, 'gap' => 56, 'id' => 'engage' ], [
		f3_c( [ 'gap' => 16 ], [ f3_eyebrow( 'Engagement models' ), f3_heading( 'Use as much of<br>Folio3 as you need', 'h2', [ 64, 48, 36 ], [ 'anim' => 'fadeInUp' ] ) ] ),
		f3_c( [ 'grid' => 3, 'grid_t' => 1, 'grid_m' => 1, 'gap' => 16, 'class' => 'f3-models' ], $mc ),
	] );

	/* Partners */
	$partners = [ [ 'oracle-netsuite.png', 'Oracle NetSuite', 'Partner of the Year 2025, Middle East' ], [ 'microsoft.png', 'Microsoft', 'Dynamics 365 and Copilot Studio; AI Guardian on Microsoft Marketplace' ], [ 'aws.png', 'AWS', 'Lambda Service Delivery Designation' ], [ 'clutch.png', 'Clutch', 'Top NetSuite Company 2024' ] ];
	$pcards = [];
	foreach ( $partners as $i => $p ) {
		$pcards[] = f3_c( [ 'bg' => F3_S2, 'radius' => 8, 'pad' => [ 28 ], 'gap' => 48, 'minh' => 300, 'minh_m' => 0, 'justify' => 'space-between', 'class' => 'f3-card f3-partner' . ( $i % 2 ? ' f3-partner--low' : '' ), 'anim' => 'fadeInUp', 'delay' => $i * 100 ], [
			f3_heading( sprintf( '%02d', $i + 1 ), 'span', [ 13 ], [ 'weight' => '600', 'color' => F3_MUTED, 'ls' => 0 ] ),
			f3_image( f3_img( 'assets/partners/' . $p[0] ), [ 'w' => 140, 'w_unit' => 'px' ] ),
			f3_icon_box( '', $p[1], $p[2], [ 'icon' => 'none', 'title_sizes' => [ 24, 22, 20 ] ] ),
		] );
	}
	$E[] = f3_section( [ 'y' => 120, 'gap' => 56 ], [
		f3_heading( 'Recognized by the platforms we build on', 'h2', [ 64, 48, 36 ], [ 'anim' => 'fadeInUp', 'maxw' => 820 ] ),
		f3_c( [ 'grid' => 4, 'grid_t' => 2, 'grid_m' => 1, 'gap' => 16, 'class' => 'f3-partners' ], $pcards ),
	] );

	$E[] = f3_cta();
	$E[] = f3_footer();
	return $E;
}

function f3_work_card( $slug, $title, $tag, $chip, $img, $delay = 0 ) {
	$post = get_page_by_path( $slug, OBJECT, 'post' );
	$url = $post ? get_permalink( $post ) : f3_url( 'work' );
	return f3_c( [ 'gap' => 18, 'link' => $url, 'class' => 'f3-workcard', 'anim' => 'fadeInUp', 'delay' => $delay ], [
		f3_c( [ 'radius' => 8, 'overflow' => 'hidden', 'class' => 'f3-workcard__media' ], [ f3_image( f3_img( $img ), [ 'class' => 'f3-cover', 'h' => 480, 'h_m' => 280 ] ) ] ),
		f3_c( [ 'dir' => 'row', 'dir_m' => 'row', 'justify' => 'space-between', 'align' => 'flex-start', 'gap' => 16 ], [
			f3_c( [ 'gap' => 6 ], [ f3_heading( $title, 'h3', [ 28, 24, 22 ], [ 'ls' => -0.03 ] ), f3_text( $tag, [ 'sizes' => [ 15 ] ] ) ] ),
			f3_chips( [ $chip ] ),
		] ),
	] );
}
