<p>
	<?php if ( $minsta_client_id_error ) : ?>
		<span class="error-message"><?php echo esc_html( __( 'Set CLIENT ID at Minstagram setting page.',
				'minsta' ) ); ?></span>
	<?php endif; ?>
</p>
<p>
	<label for="<?php echo esc_attr( $this->get_field_id( 'minsta_title' ) ); ?>">
		<?php echo esc_html( __( 'Title', 'minsta' ) ); ?>:
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'minsta_title' ) ); ?>"
		       name="<?php echo esc_attr( $this->get_field_name( 'minsta_title' ) ); ?>" type="text"
		       value="<?php echo esc_attr( $minsta_title ); ?>"/>
	</label>
</p>
<?php if ( 'users' === $minsta_type ) : ?>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'minsta_user_id' ) ); ?>">
			<?php echo esc_html( __( 'User ID (Required)', 'minsta' ) ); ?>:
			<?php if ( $minsta_user_id_error ) : ?>
				<span class="error-message"><?php echo esc_html(__( 'User ID is required.', 'minsta' )); ?></span>
			<?php endif; ?>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'minsta_user_id' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'minsta_user_id' ) ); ?>" type="text"
			       value="<?php echo esc_attr( $minsta_user_id ); ?>"/>
		</label>
	</p>
<?php endif; ?>
<?php if ( 'tags' === $minsta_type ) : ?>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'minsta_tag' ) ); ?>">
			<?php echo esc_html(__( 'Tag (Required)', 'minsta' )); ?>:
			<?php if ( $minsta_tag_error ) : ?>
				<span class="error-message"><?php echo esc_html(__( 'Tag is required.', 'minsta' )); ?></span>
			<?php endif; ?>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'minsta_tag' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'minsta_tag' ) ); ?>" type="text"
			       value="<?php echo esc_attr( $minsta_tag ); ?>"/>
		</label>
	</p>
<?php endif; ?>
<p>
	<label for="<?php echo esc_attr( $this->get_field_id( 'minsta_count' ) ); ?>">
		<?php echo esc_html(__( 'The number of display (Required)', 'minsta' ) ); ?>:
		<?php if ( $minsta_count_error ) : ?>
			<span class="error-message"><?php echo esc_html(__( 'The number of display item is required.', 'minsta' )); ?></span>
		<?php endif; ?>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'minsta_count' ) ); ?>"
		       name="<?php echo esc_attr( $this->get_field_name( 'minsta_count' ) ); ?>" type="text"
		       value="<?php echo esc_attr( $minsta_count ); ?>"/>
	</label>
</p>
