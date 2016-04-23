<?php

class Avada_Patcher_Admin_Screen {

	/**
	 * The class constructor
	 */
	public function __construct() {
		// Call register settings function
		add_action( 'admin_init', array( $this, 'settings' ) );
		// Add the patcher to the support screen
		add_action( 'avada/admin_pages/support/after_list', array( $this, 'form' ) );
	}

	/**
	 * Register the settings
	 */
	public function settings() {
		$patches = Avada_Patcher_Client::get_patches();
		if ( ! empty( $patches ) ) {
			foreach( $patches as $key => $value ) {
				register_setting( 'avada_patcher_' . $key, 'avada_patch_contents_' . $key );
			}
		}
	}

	/**
	 * The page contents
	 */
	public function form() {
		$patches = Avada_Patcher_Client::get_patches();
		// Get the fusion-core plugin version
		$fusion_core_version = ( class_exists( 'FusionCore_Plugin' ) ) ? FusionCore_Plugin::VERSION : false;
		// Get the avada theme version
		$avada_version = Avada::get_theme_version();

		$available_patches = array();

		// Determine if there are available patches, and build an array of them.
		$context = array( 'avada' => false, 'fusion-core' => false );
		foreach ( $patches as $patch_id => $patch_args ) {
			if ( ! isset( $patch_args['patch'] ) ) {
				continue;
			}
			foreach ( $patch_args['patch'] as $key => $unique_patch_args ) {
				if ( 'avada' == $unique_patch_args['context'] && $avada_version == $unique_patch_args['version'] ) {
					$available_patches[] = $patch_id;
					$context['avada'] = true;
				} elseif ( 'fusion-core' == $unique_patch_args['context'] && $fusion_core_version == $unique_patch_args['version'] ) {
					$available_patches[] = $patch_id;
					$context['fusion-core'] = true;
				}
			}
		}

		$available_patches = array_unique( $available_patches );
		$applied_patches   = get_option( 'avada_applied_patches', array() );
		?>
		<div class="avada-important-notice avada-auto-patcher">

			<p class="avada-auto-patcher description">
				<?php if ( empty( $available_patches ) ) : ?>
					<?php printf( esc_html__( 'Avada Patcher: There Are No Available Patches For Avada v%s', 'Avada' ), $avada_version ); ?>
				<?php else : ?>
					<?php printf( esc_html__( 'Avada Patcher: The following patches are available for Avada %s', 'Avada' ), $avada_version ); ?>
				<?php endif; ?>
				<span class="avada-auto-patcher learn-more"><a href="https://theme-fusion.com/avada-doc/avada-patcher/" target="_blank"><?php esc_attr_e( 'Learn More', 'Avada' ); ?></a></span>
			</p>
			<?php if ( ! empty( $available_patches ) ) : // Only display the table if we have patches to apply ?>
				<table class="avada-patcher-table">
					<tbody>
						<tr class="avada-patcher-headings">
							<th><?php esc_attr_e( 'Patch #', 'Avada' ); ?></th>
							<th><?php esc_attr_e( 'Issue Date', 'Avada' ); ?></th>
							<th><?php esc_attr_e( 'Description', 'Avada' ); ?></th>
							<th><?php esc_attr_e( 'Status', 'Avada' ); ?></th>
							<th></th>
						</tr>
						</tr>
						<?php foreach ( $patches as $patch_id => $patch_args ) : ?>
							<?php if ( ! in_array( $patch_id, $available_patches ) ) : ?>
								<?php continue; ?>
							<?php endif; ?>
							<?php $patch_applied = ( in_array( $patch_id, $applied_patches ) ) ? true : false; ?>

							<tr class="avada-patcher-table-head">
								<td class="patch-id">#<?php echo intval( $patch_id ); ?></td>
								<td class="patch-date"><?php echo $patch_args['date'][0]; ?></td>
								<td class="patch-description"><?php echo $patch_args['description'][0]; ?></td>
								<td class="patch-status">
									<?php if ( $patch_applied ) : ?>
										<span style="color:#4CAF50;" class="dashicons dashicons-yes"></span>
									<?php endif; ?>
								</td>
								<td class="patch-apply">
									<form method="post" action="options.php">
										<?php settings_fields( 'avada_patcher_' . $patch_id ); ?>
										<?php do_settings_sections( 'avada_patcher_' . $patch_id ); ?>
										<input type="hidden" name="avada_patch_contents_<?php echo $patch_id; ?>" value="<?php echo self::format_patch( $patch_args ); ?>" />
										<?php submit_button( esc_attr__( 'Apply Patch', 'Avada' ) ); ?>
									</form>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Format the patch.
	 * We're encoding everything here for security reasons.
	 * We're also going to check the current versions of Avada & Fusion-Core,
	 * and then build the hash for this patch using the files that are needed.
	 */
	private static function format_patch( $patch ) {
		// Get the fusion-core plugin version
		$fusion_core_version = ( class_exists( 'FusionCore_Plugin' ) ) ? FusionCore_Plugin::VERSION : false;
		// Get the avada theme version
		$avada_version = Avada::get_theme_version();

		$patches = array();
		if ( ! isset( $patch['patch'] ) ) {
			return;
		}
		foreach ( $patch['patch'] as $key => $args ) {
			if ( ! isset( $args['context'] ) || ! isset( $args['path'] ) || ! isset( $args['reference'] ) ) {
				continue;
			}
			if ( 'avada' == $args['context'] && $avada_version == $args['version'] ) {
				$patches[ $args['context'] ][ $args['path'] ] = $args['reference'];
			} elseif ( 'fusion-core' == $args['context'] && $fusion_core_version == $args['version'] ) {
				$patches[ $args['context'] ][ $args['path'] ] = $args['reference'];
			}
		}
		return base64_encode( json_encode( $patches ) );
	}

}
