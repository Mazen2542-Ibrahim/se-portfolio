<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// $m — skill meta array
?>
<table class="form-table sep-meta-table">
	<tr>
		<th><label for="sep_category"><?php esc_html_e( 'Category', 'se-portfolio' ); ?></label></th>
		<td>
			<select id="sep_category" name="sep_category">
				<option value="frontend"   <?php selected( $m['category'], 'frontend' ); ?>><?php esc_html_e( 'Frontend', 'se-portfolio' ); ?></option>
				<option value="backend"    <?php selected( $m['category'], 'backend' ); ?>><?php esc_html_e( 'Backend', 'se-portfolio' ); ?></option>
				<option value="devops"     <?php selected( $m['category'], 'devops' ); ?>><?php esc_html_e( 'DevOps', 'se-portfolio' ); ?></option>
				<option value="database"   <?php selected( $m['category'], 'database' ); ?>><?php esc_html_e( 'Database', 'se-portfolio' ); ?></option>
				<option value="tools"      <?php selected( $m['category'], 'tools' ); ?>><?php esc_html_e( 'Tools', 'se-portfolio' ); ?></option>
				<option value="soft-skills" <?php selected( $m['category'], 'soft-skills' ); ?>><?php esc_html_e( 'Soft Skills', 'se-portfolio' ); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<th><label for="sep_proficiency"><?php esc_html_e( 'Proficiency (1–100)', 'se-portfolio' ); ?></label></th>
		<td><input type="number" id="sep_proficiency" name="sep_proficiency" value="<?php echo esc_attr( $m['proficiency'] ); ?>" min="1" max="100" class="small-text"></td>
	</tr>
	<tr>
		<th><label for="sep_icon"><?php esc_html_e( 'Icon', 'se-portfolio' ); ?></label></th>
		<td>
			<input type="text" id="sep_icon" name="sep_icon" value="<?php echo esc_attr( $m['icon'] ); ?>" class="regular-text">
			<p class="description"><?php esc_html_e( 'Dashicon class, emoji, or SVG string.', 'se-portfolio' ); ?></p>
		</td>
	</tr>
	<tr>
		<th><label for="sep_years_exp"><?php esc_html_e( 'Years of Experience', 'se-portfolio' ); ?></label></th>
		<td><input type="number" id="sep_years_exp" name="sep_years_exp" value="<?php echo esc_attr( $m['years_exp'] ); ?>" min="0" class="small-text"></td>
	</tr>
	<tr>
		<th><label for="sep_order"><?php esc_html_e( 'Display Order', 'se-portfolio' ); ?></label></th>
		<td><input type="number" id="sep_order" name="sep_order" value="<?php echo esc_attr( $m['order'] ); ?>" min="0" class="small-text"></td>
	</tr>
</table>
