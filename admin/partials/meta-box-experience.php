<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// $m — experience meta array
?>
<table class="form-table sep-meta-table">
	<tr>
		<th><label for="sep_company"><?php esc_html_e( 'Company Name', 'se-portfolio' ); ?></label></th>
		<td><input type="text" id="sep_company" name="sep_company" value="<?php echo esc_attr( $m['company'] ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th><label for="sep_company_url"><?php esc_html_e( 'Company URL', 'se-portfolio' ); ?></label></th>
		<td><input type="url" id="sep_company_url" name="sep_company_url" value="<?php echo esc_attr( $m['company_url'] ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th><label for="sep_employment_type"><?php esc_html_e( 'Employment Type', 'se-portfolio' ); ?></label></th>
		<td>
			<select id="sep_employment_type" name="sep_employment_type">
				<option value="full-time"  <?php selected( $m['employment_type'], 'full-time' ); ?>><?php esc_html_e( 'Full-time', 'se-portfolio' ); ?></option>
				<option value="part-time"  <?php selected( $m['employment_type'], 'part-time' ); ?>><?php esc_html_e( 'Part-time', 'se-portfolio' ); ?></option>
				<option value="contract"   <?php selected( $m['employment_type'], 'contract' ); ?>><?php esc_html_e( 'Contract', 'se-portfolio' ); ?></option>
				<option value="freelance"  <?php selected( $m['employment_type'], 'freelance' ); ?>><?php esc_html_e( 'Freelance', 'se-portfolio' ); ?></option>
				<option value="internship" <?php selected( $m['employment_type'], 'internship' ); ?>><?php esc_html_e( 'Internship', 'se-portfolio' ); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<th><label for="sep_start_date"><?php esc_html_e( 'Start Date', 'se-portfolio' ); ?></label></th>
		<td><input type="date" id="sep_start_date" name="sep_start_date" value="<?php echo esc_attr( $m['start_date'] ); ?>"></td>
	</tr>
	<tr>
		<th><label for="sep_end_date"><?php esc_html_e( 'End Date', 'se-portfolio' ); ?></label></th>
		<td>
			<input type="date" id="sep_end_date" name="sep_end_date" value="<?php echo esc_attr( $m['end_date'] ); ?>">
			<label style="margin-left:12px;">
				<input type="checkbox" name="sep_is_present" value="1" <?php checked( $m['is_present'] ); ?>>
				<?php esc_html_e( 'Currently working here', 'se-portfolio' ); ?>
			</label>
		</td>
	</tr>
	<tr>
		<th><label for="sep_location"><?php esc_html_e( 'Location', 'se-portfolio' ); ?></label></th>
		<td><input type="text" id="sep_location" name="sep_location" value="<?php echo esc_attr( $m['location'] ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th><label for="sep_technologies"><?php esc_html_e( 'Technologies', 'se-portfolio' ); ?></label></th>
		<td>
			<input type="text" id="sep_technologies" name="sep_technologies" value="<?php echo esc_attr( $m['technologies'] ); ?>" class="regular-text">
			<p class="description"><?php esc_html_e( 'Comma-separated.', 'se-portfolio' ); ?></p>
		</td>
	</tr>
	<tr>
		<th><label for="sep_order"><?php esc_html_e( 'Display Order', 'se-portfolio' ); ?></label></th>
		<td><input type="number" id="sep_order" name="sep_order" value="<?php echo esc_attr( $m['order'] ); ?>" min="0" class="small-text"></td>
	</tr>
</table>
