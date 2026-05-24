<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Admin partial: Style Settings page.
 *
 * @since 1.1.0
 */

$style    = get_option( 'sep_style', SE_Portfolio_Style_Settings::get_defaults() );
$defaults = SE_Portfolio_Style_Settings::get_defaults();

// Merge in any missing keys so partial always has a full structure.
$style = array_merge( $defaults, array_intersect_key( $style, $defaults ) );
if ( ! isset( $style['components'] ) || ! is_array( $style['components'] ) ) {
	$style['components'] = $defaults['components'];
}

$global_color_fields = [
	'bg'       => __( 'Background', 'se-portfolio' ),
	'surface'  => __( 'Surface', 'se-portfolio' ),
	'surface2' => __( 'Surface 2', 'se-portfolio' ),
	'border'   => __( 'Border', 'se-portfolio' ),
	'accent'   => __( 'Accent', 'se-portfolio' ),
	'green'    => __( 'Green (secondary accent)', 'se-portfolio' ),
	'text'     => __( 'Text', 'se-portfolio' ),
	'muted'    => __( 'Muted / Dimmed text', 'se-portfolio' ),
	'prompt'   => __( 'Prompt / Highlight', 'se-portfolio' ),
	'warning'  => __( 'Warning / Tag', 'se-portfolio' ),
];

$component_labels = [
	'hero'         => __( 'Hero', 'se-portfolio' ),
	'about'        => __( 'About', 'se-portfolio' ),
	'skills'       => __( 'Skills', 'se-portfolio' ),
	'projects'     => __( 'Projects', 'se-portfolio' ),
	'experience'   => __( 'Experience', 'se-portfolio' ),
	'education'    => __( 'Education', 'se-portfolio' ),
	'certificates' => __( 'Certificates', 'se-portfolio' ),
	'contact'      => __( 'Contact', 'se-portfolio' ),
	'footer'       => __( 'Footer', 'se-portfolio' ),
];

