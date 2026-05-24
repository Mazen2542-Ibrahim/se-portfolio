<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// $m — project meta array from SE_Portfolio_Meta_Boxes::get_project_meta()
?>
<table class="form-table sep-meta-table">
	<tr>
		<th><label for="sep_short_desc"><?php esc_html_e( 'Short Description', 'se-portfolio' ); ?></label></th>
		<td>
			<textarea id="sep_short_desc" name="sep_short_desc" rows="3" class="large-text"><?php echo esc_textarea( $m['short_desc'] ); ?></textarea>
		</td>
	</tr>
	<tr>
		<th><label for="sep_technologies"><?php esc_html_e( 'Technologies Used', 'se-portfolio' ); ?></label></th>
		<td>
			<input type="text" id="sep_technologies" name="sep_technologies" value="<?php echo esc_attr( $m['technologies'] ); ?>" class="regular-text">
			<p class="description"><?php esc_html_e( 'Comma-separated. e.g. PHP, JavaScript, MySQL', 'se-portfolio' ); ?></p>
		</td>
	</tr>
	<tr>
		<th><label for="sep_project_url"><?php esc_html_e( 'Project URL', 'se-portfolio' ); ?></label></th>
		<td><input type="url" id="sep_project_url" name="sep_project_url" value="<?php echo esc_attr( $m['project_url'] ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th><label for="sep_github_url"><?php esc_html_e( 'GitHub Repository URL', 'se-portfolio' ); ?></label></th>
		<td><input type="url" id="sep_github_url" name="sep_github_url" value="<?php echo esc_attr( $m['github_url'] ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th><label for="sep_status"><?php esc_html_e( 'Status', 'se-portfolio' ); ?></label></th>
		<td>
			<select id="sep_status" name="sep_status">
				<option value="in-progress" <?php selected( $m['status'], 'in-progress' ); ?>><?php esc_html_e( 'In Progress', 'se-portfolio' ); ?></option>
				<option value="completed"   <?php selected( $m['status'], 'completed' ); ?>><?php esc_html_e( 'Completed', 'se-portfolio' ); ?></option>
				<option value="archived"    <?php selected( $m['status'], 'archived' ); ?>><?php esc_html_e( 'Archived', 'se-portfolio' ); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<th><label for="sep_start_date"><?php esc_html_e( 'Start Date', 'se-portfolio' ); ?></label></th>
		<td><input type="date" id="sep_start_date" name="sep_start_date" value="<?php echo esc_attr( $m['start_date'] ); ?>"></td>
	</tr>
	<tr>
		<th><label for="sep_end_date"><?php esc_html_e( 'End Date', 'se-portfolio' ); ?></label></th>
		<td><input type="date" id="sep_end_date" name="sep_end_date" value="<?php echo esc_attr( $m['end_date'] ); ?>"></td>
	</tr>
	<tr>
		<th><?php esc_html_e( 'Featured', 'se-portfolio' ); ?></th>
		<td>
			<label>
				<input type="checkbox" name="sep_featured" value="1" <?php checked( $m['featured'] ); ?>>
				<?php esc_html_e( 'Show in hero / featured section', 'se-portfolio' ); ?>
			</label>
		</td>
	</tr>
</table>
