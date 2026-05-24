<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// $m — education meta array
?>
<table class="form-table sep-meta-table">
	<tr>
		<th><label for="sep_degree"><?php esc_html_e( 'Degree / Qualification', 'se-portfolio' ); ?></label></th>
		<td><input type="text" id="sep_degree" name="sep_degree" value="<?php echo esc_attr( $m['degree'] ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th><label for="sep_field"><?php esc_html_e( 'Field of Study', 'se-portfolio' ); ?></label></th>
		<td><input type="text" id="sep_field" name="sep_field" value="<?php echo esc_attr( $m['field'] ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th><label for="sep_institution"><?php esc_html_e( 'Institution Name', 'se-portfolio' ); ?></label></th>
		<td><input type="text" id="sep_institution" name="sep_institution" value="<?php echo esc_attr( $m['institution'] ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th><label for="sep_institution_url"><?php esc_html_e( 'Institution URL', 'se-portfolio' ); ?></label></th>
		<td><input type="url" id="sep_institution_url" name="sep_institution_url" value="<?php echo esc_attr( $m['institution_url'] ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th><label for="sep_start_year"><?php esc_html_e( 'Start Year', 'se-portfolio' ); ?></label></th>
		<td><input type="number" id="sep_start_year" name="sep_start_year" value="<?php echo esc_attr( $m['start_year'] ); ?>" min="1900" max="2100" class="small-text"></td>
	</tr>
	<tr>
		<th><label for="sep_end_year"><?php esc_html_e( 'End Year', 'se-portfolio' ); ?></label></th>
		<td>
			<input type="number" id="sep_end_year" name="sep_end_year" value="<?php echo esc_attr( $m['end_year'] ); ?>" min="1900" max="2100" class="small-text">
			<label style="margin-left:12px;">
				<input type="checkbox" name="sep_in_progress" value="1" <?php checked( $m['in_progress'] ); ?>>
				<?php esc_html_e( 'In Progress', 'se-portfolio' ); ?>
			</label>
		</td>
	</tr>
	<tr>
		<th><label for="sep_grade"><?php esc_html_e( 'Grade / GPA', 'se-portfolio' ); ?></label></th>
		<td><input type="text" id="sep_grade" name="sep_grade" value="<?php echo esc_attr( $m['grade'] ); ?>" class="small-text" placeholder="<?php esc_attr_e( 'Optional', 'se-portfolio' ); ?>"></td>
	</tr>
	<tr>
		<th><label for="sep_description"><?php esc_html_e( 'Description', 'se-portfolio' ); ?></label></th>
		<td><textarea id="sep_description" name="sep_description" rows="3" class="large-text"><?php echo esc_textarea( $m['description'] ); ?></textarea></td>
	</tr>
	<tr>
		<th><label for="sep_order"><?php esc_html_e( 'Display Order', 'se-portfolio' ); ?></label></th>
		<td><input type="number" id="sep_order" name="sep_order" value="<?php echo esc_attr( $m['order'] ); ?>" min="0" class="small-text"></td>
	</tr>
</table>
