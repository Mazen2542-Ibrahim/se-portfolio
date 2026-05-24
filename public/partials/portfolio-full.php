<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
 * Variables passed from SE_Portfolio_Public::shortcode_portfolio():
 *   $about               — array from get_option( 'sep_about' )
 *   $projects_query      — WP_Query (page 1)
 *   $projects_per_page   — int
 *   $projects_max_pages  — int
 *   $skills_query        — WP_Query
 *   $experience_query    — WP_Query
 *   $education_query     — WP_Query
 *   $certs_query         — WP_Query (page 1)
 *   $certs_per_page      — int
 *   $certs_max_pages     — int
 */

$photo_id  = isset( $about['photo_id'] ) ? (int) $about['photo_id'] : 0;
$photo_url = $photo_id ? wp_get_attachment_image_url( $photo_id, 'medium' ) : '';

// Build grouped skills for the skills section.
$grouped_skills = [];
if ( $skills_query->have_posts() ) {
	while ( $skills_query->have_posts() ) {
		$skills_query->the_post();
		$cat = get_post_meta( get_the_ID(), '_sep_category', true ) ?: 'tools';
		$grouped_skills[ $cat ][] = get_the_ID();
	}
}

$category_labels = [
	'frontend'    => __( 'Frontend', 'se-portfolio' ),
	'backend'     => __( 'Backend', 'se-portfolio' ),
	'devops'      => __( 'DevOps', 'se-portfolio' ),
	'database'    => __( 'Database', 'se-portfolio' ),
	'tools'       => __( 'Tools', 'se-portfolio' ),
	'soft-skills' => __( 'Soft Skills', 'se-portfolio' ),
];

$show_contact = ! empty( $about['show_contact'] );
$section_ids  = array_merge(
	[ 'hero', 'about', 'skills', 'experience', 'projects', 'education', 'certificates' ],
	$show_contact ? [ 'contact' ] : []
);
?>

