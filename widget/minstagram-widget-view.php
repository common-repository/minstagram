<?php if ( ! empty( $minsta_items ) ) : ?>
	<div class="widget widget_minstagram">
		<div class="widget-inner">
			<?php if ( ! empty( $minsta_title ) ) : ?>
				<h4 class="title"><?php echo esc_html( $minsta_title ); ?></h4>
			<?php endif; ?>
			<ul>
				<?php foreach ( $minsta_items as $minsta_item ) : ?>
					<li>
						<a href="<?php echo esc_url( $minsta_item['link'] ); ?>">
							<img src="<?php echo esc_url( $minsta_item['thumb_uri'] ); ?>">
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
<?php else: ?>
	<div class="widget widget_minstagram">
		<div class="widget-inner">
			<h4 class="title"><?php echo esc_html( $minsta_title ); ?></h4>
			<p><?php echo esc_html( __( 'It could not be displayed correctly.', 'minsta' ) ) ?></p>
		</div>
	</div>
<?php endif; ?>
