<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// $m — certificate meta array
$cert_image_url = $m['cert_image'] ? wp_get_attachment_image_url( $m['cert_image'], 'thumbnail' ) : '';
?>
<table class="form-table sep-meta-table">
	<tr>
		<th><label for="sep_issuer"><?php esc_html_e( 'Issuing Organization', 'se-portfolio' ); ?></label></th>
		<td><input type="text" id="sep_issuer" name="sep_issuer" value="<?php echo esc_attr( $m['issuer'] ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th><label for="sep_issue_date"><?php esc_html_e( 'Issue Date', 'se-portfolio' ); ?></label></th>
		<td><input type="date" id="sep_issue_date" name="sep_issue_date" value="<?php echo esc_attr( $m['issue_date'] ); ?>"></td>
	</tr>
	<tr>
		<th><label for="sep_expiry_date"><?php esc_html_e( 'Expiry Date', 'se-portfolio' ); ?></label></th>
		<td>
			<input type="date" id="sep_expiry_date" name="sep_expiry_date" value="<?php echo esc_attr( $m['expiry_date'] ); ?>">
			<label style="margin-left:12px;">
				<input type="checkbox" name="sep_no_expiry" value="1" <?php checked( $m['no_expiry'] ); ?>>
				<?php esc_html_e( 'No Expiry', 'se-portfolio' ); ?>
			</label>
		</td>
	</tr>
	<tr>
		<th><label for="sep_credential_id"><?php esc_html_e( 'Credential ID', 'se-portfolio' ); ?></label></th>
		<td><input type="text" id="sep_credential_id" name="sep_credential_id" value="<?php echo esc_attr( $m['credential_id'] ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th><label for="sep_credential_url"><?php esc_html_e( 'Credential URL', 'se-portfolio' ); ?></label></th>
		<td><input type="url" id="sep_credential_url" name="sep_credential_url" value="<?php echo esc_attr( $m['credential_url'] ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th><?php esc_html_e( 'Certificate Image', 'se-portfolio' ); ?></th>
		<td>
			<div class="sep-media-field">
				<?php if ( $cert_image_url ) : ?>
					<img src="<?php echo esc_url( $cert_image_url ); ?>" alt="" style="max-width:100px;display:block;margin-bottom:8px;">
				<?php endif; ?>
				<input type="hidden" name="sep_cert_image" id="sep_cert_image" value="<?php echo esc_attr( $m['cert_image'] ); ?>">
				<button type="button" class="button sep-upload-btn" data-target="sep_cert_image">
					<?php esc_html_e( 'Select Image', 'se-portfolio' ); ?>
				</button>
			</div>
		</td>
	</tr>
	<tr>
		<th><label for="sep_skills_covered"><?php esc_html_e( 'Skills Covered', 'se-portfolio' ); ?></label></th>
		<td>
			<input type="text" id="sep_skills_covered" name="sep_skills_covered" value="<?php echo esc_attr( $m['skills_covered'] ); ?>" class="regular-text">
			<p class="description"><?php esc_html_e( 'Comma-separated.', 'se-portfolio' ); ?></p>
		</td>
	</tr>
	<tr>
		<th><label for="sep_order"><?php esc_html_e( 'Display Order', 'se-portfolio' ); ?></label></th>
		<td><input type="number" id="sep_order" name="sep_order" value="<?php echo esc_attr( $m['order'] ); ?>" min="0" class="small-text"></td>
	</tr>
</table>