<div class="sep-portfolio">

	<!-- ============================================================
	     Top Navigation Bar
	     ============================================================ -->
	<nav class="sep-topnav" aria-label="<?php esc_attr_e( 'Portfolio navigation', 'se-portfolio' ); ?>">
		<div class="sep-topnav-inner">
			<span class="sep-topnav-path" aria-hidden="true"><span class="sep-path-tilde">~</span>/portfolio</span>
			<a class="sep-topnav-logo" href="#sep-hero">
				<span class="sep-logo-bracket">&lt;</span><span class="sep-logo-name"><?php echo esc_html( $about['name'] ?? 'Portfolio' ); ?></span><span class="sep-logo-bracket"> /&gt;</span>
			</a>

			<button class="sep-topnav-toggle" aria-label="<?php esc_attr_e( 'Toggle navigation', 'se-portfolio' ); ?>" aria-expanded="false">&#9776;</button>

			<ul class="sep-topnav-links">
				<li><a href="#sep-about"><?php esc_html_e( 'About', 'se-portfolio' ); ?></a></li>
				<li><a href="#sep-skills"><?php esc_html_e( 'Skills', 'se-portfolio' ); ?></a></li>
				<li><a href="#sep-experience"><?php esc_html_e( 'Experience', 'se-portfolio' ); ?></a></li>
				<li><a href="#sep-projects"><?php esc_html_e( 'Projects', 'se-portfolio' ); ?></a></li>
				<li><a href="#sep-education"><?php esc_html_e( 'Education', 'se-portfolio' ); ?></a></li>
				<li><a href="#sep-certificates"><?php esc_html_e( 'Certificates', 'se-portfolio' ); ?></a></li>
				<?php if ( $show_contact ) : ?>
					<li><a href="#sep-contact"><?php esc_html_e( 'Contact', 'se-portfolio' ); ?></a></li>
				<?php endif; ?>
			</ul>

			<?php if ( ! empty( $about['show_hire_me'] ) && ! empty( $about['email'] ) ) : ?>
				<a class="sep-topnav-cta" href="<?php echo $show_contact ? '#sep-contact' : 'mailto:' . esc_attr( $about['email'] ); ?>">
					<?php esc_html_e( 'Hire Me', 'se-portfolio' ); ?>
				</a>
			<?php endif; ?>
		</div>
	</nav>

	<!-- ============================================================
	     Section Dot Nav (desktop right side)
	     ============================================================ -->
	<nav class="sep-sticky-nav" aria-label="<?php esc_attr_e( 'Section navigation', 'se-portfolio' ); ?>">
		<ul>
			<?php foreach ( $section_ids as $sid ) : ?>
				<li>
					<span class="sep-nav-dot" data-section="<?php echo esc_attr( $sid ); ?>" title="<?php echo esc_attr( ucfirst( $sid ) ); ?>"></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>

	<!-- ============================================================
	     1. Hero
	     ============================================================ -->
	<header class="sep-hero" id="sep-hero" data-sep-section="hero">
		<div class="sep-term-chrome" aria-hidden="true">
			<div class="sep-term-dots">
				<span class="sep-term-dot sep-term-dot--close"></span>
				<span class="sep-term-dot sep-term-dot--min"></span>
				<span class="sep-term-dot sep-term-dot--max"></span>
			</div>
			<span class="sep-term-title">visitor@portfolio: ~/about — bash</span>
		</div>
		<div class="sep-term-body">
			<div class="sep-term-prompt-line">
				<span class="sep-term-ps1">visitor@portfolio:~$</span>
				<span class="sep-term-cmd"> whoami</span>
			</div>
			<?php if ( ! empty( $about['available'] ) ) : ?>
				<span class="sep-badge-available">
					<?php esc_html_e( 'Available for opportunities', 'se-portfolio' ); ?>
				</span>
			<?php endif; ?>
			<div class="sep-hero-inner<?php echo $photo_url ? '' : ' sep-no-photo'; ?>">
			<?php if ( $photo_url ) : ?>
				<img
					class="sep-hero-photo"
					src="<?php echo esc_url( $photo_url ); ?>"
					alt="<?php echo esc_attr( $about['name'] ?? '' ); ?>"
				>
			<?php endif; ?>

			<div class="sep-hero-content">
				<h1 class="sep-hero-name"><?php echo esc_html( $about['name'] ?? '' ); ?></h1>
				<p class="sep-hero-title"><?php echo esc_html( $about['job_title'] ?? '' ); ?></p>

				<?php if ( ! empty( $about['short_bio'] ) ) : ?>
					<div class="sep-hero-bio">
						<?php echo wp_kses_post( $about['short_bio'] ); ?>
					</div>
				<?php endif; ?>

				<div class="sep-hero-actions">
					<?php if ( ! empty( $about['show_cv_btn'] ) && ! empty( $about['cv_url'] ) ) : ?>
						<a class="sep-btn sep-btn-primary" href="<?php echo esc_url( $about['cv_url'] ); ?>" target="_blank" rel="noopener noreferrer">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15" aria-hidden="true"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
							<?php esc_html_e( 'Download CV', 'se-portfolio' ); ?>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $about['email'] ) ) : ?>
						<a class="sep-btn sep-btn-outline" href="<?php echo $show_contact ? '#sep-contact' : 'mailto:' . esc_attr( $about['email'] ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
							<?php esc_html_e( 'Contact Me', 'se-portfolio' ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div><!-- /.sep-hero-inner -->
		</div><!-- /.sep-term-body -->
	</header>

	<!-- ============================================================
	     2. About
	     ============================================================ -->
	<section class="sep-section" id="sep-about" data-sep-section="about">
		<div class="sep-section-inner">
			<h2 class="sep-section-heading"><?php esc_html_e( 'About Me', 'se-portfolio' ); ?></h2>

			<div class="sep-about-inner<?php echo $photo_url ? '' : ' sep-no-photo'; ?>">
				<?php if ( $photo_url ) : ?>
					<div>
						<img
							class="sep-about-photo"
							src="<?php echo esc_url( $photo_url ); ?>"
							alt="<?php echo esc_attr( $about['name'] ?? '' ); ?>"
						>
					</div>
				<?php endif; ?>

				<div>
					<?php if ( ! empty( $about['long_bio'] ) ) : ?>
						<div class="sep-about-bio-block">
							<div class="sep-term-prompt-line">
								<span class="sep-term-ps1">visitor@portfolio:~$</span>
								<span class="sep-term-cmd"> cat profile.md</span>
							</div>
							<div class="sep-about-bio"><?php echo wp_kses_post( $about['long_bio'] ); ?></div>
						</div>
					<?php endif; ?>

					<?php
					$years_exp     = isset( $about['years_exp'] ) ? (int) $about['years_exp'] : 0;
					$project_count = wp_count_posts( 'sep_project' )->publish ?? 0;
					$skill_count   = wp_count_posts( 'sep_skill' )->publish ?? 0;
					?>
					<div class="sep-stats">
						<?php if ( $years_exp ) : ?>
							<div class="sep-stat">
								<span class="sep-stat-number"><?php echo esc_html( $years_exp ); ?>+</span>
								<span class="sep-stat-label"><?php esc_html_e( 'Years Experience', 'se-portfolio' ); ?></span>
							</div>
						<?php endif; ?>
						<?php if ( $project_count ) : ?>
							<div class="sep-stat">
								<span class="sep-stat-number"><?php echo esc_html( $project_count ); ?></span>
								<span class="sep-stat-label"><?php esc_html_e( 'Projects', 'se-portfolio' ); ?></span>
							</div>
						<?php endif; ?>
						<?php if ( $skill_count ) : ?>
							<div class="sep-stat">
								<span class="sep-stat-number"><?php echo esc_html( $skill_count ); ?></span>
								<span class="sep-stat-label"><?php esc_html_e( 'Skills', 'se-portfolio' ); ?></span>
							</div>
						<?php endif; ?>
					</div>

					<div class="sep-term-prompt-line">
						<span class="sep-term-ps1">visitor@portfolio:~$</span>
						<span class="sep-term-cmd"> cat links.txt</span>
					</div>
					<div class="sep-about-links">
						<?php if ( ! empty( $about['github_url'] ) ) : ?>
							<a class="sep-btn sep-btn-outline" href="<?php echo esc_url( $about['github_url'] ); ?>" target="_blank" rel="noopener noreferrer"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="15" height="15" aria-hidden="true"><path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg> GitHub</a>
						<?php endif; ?>
						<?php if ( ! empty( $about['linkedin_url'] ) ) : ?>
							<a class="sep-btn sep-btn-outline" href="<?php echo esc_url( $about['linkedin_url'] ); ?>" target="_blank" rel="noopener noreferrer"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="15" height="15" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg> LinkedIn</a>
						<?php endif; ?>
						<?php if ( ! empty( $about['show_cv_btn'] ) && ! empty( $about['cv_url'] ) ) : ?>
							<a class="sep-btn sep-btn-primary" href="<?php echo esc_url( $about['cv_url'] ); ?>" target="_blank" rel="noopener noreferrer">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15" aria-hidden="true"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
								<?php esc_html_e( 'Download CV', 'se-portfolio' ); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- ============================================================
	     3. Skills — Chip/Pill Design (no bars)
	     ============================================================ -->
	<section class="sep-section sep-section--surface" id="sep-skills" data-sep-section="skills">
		<div class="sep-section-inner">
			<h2 class="sep-section-heading"><?php esc_html_e( 'Skills', 'se-portfolio' ); ?></h2>

			<?php if ( $grouped_skills ) : ?>
				<div class="sep-skills-grid">
					<?php foreach ( $grouped_skills as $cat => $post_ids ) : ?>
						<div class="sep-skills-group">
							<div class="sep-skills-group-label">
								<?php echo esc_html( $category_labels[ $cat ] ?? ucfirst( $cat ) ); ?>
							</div>
							<div class="sep-skills-tags">
								<?php foreach ( $post_ids as $pid ) : ?>
									<?php
									$proficiency = min( 100, max( 0, (int) get_post_meta( $pid, '_sep_proficiency', true ) ) );
									$level       = $proficiency >= 80 ? 'high' : ( $proficiency >= 60 ? 'mid' : 'low' );
									?>
									<span class="sep-skill-tag sep-skill-tag--<?php echo esc_attr( $level ); ?>">
										<?php echo esc_html( get_the_title( $pid ) ); ?>
									</span>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<p class="sep-empty"><?php esc_html_e( 'No skills found.', 'se-portfolio' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<!-- ============================================================
	     4. Experience
	     ============================================================ -->
	<section class="sep-section" id="sep-experience" data-sep-section="experience">
		<div class="sep-section-inner">
			<h2 class="sep-section-heading"><?php esc_html_e( 'Experience', 'se-portfolio' ); ?></h2>

			<?php if ( $experience_query->have_posts() ) : ?>
				<div class="sep-timeline">
					<?php while ( $experience_query->have_posts() ) : $experience_query->the_post(); ?>
						<?php
						$eid        = get_the_ID();
						$company    = get_post_meta( $eid, '_sep_company', true );
						$co_url     = get_post_meta( $eid, '_sep_company_url', true );
						$emp_type   = get_post_meta( $eid, '_sep_employment_type', true );
						$e_start    = get_post_meta( $eid, '_sep_start_date', true );
						$e_end      = get_post_meta( $eid, '_sep_end_date', true );
						$e_present  = (bool) get_post_meta( $eid, '_sep_is_present', true );
						$e_location = get_post_meta( $eid, '_sep_location', true );
						$e_tech     = get_post_meta( $eid, '_sep_technologies', true );
						?>
						<div class="sep-timeline-item">
							<h3 class="sep-timeline-title"><?php the_title(); ?></h3>
							<p class="sep-timeline-subtitle">
								<?php if ( $co_url ) : ?>
									<a href="<?php echo esc_url( $co_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $company ); ?></a>
								<?php else : ?>
									<?php echo esc_html( $company ); ?>
								<?php endif; ?>
								<?php if ( $emp_type ) : ?>
									&mdash; <span class="sep-status sep-status-completed"><?php echo esc_html( ucfirst( str_replace( '-', ' ', $emp_type ) ) ); ?></span>
								<?php endif; ?>
							</p>
							<p class="sep-timeline-date">
								<?php
								if ( $e_start ) {
									echo esc_html( date_i18n( 'M Y', strtotime( $e_start ) ) ) . ' &mdash; ';
									echo $e_present ? esc_html__( 'Present', 'se-portfolio' ) : esc_html( $e_end ? date_i18n( 'M Y', strtotime( $e_end ) ) : '' );
								}
								if ( $e_location ) {
									echo ' &nbsp;&bull;&nbsp; ' . esc_html( $e_location );
								}
								?>
							</p>
							<?php if ( get_the_content() ) : ?>
								<div class="sep-timeline-body"><?php the_content(); ?></div>
							<?php endif; ?>
							<?php if ( $e_tech ) : ?>
								<div class="sep-tags" style="margin-top:12px;">
									<?php foreach ( array_filter( array_map( 'trim', explode( ',', $e_tech ) ) ) as $t ) : ?>
										<span class="sep-tag"><?php echo esc_html( $t ); ?></span>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endwhile; ?>
				</div>
			<?php else : ?>
				<p class="sep-empty"><?php esc_html_e( 'No experience entries found.', 'se-portfolio' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<!-- ============================================================
	     5. Projects
	     ============================================================ -->
	<section class="sep-section sep-section--surface" id="sep-projects" data-sep-section="projects">
		<div class="sep-section-inner">
			<h2 class="sep-section-heading"><?php esc_html_e( 'Projects', 'se-portfolio' ); ?></h2>

			<?php if ( $projects_query->have_posts() ) : ?>
				<div class="sep-filter-tabs">
					<button class="sep-filter-tab is-active" data-filter="all"><?php esc_html_e( 'All', 'se-portfolio' ); ?></button>
					<button class="sep-filter-tab" data-filter="completed"><?php esc_html_e( 'Completed', 'se-portfolio' ); ?></button>
					<button class="sep-filter-tab" data-filter="in-progress"><?php esc_html_e( 'In Progress', 'se-portfolio' ); ?></button>
					<button class="sep-filter-tab" data-filter="archived"><?php esc_html_e( 'Archived', 'se-portfolio' ); ?></button>
				</div>

				<div class="sep-cards-grid"
					data-section="projects"
					data-page="1"
					data-per-page="<?php echo esc_attr( $projects_per_page ); ?>"
					data-max-pages="<?php echo esc_attr( $projects_max_pages ); ?>">
					<?php while ( $projects_query->have_posts() ) : $projects_query->the_post(); ?>
						<?php include __DIR__ . '/card-project.php'; ?>
					<?php endwhile; ?>
				</div>

				<?php if ( $projects_max_pages > 1 ) : ?>
					<div class="sep-load-more-wrap">
						<button class="sep-btn sep-btn-outline sep-load-more" data-section="projects">
							<?php esc_html_e( 'Load More', 'se-portfolio' ); ?>
						</button>
					</div>
				<?php endif; ?>

			<?php else : ?>
				<p class="sep-empty"><?php esc_html_e( 'No projects found.', 'se-portfolio' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<!-- ============================================================
	     6. Education
	     ============================================================ -->
	<section class="sep-section" id="sep-education" data-sep-section="education">
		<div class="sep-section-inner">
			<h2 class="sep-section-heading"><?php esc_html_e( 'Education', 'se-portfolio' ); ?></h2>

			<?php if ( $education_query->have_posts() ) : ?>
				<div class="sep-timeline">
					<?php while ( $education_query->have_posts() ) : $education_query->the_post(); ?>
						<?php
						$edid     = get_the_ID();
						$degree   = get_post_meta( $edid, '_sep_degree', true );
						$field    = get_post_meta( $edid, '_sep_field', true );
						$inst     = get_post_meta( $edid, '_sep_institution', true );
						$inst_url = get_post_meta( $edid, '_sep_institution_url', true );
						$s_year   = (int) get_post_meta( $edid, '_sep_start_year', true );
						$e_year   = (int) get_post_meta( $edid, '_sep_end_year', true );
						$in_prog  = (bool) get_post_meta( $edid, '_sep_in_progress', true );
						$grade    = get_post_meta( $edid, '_sep_grade', true );
						$ed_desc  = get_post_meta( $edid, '_sep_description', true );
						?>
						<div class="sep-timeline-item">
							<h3 class="sep-timeline-title">
								<?php echo esc_html( $degree ); ?>
								<?php if ( $field ) : ?>
									<span style="color:var(--sep-muted);font-size:0.9em;"><?php echo ' — ' . esc_html( $field ); ?></span>
								<?php endif; ?>
							</h3>
							<p class="sep-timeline-subtitle">
								<?php if ( $inst_url ) : ?>
									<a href="<?php echo esc_url( $inst_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $inst ); ?></a>
								<?php else : ?>
									<?php echo esc_html( $inst ); ?>
								<?php endif; ?>
							</p>
							<p class="sep-timeline-date">
								<?php
								if ( $s_year ) {
									echo esc_html( $s_year ) . ' &mdash; ';
									echo $in_prog ? esc_html__( 'Present', 'se-portfolio' ) : ( $e_year ? esc_html( $e_year ) : '' );
								}
								if ( $grade ) {
									echo ' &nbsp;&bull;&nbsp; ' . esc_html( $grade );
								}
								?>
							</p>
							<?php if ( $ed_desc ) : ?>
								<div class="sep-timeline-body"><?php echo esc_html( $ed_desc ); ?></div>
							<?php endif; ?>
						</div>
					<?php endwhile; ?>
				</div>
			<?php else : ?>
				<p class="sep-empty"><?php esc_html_e( 'No education entries found.', 'se-portfolio' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<!-- ============================================================
	     7. Certificates
	     ============================================================ -->
	<section class="sep-section sep-section--surface" id="sep-certificates" data-sep-section="certificates">
		<div class="sep-section-inner">
			<h2 class="sep-section-heading"><?php esc_html_e( 'Certificates', 'se-portfolio' ); ?></h2>

			<?php if ( $certs_query->have_posts() ) : ?>
				<div class="sep-cards-grid"
					data-section="certificates"
					data-page="1"
					data-per-page="<?php echo esc_attr( $certs_per_page ); ?>"
					data-max-pages="<?php echo esc_attr( $certs_max_pages ); ?>">
					<?php while ( $certs_query->have_posts() ) : $certs_query->the_post(); ?>
						<?php include __DIR__ . '/card-certificate.php'; ?>
					<?php endwhile; ?>
				</div>

				<?php if ( $certs_max_pages > 1 ) : ?>
					<div class="sep-load-more-wrap">
						<button class="sep-btn sep-btn-outline sep-load-more" data-section="certificates">
							<?php esc_html_e( 'Load More', 'se-portfolio' ); ?>
						</button>
					</div>
				<?php endif; ?>

			<?php else : ?>
				<p class="sep-empty"><?php esc_html_e( 'No certificates found.', 'se-portfolio' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<!-- ============================================================
	     8. Contact (optional)
	     ============================================================ -->
	<?php if ( $show_contact ) : ?>
	<section class="sep-section" id="sep-contact" data-sep-section="contact">
		<div class="sep-section-inner sep-contact-inner">
			<h2 class="sep-section-heading">
				<?php echo esc_html( $about['contact_heading'] ?: __( 'Get In Touch', 'se-portfolio' ) ); ?>
			</h2>

			<?php if ( ! empty( $about['contact_intro'] ) ) : ?>
				<p class="sep-contact-intro"><?php echo esc_html( $about['contact_intro'] ); ?></p>
			<?php endif; ?>

			<div class="sep-term-prompt-line sep-contact-cmd">
				<span class="sep-term-ps1">visitor@portfolio:~$</span>
				<span class="sep-term-cmd"> ./reach-out.sh</span>
			</div>

			<div class="sep-contact-cards">
				<?php if ( ! empty( $about['email'] ) ) : ?>
					<a class="sep-contact-card sep-contact-card--primary" href="mailto:<?php echo esc_attr( $about['email'] ); ?>">
						<span class="sep-contact-card-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span>
						<span class="sep-contact-card-label"><?php esc_html_e( 'Email', 'se-portfolio' ); ?></span>
						<span class="sep-contact-card-value"><?php echo esc_html( $about['email'] ); ?></span>
					</a>
				<?php endif; ?>

				<?php if ( ! empty( $about['phone'] ) ) : ?>
					<a class="sep-contact-card" href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $about['phone'] ) ); ?>">
						<span class="sep-contact-card-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20" aria-hidden="true"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.86 9.5a19.79 19.79 0 01-3.07-8.67A2 2 0 012.8 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.91 9.91a16 16 0 006.29 6.29l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg></span>
						<span class="sep-contact-card-label"><?php esc_html_e( 'Phone', 'se-portfolio' ); ?></span>
						<span class="sep-contact-card-value"><?php echo esc_html( $about['phone'] ); ?></span>
					</a>
				<?php endif; ?>

				<?php if ( ! empty( $about['linkedin_url'] ) ) : ?>
					<a class="sep-contact-card" href="<?php echo esc_url( $about['linkedin_url'] ); ?>" target="_blank" rel="noopener noreferrer">
						<span class="sep-contact-card-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg></span>
						<span class="sep-contact-card-label">LinkedIn</span>
						<span class="sep-contact-card-value"><?php echo esc_html( basename( (string) wp_parse_url( $about['linkedin_url'], PHP_URL_PATH ) ) ); ?></span>
					</a>
				<?php endif; ?>

				<?php if ( ! empty( $about['github_url'] ) ) : ?>
					<a class="sep-contact-card" href="<?php echo esc_url( $about['github_url'] ); ?>" target="_blank" rel="noopener noreferrer">
						<span class="sep-contact-card-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20" aria-hidden="true"><path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg></span>
						<span class="sep-contact-card-label">GitHub</span>
						<span class="sep-contact-card-value"><?php echo esc_html( ltrim( (string) wp_parse_url( $about['github_url'], PHP_URL_PATH ), '/' ) ); ?></span>
					</a>
				<?php endif; ?>

				<?php if ( ! empty( $about['location'] ) ) : ?>
					<div class="sep-contact-card sep-contact-card--plain">
						<span class="sep-contact-card-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
						<span class="sep-contact-card-label"><?php esc_html_e( 'Location', 'se-portfolio' ); ?></span>
						<span class="sep-contact-card-value"><?php echo esc_html( $about['location'] ); ?></span>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $about['available'] ) ) : ?>
				<div class="sep-contact-availability">
					<span class="sep-badge-available"><?php esc_html_e( 'Available for opportunities', 'se-portfolio' ); ?></span>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>

	<!-- ============================================================
	     Footer
	     ============================================================ -->
	<footer class="sep-footer">
		<div class="sep-footer-inner">
			<div class="sep-footer-top">
				<div class="sep-footer-brand">
					<p class="sep-footer-brand-comment">// end.portfolio()</p>
					<h3 class="sep-footer-brand-name"><?php echo esc_html( $about['name'] ?? '' ); ?></h3>
					<p class="sep-footer-brand-tagline"><?php echo esc_html( $about['job_title'] ?? '' ); ?></p>
				</div>

				<nav class="sep-footer-nav">
					<div class="sep-footer-nav-col">
						<h4><?php esc_html_e( 'Navigation', 'se-portfolio' ); ?></h4>
						<ul>
							<li><a href="#sep-about"><?php esc_html_e( 'About', 'se-portfolio' ); ?></a></li>
							<li><a href="#sep-skills"><?php esc_html_e( 'Skills', 'se-portfolio' ); ?></a></li>
							<li><a href="#sep-experience"><?php esc_html_e( 'Experience', 'se-portfolio' ); ?></a></li>
							<li><a href="#sep-projects"><?php esc_html_e( 'Projects', 'se-portfolio' ); ?></a></li>
							<li><a href="#sep-education"><?php esc_html_e( 'Education', 'se-portfolio' ); ?></a></li>
							<li><a href="#sep-certificates"><?php esc_html_e( 'Certificates', 'se-portfolio' ); ?></a></li>
						</ul>
					</div>

					<div class="sep-footer-nav-col">
						<h4><?php esc_html_e( 'Connect', 'se-portfolio' ); ?></h4>
						<ul>
							<?php if ( ! empty( $about['github_url'] ) ) : ?>
								<li><a href="<?php echo esc_url( $about['github_url'] ); ?>" target="_blank" rel="noopener noreferrer">GitHub</a></li>
							<?php endif; ?>
							<?php if ( ! empty( $about['linkedin_url'] ) ) : ?>
								<li><a href="<?php echo esc_url( $about['linkedin_url'] ); ?>" target="_blank" rel="noopener noreferrer">LinkedIn</a></li>
							<?php endif; ?>
							<?php if ( ! empty( $about['email'] ) ) : ?>
								<li><a href="mailto:<?php echo esc_attr( $about['email'] ); ?>"><?php esc_html_e( 'Email', 'se-portfolio' ); ?></a></li>
							<?php endif; ?>
							<?php if ( ! empty( $about['show_cv_btn'] ) && ! empty( $about['cv_url'] ) ) : ?>
								<li><a href="<?php echo esc_url( $about['cv_url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Download CV', 'se-portfolio' ); ?></a></li>
							<?php endif; ?>
						</ul>
					</div>
				</nav>
			</div>

			<div class="sep-footer-bottom">
				<p class="sep-footer-copy">
					&copy; <?php echo esc_html( (string) gmdate( 'Y' ) ); ?> <?php echo esc_html( $about['name'] ?? '' ); ?>.
				</p>
			</div>
		</div>
	</footer>

</div><!-- /.sep-portfolio -->