$component_color_labels = [
	'bg'      => __( 'Background', 'se-portfolio' ),
	'surface' => __( 'Surface', 'se-portfolio' ),
	'accent'  => __( 'Accent', 'se-portfolio' ),
	'border'  => __( 'Border', 'se-portfolio' ),
	'text'    => __( 'Text', 'se-portfolio' ),
	'muted'   => __( 'Muted text', 'se-portfolio' ),
];
?>
<div class="wrap sep-style-page">
	<h1><?php esc_html_e( 'Style Settings', 'se-portfolio' ); ?></h1>

	<?php settings_errors( 'sep_style_group' ); ?>

	<!-- ============================================================
	     Preset Bar
	     ============================================================ -->
	<div class="sep-preset-bar">
		<label for="sep-style-preset"><?php esc_html_e( 'Quick Preset:', 'se-portfolio' ); ?></label>
		<select id="sep-style-preset">
			<option value=""><?php esc_html_e( '— choose a preset —', 'se-portfolio' ); ?></option>
			<option value="github-dark"><?php esc_html_e( 'GitHub Dark (default)', 'se-portfolio' ); ?></option>
			<option value="ocean-blue"><?php esc_html_e( 'Ocean Blue', 'se-portfolio' ); ?></option>
			<option value="dracula"><?php esc_html_e( 'Dracula', 'se-portfolio' ); ?></option>
			<option value="nord"><?php esc_html_e( 'Nord', 'se-portfolio' ); ?></option>
			<option value="purple-haze"><?php esc_html_e( 'Purple Haze', 'se-portfolio' ); ?></option>
			<option value="solarized-dark"><?php esc_html_e( 'Solarized Dark', 'se-portfolio' ); ?></option>
		</select>
		<p class="description" style="margin:0;"><?php esc_html_e( 'Selecting a preset updates the fields below. Click Save Changes to apply.', 'se-portfolio' ); ?></p>
	</div>

	<form method="post" action="options.php">
		<?php settings_fields( 'sep_style_group' ); ?>

		<!-- ============================================================
		     1. Global Colors
		     ============================================================ -->
		<div class="sep-style-section">
			<div class="sep-style-section-header">
				<span class="dashicons dashicons-art"></span>
				<?php esc_html_e( 'Global Colors', 'se-portfolio' ); ?>
			</div>
			<div class="sep-style-section-body">
				<?php foreach ( $global_color_fields as $key => $label ) :
					$value = $style[ $key ] ?? $defaults[ $key ];
				?>
				<div class="sep-color-row">
					<label for="sep_style_<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label>
					<input
						type="text"
						id="sep_style_<?php echo esc_attr( $key ); ?>"
						name="sep_style[<?php echo esc_attr( $key ); ?>]"
						value="<?php echo esc_attr( $value ); ?>"
						class="sep-color-picker"
					>
				</div>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- ============================================================
		     2. Typography
		     ============================================================ -->
		<div class="sep-style-section">
			<div class="sep-style-section-header">
				<span class="dashicons dashicons-editor-textcolor"></span>
				<?php esc_html_e( 'Typography', 'se-portfolio' ); ?>
			</div>
			<div class="sep-style-section-body">
				<div class="sep-text-row">
					<label for="sep_style_font_mono"><?php esc_html_e( 'Monospace Font', 'se-portfolio' ); ?></label>
					<input
						type="text"
						id="sep_style_font_mono"
						name="sep_style[font_mono]"
						value="<?php echo esc_attr( $style['font_mono'] ?? $defaults['font_mono'] ); ?>"
						class="regular-text"
					>
				</div>
				<div class="sep-text-row">
					<label for="sep_style_font_body"><?php esc_html_e( 'Body Font', 'se-portfolio' ); ?></label>
					<input
						type="text"
						id="sep_style_font_body"
						name="sep_style[font_body]"
						value="<?php echo esc_attr( $style['font_body'] ?? $defaults['font_body'] ); ?>"
						class="regular-text"
					>
				</div>
				<p class="description" style="margin-top:6px;">
					<?php esc_html_e( 'Enter a CSS font-family value, e.g. ', 'se-portfolio' ); ?>
					<code>'JetBrains Mono', monospace</code>
				</p>
			</div>
		</div>

		<!-- ============================================================
		     3. Layout
		     ============================================================ -->
		<div class="sep-style-section">
			<div class="sep-style-section-header">
				<span class="dashicons dashicons-editor-expand"></span>
				<?php esc_html_e( 'Layout', 'se-portfolio' ); ?>
			</div>
			<div class="sep-style-section-body">
				<div class="sep-text-row">
					<label for="sep_style_radius"><?php esc_html_e( 'Border Radius', 'se-portfolio' ); ?></label>
					<input
						type="text"
						id="sep_style_radius"
						name="sep_style[radius]"
						value="<?php echo esc_attr( $style['radius'] ?? $defaults['radius'] ); ?>"
						class="sep-radius-input"
						placeholder="3px"
					>
				</div>
				<p class="description" style="margin-top:6px;">
					<?php esc_html_e( 'CSS length value applied to card corners, badges, etc. (e.g. 3px, 0.5rem, 0)', 'se-portfolio' ); ?>
				</p>
			</div>
		</div>

		<!-- ============================================================
		     4. Typography Sizes
		     ============================================================ -->
		<div class="sep-style-section">
			<div class="sep-style-section-header">
				<span class="dashicons dashicons-editor-textcolor"></span>
				<?php esc_html_e( 'Typography Sizes', 'se-portfolio' ); ?>
			</div>
			<div class="sep-style-section-body">
				<div class="sep-text-row">
					<label for="sep_style_base_size"><?php esc_html_e( 'Base Font Size', 'se-portfolio' ); ?></label>
					<input
						type="text"
						id="sep_style_base_size"
						name="sep_style[base_size]"
						value="<?php echo esc_attr( $style['base_size'] ?? $defaults['base_size'] ); ?>"
						class="regular-text"
						placeholder="15px"
					>
				</div>
				<div class="sep-text-row">
					<label for="sep_style_hero_name_size"><?php esc_html_e( 'Hero Name Size', 'se-portfolio' ); ?></label>
					<input
						type="text"
						id="sep_style_hero_name_size"
						name="sep_style[hero_name_size]"
						value="<?php echo esc_attr( $style['hero_name_size'] ?? $defaults['hero_name_size'] ); ?>"
						class="regular-text"
						placeholder="clamp(1.6rem, 4vw, 2.4rem)"
					>
				</div>
				<p class="description" style="margin-top:6px;">
					<?php esc_html_e( 'Base font size affects all rem/em values. Accepts px, rem, or em. Hero name size accepts any CSS font-size value (including clamp).', 'se-portfolio' ); ?>
				</p>
			</div>
		</div>

		<!-- ============================================================
		     5. Spacing & Transitions
		     ============================================================ -->
		<div class="sep-style-section">
			<div class="sep-style-section-header">
				<span class="dashicons dashicons-leftright"></span>
				<?php esc_html_e( 'Spacing & Transitions', 'se-portfolio' ); ?>
			</div>
			<div class="sep-style-section-body">
				<div class="sep-text-row">
					<label for="sep_style_section_py"><?php esc_html_e( 'Section Padding (top & bottom)', 'se-portfolio' ); ?></label>
					<input
						type="text"
						id="sep_style_section_py"
						name="sep_style[section_py]"
						value="<?php echo esc_attr( $style['section_py'] ?? $defaults['section_py'] ); ?>"
						class="sep-radius-input"
						placeholder="80px"
					>
				</div>
				<div class="sep-text-row">
					<label for="sep_style_card_pad"><?php esc_html_e( 'Card Body Padding', 'se-portfolio' ); ?></label>
					<input
						type="text"
						id="sep_style_card_pad"
						name="sep_style[card_pad]"
						value="<?php echo esc_attr( $style['card_pad'] ?? $defaults['card_pad'] ); ?>"
						class="sep-radius-input"
						placeholder="16px"
					>
				</div>
				<div class="sep-text-row">
					<label for="sep_style_hero_pt"><?php esc_html_e( 'Hero Top Padding', 'se-portfolio' ); ?></label>
					<input
						type="text"
						id="sep_style_hero_pt"
						name="sep_style[hero_pt]"
						value="<?php echo esc_attr( $style['hero_pt'] ?? $defaults['hero_pt'] ); ?>"
						class="sep-radius-input"
						placeholder="160px"
					>
				</div>
				<div class="sep-text-row">
					<label for="sep_style_container_max"><?php esc_html_e( 'Container Max Width', 'se-portfolio' ); ?></label>
					<input
						type="text"
						id="sep_style_container_max"
						name="sep_style[container_max]"
						value="<?php echo esc_attr( $style['container_max'] ?? $defaults['container_max'] ); ?>"
						class="sep-radius-input"
						placeholder="1100px"
					>
				</div>
				<div class="sep-text-row">
					<label for="sep_style_transition"><?php esc_html_e( 'Hover Transition Speed', 'se-portfolio' ); ?></label>
					<input
						type="text"
						id="sep_style_transition"
						name="sep_style[transition]"
						value="<?php echo esc_attr( $style['transition'] ?? $defaults['transition'] ); ?>"
						class="sep-radius-input"
						placeholder="0.15s"
					>
				</div>
				<p class="description" style="margin-top:6px;">
					<?php esc_html_e( 'All values accept standard CSS length units (px, rem, em, %). Transition speed accepts time values (0.15s, 0.3s, 0 to disable).', 'se-portfolio' ); ?>
				</p>
			</div>
		</div>

		<!-- ============================================================
		     6. Effects & Card Style
		     ============================================================ -->
		<div class="sep-style-section">
			<div class="sep-style-section-header">
				<span class="dashicons dashicons-visibility"></span>
				<?php esc_html_e( 'Effects & Card Style', 'se-portfolio' ); ?>
			</div>
			<div class="sep-style-section-body">

				<div class="sep-text-row" style="align-items:flex-start;padding-top:12px;">
					<span style="font-size:13px;font-weight:500;color:#1d2327;"><?php esc_html_e( 'Visual Effects', 'se-portfolio' ); ?></span>
					<div style="display:flex;flex-direction:column;gap:10px;">
						<label style="display:flex;align-items:center;gap:8px;font-weight:normal;">
							<input
								type="checkbox"
								name="sep_style[show_glows]"
								value="1"
								<?php checked( 1, (int) ( $style['show_glows'] ?? $defaults['show_glows'] ) ); ?>
							>
							<?php esc_html_e( 'Glow / Shadow Effects (buttons, cards, stats)', 'se-portfolio' ); ?>
						</label>
						<label style="display:flex;align-items:center;gap:8px;font-weight:normal;">
							<input
								type="checkbox"
								name="sep_style[show_scanlines]"
								value="1"
								<?php checked( 1, (int) ( $style['show_scanlines'] ?? $defaults['show_scanlines'] ) ); ?>
							>
							<?php esc_html_e( 'Scanline Overlay (hero section texture)', 'se-portfolio' ); ?>
						</label>
						<label style="display:flex;align-items:center;gap:8px;font-weight:normal;">
							<input
								type="checkbox"
								name="sep_style[show_animations]"
								value="1"
								<?php checked( 1, (int) ( $style['show_animations'] ?? $defaults['show_animations'] ) ); ?>
							>
							<?php esc_html_e( 'All Animations (availability pulse, etc.)', 'se-portfolio' ); ?>
						</label>
						<label style="display:flex;align-items:center;gap:8px;font-weight:normal;">
							<input
								type="checkbox"
								name="sep_style[show_blink]"
								value="1"
								<?php checked( 1, (int) ( $style['show_blink'] ?? $defaults['show_blink'] ) ); ?>
							>
							<?php esc_html_e( 'Cursor Blink (hero job title blinking cursor)', 'se-portfolio' ); ?>
						</label>
					</div>
				</div>

				<div class="sep-text-row" style="align-items:flex-start;padding-top:12px;border-top:1px solid #f0f0f1;margin-top:8px;">
					<span style="font-size:13px;font-weight:500;color:#1d2327;"><?php esc_html_e( 'Card Style', 'se-portfolio' ); ?></span>
					<div style="display:flex;flex-direction:column;gap:10px;">
						<label style="display:flex;align-items:center;gap:8px;font-weight:normal;">
							<input
								type="radio"
								name="sep_style[card_style]"
								value="terminal"
								<?php checked( 'terminal', $style['card_style'] ?? $defaults['card_style'] ); ?>
							>
							<span>
								<?php esc_html_e( 'Terminal', 'se-portfolio' ); ?>
								<span class="description">&mdash; <?php esc_html_e( 'card has colored dots chrome header (default)', 'se-portfolio' ); ?></span>
							</span>
						</label>
						<label style="display:flex;align-items:center;gap:8px;font-weight:normal;">
							<input
								type="radio"
								name="sep_style[card_style]"
								value="flat"
								<?php checked( 'flat', $style['card_style'] ?? $defaults['card_style'] ); ?>
							>
							<span>
								<?php esc_html_e( 'Flat', 'se-portfolio' ); ?>
								<span class="description">&mdash; <?php esc_html_e( 'clean card with no chrome header bar', 'se-portfolio' ); ?></span>
							</span>
						</label>
					</div>
				</div>

			</div>
		</div>

		<!-- ============================================================
		     7. Custom CSS
		     ============================================================ -->
		<div class="sep-style-section">
			<div class="sep-style-section-header">
				<span class="dashicons dashicons-editor-code"></span>
				<?php esc_html_e( 'Custom CSS', 'se-portfolio' ); ?>
			</div>
			<div class="sep-style-section-body">

				<p style="margin:0 0 12px;"><?php esc_html_e( 'Write any CSS here and it will be injected after all plugin styles — your rules always win. Scope to .sep-portfolio so nothing leaks into the rest of the page.', 'se-portfolio' ); ?></p>

				<!-- CSS Reference (collapsible, no JS required) -->
				<details class="sep-css-reference">
					<summary><?php esc_html_e( 'CSS Reference — Variables, Selectors & Examples', 'se-portfolio' ); ?></summary>
					<div class="sep-css-reference-body">

						<!-- ---- Available CSS Variables ---- -->
						<p class="sep-css-ref-group"><?php esc_html_e( 'Available CSS Variables', 'se-portfolio' ); ?></p>
						<p style="margin:0 0 8px;color:#50575e;"><?php esc_html_e( 'Use these inside any selector to reference or override the current values.', 'se-portfolio' ); ?></p>

						<?php
						$var_groups = [
							__( 'Colors', 'se-portfolio' ) => [
								'--sep-bg'       => $style['bg']       ?? $defaults['bg'],
								'--sep-surface'  => $style['surface']  ?? $defaults['surface'],
								'--sep-surface2' => $style['surface2'] ?? $defaults['surface2'],
								'--sep-border'   => $style['border']   ?? $defaults['border'],
								'--sep-accent'   => $style['accent']   ?? $defaults['accent'],
								'--sep-green'    => $style['green']    ?? $defaults['green'],
								'--sep-text'     => $style['text']     ?? $defaults['text'],
								'--sep-muted'    => $style['muted']    ?? $defaults['muted'],
								'--sep-prompt'   => $style['prompt']   ?? $defaults['prompt'],
								'--sep-warning'  => $style['warning']  ?? $defaults['warning'],
							],
							__( 'Typography & Fonts', 'se-portfolio' ) => [
								'--sep-font-mono'      => $style['font_mono']      ?? $defaults['font_mono'],
								'--sep-font-body'      => $style['font_body']      ?? $defaults['font_body'],
								'--sep-base-size'      => $style['base_size']      ?? $defaults['base_size'],
								'--sep-hero-name-size' => $style['hero_name_size'] ?? $defaults['hero_name_size'],
							],
							__( 'Spacing & Layout', 'se-portfolio' ) => [
								'--sep-section-py'    => $style['section_py']    ?? $defaults['section_py'],
								'--sep-card-pad'      => $style['card_pad']      ?? $defaults['card_pad'],
								'--sep-hero-pt'       => $style['hero_pt']       ?? $defaults['hero_pt'],
								'--sep-container-max' => $style['container_max'] ?? $defaults['container_max'],
							],
							__( 'Borders & Transitions', 'se-portfolio' ) => [
								'--sep-radius'     => $style['radius']     ?? $defaults['radius'],
								'--sep-transition' => $style['transition'] ?? $defaults['transition'],
							],
						];
						foreach ( $var_groups as $group_label => $vars ) :
						?>
						<p style="margin:10px 0 4px;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.06em;color:#50575e;"><?php echo esc_html( $group_label ); ?></p>
						<table class="sep-css-ref-table">
							<thead>
								<tr>
									<th><?php esc_html_e( 'Variable', 'se-portfolio' ); ?></th>
									<th><?php esc_html_e( 'Current Value', 'se-portfolio' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $vars as $var_name => $var_value ) : ?>
								<tr>
									<td><code><?php echo esc_html( $var_name ); ?></code></td>
									<td><code><?php echo esc_html( $var_value ); ?></code></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<?php endforeach; ?>

						<!-- ---- Section Selectors ---- -->
						<p class="sep-css-ref-group"><?php esc_html_e( 'Section Selectors', 'se-portfolio' ); ?></p>
						<table class="sep-css-ref-table">
							<thead>
								<tr>
									<th><?php esc_html_e( 'Section', 'se-portfolio' ); ?></th>
									<th><?php esc_html_e( 'CSS Selector', 'se-portfolio' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$selectors = [
									__( 'Whole portfolio', 'se-portfolio' )  => '.sep-portfolio',
									__( 'Hero', 'se-portfolio' )             => '[data-sep-section="hero"]',
									__( 'About', 'se-portfolio' )           => '[data-sep-section="about"]',
									__( 'Skills', 'se-portfolio' )          => '[data-sep-section="skills"]',
									__( 'Projects', 'se-portfolio' )        => '[data-sep-section="projects"]',
									__( 'Experience', 'se-portfolio' )      => '[data-sep-section="experience"]',
									__( 'Education', 'se-portfolio' )       => '[data-sep-section="education"]',
									__( 'Certificates', 'se-portfolio' )    => '[data-sep-section="certificates"]',
									__( 'Contact', 'se-portfolio' )         => '[data-sep-section="contact"]',
									__( 'Footer', 'se-portfolio' )          => '.sep-footer',
									__( 'Any card', 'se-portfolio' )        => '.sep-portfolio .sep-card',
									__( 'Top navigation', 'se-portfolio' )  => '.sep-portfolio .sep-topnav',
									__( 'Section headings', 'se-portfolio' )=> '.sep-portfolio .sep-section-heading',
									__( 'Timeline items', 'se-portfolio' )  => '.sep-portfolio .sep-timeline-item',
								];
								foreach ( $selectors as $section => $selector ) :
								?>
								<tr>
									<td><?php echo esc_html( $section ); ?></td>
									<td><code><?php echo esc_html( $selector ); ?></code></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>

						<!-- ---- Example Snippets ---- -->
						<p class="sep-css-ref-group"><?php esc_html_e( 'Example Snippets', 'se-portfolio' ); ?></p>

						<div class="sep-css-snippet">
							<p style="margin:0 0 4px;color:#50575e;"><?php esc_html_e( 'Change the hero section background independently:', 'se-portfolio' ); ?></p>
							<pre>[data-sep-section="hero"] {
    --sep-bg: #0d1b2a;
}</pre>
						</div>

						<div class="sep-css-snippet">
							<p style="margin:0 0 4px;color:#50575e;"><?php esc_html_e( 'Make the hero name larger and a different color:', 'se-portfolio' ); ?></p>
							<pre>.sep-portfolio .sep-hero-name {
    font-size: 3rem;
    color: var(--sep-accent);
}</pre>
						</div>

						<div class="sep-css-snippet">
							<p style="margin:0 0 4px;color:#50575e;"><?php esc_html_e( 'Add a stronger glow on card hover:', 'se-portfolio' ); ?></p>
							<pre>.sep-portfolio .sep-card:hover {
    box-shadow: 0 0 24px rgba(0, 210, 106, 0.35);
    border-color: var(--sep-accent);
}</pre>
						</div>

						<div class="sep-css-snippet">
							<p style="margin:0 0 4px;color:#50575e;"><?php esc_html_e( 'Hide the availability badge:', 'se-portfolio' ); ?></p>
							<pre>.sep-portfolio .sep-badge-available {
    display: none;
}</pre>
						</div>

						<div class="sep-css-snippet">
							<p style="margin:0 0 4px;color:#50575e;"><?php esc_html_e( 'Use a serif font only for section headings:', 'se-portfolio' ); ?></p>
							<pre>.sep-portfolio .sep-section-heading {
    font-family: 'Georgia', serif;
    letter-spacing: 0;
}</pre>
						</div>

					</div><!-- /.sep-css-reference-body -->
				</details>

				<textarea
					id="sep_style_custom_css"
					name="sep_style[custom_css]"
					rows="12"
					class="large-text code"
					placeholder="/* Add your custom CSS here. All rules are injected after the plugin's styles, so they take priority. */"
					spellcheck="false"
				><?php echo esc_textarea( $style['custom_css'] ?? '' ); ?></textarea>
				<p class="description" style="margin-top:6px;">
					<?php esc_html_e( 'CSS injected after all other portfolio styles. Scope to .sep-portfolio to avoid affecting the rest of the page.', 'se-portfolio' ); ?>
				</p>
			</div>
		</div>

		<!-- ============================================================
		     8. Per-Component Overrides
		     ============================================================ -->
		<div class="sep-style-section">
			<div class="sep-style-section-header">
				<span class="dashicons dashicons-admin-customizer"></span>
				<?php esc_html_e( 'Component Overrides', 'se-portfolio' ); ?>
			</div>
			<div class="sep-style-section-body">
				<p class="description" style="margin-bottom:12px;">
					<?php esc_html_e( 'Override specific colors per section. Leave a field empty to inherit the global value.', 'se-portfolio' ); ?>
				</p>

				<?php foreach ( $component_labels as $component => $comp_label ) :
					$comp_values = isset( $style['components'][ $component ] ) && is_array( $style['components'][ $component ] )
						? $style['components'][ $component ]
						: $defaults['components'][ $component ];

					$has_any_override = false;
					foreach ( $comp_values as $v ) {
						if ( '' !== $v ) {
							$has_any_override = true;
							break;
						}
					}
				?>
				<div class="sep-component-accordion">
					<button type="button" class="sep-component-toggle <?php echo $has_any_override ? 'is-open' : ''; ?>">
						<?php echo esc_html( $comp_label ); ?>
						<?php if ( $has_any_override ) : ?>
							<span style="font-weight:normal;font-size:11px;color:#0073aa;margin-left:6px;"><?php esc_html_e( '(customised)', 'se-portfolio' ); ?></span>
						<?php endif; ?>
						<span class="sep-toggle-icon dashicons dashicons-arrow-down-alt2"></span>
					</button>
					<div class="sep-component-fields" <?php echo $has_any_override ? 'style="display:block;"' : ''; ?>>
						<p class="sep-component-hint">
							<?php esc_html_e( 'Override for this section only. Empty = use the Global Colors above.', 'se-portfolio' ); ?>
						</p>
						<?php foreach ( $component_color_labels as $color_key => $color_label ) :
							$field_id  = 'sep_style_comp_' . $component . '_' . $color_key;
							$field_val = $comp_values[ $color_key ] ?? '';
						?>
						<div class="sep-color-row">
							<label for="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_html( $color_label ); ?></label>
							<input
								type="text"
								id="<?php echo esc_attr( $field_id ); ?>"
								name="sep_style[components][<?php echo esc_attr( $component ); ?>][<?php echo esc_attr( $color_key ); ?>]"
								value="<?php echo esc_attr( $field_val ); ?>"
								class="sep-color-picker sep-component-color-picker"
								data-component="<?php echo esc_attr( $component ); ?>"
								data-key="<?php echo esc_attr( $color_key ); ?>"
							>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- ============================================================
		     Action Bar
		     ============================================================ -->
		<div class="sep-style-actions">
			<?php submit_button( __( 'Save Changes', 'se-portfolio' ), 'primary', 'submit', false ); ?>
			<button
				type="button"
				id="sep-reset-defaults"
				class="button button-secondary"
				data-label="<?php esc_attr_e( 'Reset to Defaults', 'se-portfolio' ); ?>"
			><?php esc_html_e( 'Reset to Defaults', 'se-portfolio' ); ?></button>
			<span id="sep-reset-notice" role="status"></span>
		</div>

	</form>
</div>
