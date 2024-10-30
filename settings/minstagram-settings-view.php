<div class="wrap">
	<h2>Minstagram Settings</h2>
	
	<p> <?php echo __('1. To get ACCESS TOKEN, please input CLIENT ID and CLIENT SECRET', 'minsta'); ?></p>
	<p>
		<?php
		echo __(
				'2. Please set following URL to REDIRECT URL at Instagram Manage Clients page.',
				'minsta'
		); ?>
	</p>
	<p>
		<input type="text"
		       value="<?php echo esc_url( ( empty( $_SERVER['HTTPS'] ) ? 'http://' : 'https://' )
		                                  . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ); ?>" size="80" disabled>
	</p>

	<form method="post" action="">
		<?php wp_nonce_field( 'update-settings' ); ?>
		<input type="hidden" name="type" value="update-settings">
		<table class="form-table">
			<tr>
				<th>CLIENT ID</th>
				<td>
					<input type="text" name="minsta_client_id"
					       value="<?php echo esc_attr( $minsta_client_id ); ?>" size="40">
					<?php if ( get_transient( "minsta_client_id_error" ) ) : ?>
						<p class="error-message"><?php echo __( 'CLIENT ID is required', 'minsta' ); ?></p>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th>CLIENT SECRET</th>
				<td>
					<input type="text" name="minsta_client_secret"
					       value="<?php echo esc_attr( $minsta_client_secret ); ?>" size="40">
					<?php if ( get_transient( "minsta_client_secret_error" ) ) : ?>
						<p class="error-message">
							<?php echo __( 'If you will ACCESS TOKEN then CLIENT SECRET is required', 'minsta' ); ?>
						</p>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th>Access Token</th>
				
				<td>
					
					<input type="text" name="minsta_access_token"
					       value="<?php echo esc_attr( $minsta_access_token ); ?>" size="60"></td>
				
			</tr>
		</table>
		
		<?php if ( '' !== $minsta_access_token_uri ) : ?>
			<p>
				<a href="<?php echo esc_url( $minsta_access_token_uri ); ?>">
					<?php echo __( 'GET ACCESS TOKEN', 'minsta' ); ?></a>
			</p>
		<?php else : ?>
			<p><?php echo __( 'Please save CLIENT ID and CLIENT SECRET to get Access Token.', 'minsta' ); ?></p>
		<?php endif; ?>

		<p class="submit">
			<input type="submit" class="button-primary"
			       value="<?php echo esc_attr( __( 'Save', 'minsta' ) ); ?>"/>
		</p>
	</form>
	<h2>Search USER ID By USER NAME</h2>
	<p><?php echo __( 'User search requires access token.', 'minsta' ); ?></p>
	<?php if ( '' !== $minsta_access_token ): ?>
		<form method="POST" action="">
			<?php wp_nonce_field( 'search-users' ); ?>
			<input type="hidden" name="type" value="search-users">
			<table class="form-table">
				<tr>
					<th>USER NAME</th>
					<td>
						<input type="text" name="minsta_user_name"
						       value="<?php echo esc_attr( $minsta_user_name ); ?>" size="40">
					</td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" class="button-primary"
				       value="<?php echo esc_attr( __( 'Search USER ID', 'minsta' ) ); ?>"/>
			</p>
		</form>
		<div>
			<?php echo $minsta_users; ?>
		</div>
	<?php endif; ?>
</div>
