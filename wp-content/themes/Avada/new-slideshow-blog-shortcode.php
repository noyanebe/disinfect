<?php global $post; ?>

<?php if ( 'grid' != $atts['layout'] && 'timeline' != $atts['layout'] ) : ?>
	<style type="text/css">
		<?php if ( get_post_meta( $post->ID, 'pyre_fimg_width', true ) && 'auto' != get_post_meta( $post->ID, 'pyre_fimg_width', true ) ) : ?>
			#post-<?php echo $post->ID; ?> .fusion-post-slideshow {
				max-width: <?php echo get_post_meta( $post->ID, 'pyre_fimg_width', true ); ?> !important;
			}
		<?php endif; ?>

		<?php if ( get_post_meta( $post->ID, 'pyre_fimg_height', true ) && 'auto' != get_post_meta( $post->ID, 'pyre_fimg_height', true ) ) : ?>
			#post-<?php echo $post->ID; ?> .fusion-post-slideshow,
			#post-<?php echo $post->ID; ?> .fusion-post-slideshow .fusion-image-wrapper img {
				max-height: <?php echo get_post_meta( $post->ID, 'pyre_fimg_height', true ); ?> !important;
			}
		<?php endif; ?>

		<?php if ( get_post_meta( $post->ID, 'pyre_fimg_width', true ) && 'auto' == get_post_meta( $post->ID, 'pyre_fimg_width', true ) ) : ?>
			#post-<?php echo $post->ID; ?> .fusion-post-slideshow .fusion-image-wrapper img {
				width: auto;
			}
		<?php endif; ?>

		<?php if ( get_post_meta($post->ID, 'pyre_fimg_height', true ) && 'auto' == get_post_meta( $post->ID, 'pyre_fimg_height', true ) ) : ?>
			#post-<?php echo $post->ID; ?> .fusion-post-slideshow .fusion-image-wrapper img {
				height: auto;
			}
		<?php endif; ?>

		<?php if ( get_post_meta($post->ID, 'pyre_fimg_height', true ) && get_post_meta( $post->ID, 'pyre_fimg_width', true ) && 'auto' != get_post_meta( $post->ID, 'pyre_fimg_height', true ) && 'auto' != get_post_meta( $post->ID, 'pyre_fimg_width', true ) ) : ?>
			@media only screen and (max-width: 479px){
				#post-<?php echo $post->ID; ?> .fusion-post-slideshow,
				#post-<?php echo $post->ID; ?> .fusion-post-slideshow .fusion-image-wrapper img {
					width :auto !important;
					height :auto !important;
				}
			}
		<?php endif; ?>
	</style>
<?php endif; ?>

<?php
$permalink = get_permalink( $post->ID );

$size = 'blog-large';
$size = ( ! Avada()->template->has_sidebar() || 'yes' == get_post_meta( $post->ID, 'pyre_full_width', true ) ) ? 'full' : 'blog-large';
$size = ( 'medium' == $atts['layout'] || 'medium-alternate' == $atts['layout'] ) ? 'blog-medium' : $size;
$size = ( get_post_meta( $post->ID, 'pyre_fimg_height', true ) && get_post_meta( $post->ID, 'pyre_fimg_width', true ) && 'auto' != get_post_meta( $post->ID, 'pyre_fimg_height', true ) && 'auto' != get_post_meta( $post->ID, 'pyre_fimg_width', true ) ) ? 'full' : $size;
$size = ( 'auto' == get_post_meta( $post->ID, 'pyre_fimg_height', true ) || 'auto' == get_post_meta( $post->ID, 'pyre_fimg_width', true ) ) ? 'full' : $size;
$size = ( 'grid' == $atts['layout'] || 'timeline' == $atts['layout'] ) ? 'full' : $size;
?>

<?php if ( has_post_thumbnail() || get_post_meta(get_the_ID(), 'pyre_video', true ) ) : ?>
	<div class="fusion-flexslider flexslider fusion-flexslider-loading fusion-post-slideshow">
		<ul class="slides">
			<?php if ( get_post_meta( get_the_ID(), 'pyre_video', true ) ) : ?>
				<li>
					<div class="full-video"><?php echo get_post_meta( get_the_ID(), 'pyre_video', true ); ?></div>
				</li>
			<?php endif; ?>

			<?php
			if ( 'grid' == $atts['layout'] ) {
				Avada()->images->set_grid_image_meta( array( 'layout' => $atts['layout'], 'columns' => $atts['blog_grid_columns'] ) );
			} elseif ( 'timeline' == $atts['layout'] ) {
				Avada()->images->set_grid_image_meta( array( 'layout' => $atts['layout'], 'columns' => '2' ) );
			} elseif ( false !== strpos( $atts['layout'], 'large' ) && 'full' == $size ) {
				Avada()->images->set_grid_image_meta( array( 'layout' => $atts['layout'], 'columns' => '1' ) );
			}
			?>
			<?php if ( has_post_thumbnail() ) : ?>
				<?php $full_image      = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>
				<?php $attachment_data = wp_get_attachment_metadata( get_post_thumbnail_id() ); ?>
				<li><?php echo avada_render_first_featured_image_markup( $post->ID, $size, $permalink ); ?></li>
			<?php endif; ?>

			<?php $i = 2; ?>
			<?php while ( $i <= Avada()->settings->get( 'posts_slideshow_number' ) ) : ?>
				<?php $attachment_id = kd_mfi_get_featured_image_id( 'featured-image-' . $i, 'post' ); ?>
				<?php if ( $attachment_id ) : ?>

					<?php $attachment_data  = wp_get_attachment_metadata( $attachment_id ); ?>
					<?php $full_image       = wp_get_attachment_image_src( $attachment_id, 'full' ); ?>

					<?php if ( 'grid' == $atts['layout'] ) {
						$image_size = Avada()->images->get_grid_image_base_size( $attachment_id, Avada_Images::$grid_image_meta['layout'], Avada_Images::$grid_image_meta['columns'] );
						$attachment_image = wp_get_attachment_image_src( $attachment_id, $image_size );
					} else {
						$attachment_image = wp_get_attachment_image_src( $attachment_id, $size );
					} ?>

					<?php if ( is_array( $attachment_data ) ) : ?>
						<li>
							<div class="fusion-image-wrapper">
								<a href="<?php the_permalink(); ?>">
									<?php 
									$image_markup = sprintf( '<img src="%s" alt="%s" class="wp-image-%s" role="presentation"/>', $attachment_image[0], $attachment_data['image_meta']['title'], $attachment_id );
									$image_markup = Avada()->images->edit_grid_image_src( $image_markup, get_the_ID(), $attachment_id, $size );
									echo wp_make_content_images_responsive( $image_markup );
									?>
								</a>
								<a style="display:none;" href="<?php echo $full_image[0]; ?>" data-rel="iLightbox[gallery<?php echo $post->ID; ?>]"  title="<?php echo get_post_field( 'post_excerpt', $attachment_id ); ?>" data-title="<?php echo get_post_field( 'post_title', $attachment_id ); ?>" data-caption="<?php echo get_post_field( 'post_excerpt', $attachment_id ); ?>">
									<?php if ( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) : ?>
										<img style="display:none;" alt="<?php echo get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ); ?>" role="presentation" />
									<?php endif; ?>
								</a>
							</div>
						</li>
					<?php endif; ?>
				<?php endif; ?>
				<?php $i++; ?>
			<?php endwhile; ?>
			<?php Avada()->images->set_grid_image_meta( array() ); ?>
		</ul>
	</div>
<?php endif;

// Omit closing PHP tag to avoid "Headers already sent" issues.
