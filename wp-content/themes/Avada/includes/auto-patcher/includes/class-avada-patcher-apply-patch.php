<?php

class Avada_Patcher_Apply_Patch {

	/**
	 * @var bool|array
	 */
	public $setting = false;

	/**
	 * The class constructor
	 */
	public function __construct() {
		$patches = Avada_Patcher_Client::get_patches();
		foreach ( $patches as $key => $args ) {
			$this->setting = false;
			$this->get_setting( $key );
			if ( false !== $this->setting && ! empty( $this->setting ) ) {
				$this->apply_patch( $key );
			}
		}
	}

	/**
	 * Get the setting from the database
	 * If the setting exists, decode it and set the class's $setting property to an array.
	 */
	public function get_setting( $key ) {
		$setting = get_site_option( 'avada_patch_contents_' . $key, false );
		if ( false !== $setting && ! empty( $setting ) ) {
			$setting = (array) json_decode( base64_decode( $setting ) );
			if ( is_array( $setting ) && ! empty( $setting ) ) {
				$this->setting = $setting;
			}
		}
	}

	/**
	 * Applies the patch.
	 * If everything is alright, return true.
	 * If there was a mistake, return false.
	 *
	 * @return bool
	 */
	public function apply_patch( $key ) {
		if ( is_array( $this->setting ) ) {
			foreach ( $this->setting as $target => $args ) {
				$args = (array) $args;
				foreach ( $args as $destination => $source ) {
					new Avada_Patcher_Filesystem( $target, $source, $destination );
				}
			}
			$this->remove_setting( $key );
			$this->update_applied_patches( $key );
		}
	}

	/**
	 * Remove the setting from the database.
	 */
	public function remove_setting( $key ) {
		delete_site_option( 'avada_patch_contents_' . $key );
	}

	/**
	 * Update the applied patches array in the db.
	 */
	public function update_applied_patches( $key ) {
		$applied_patches = get_site_option( 'avada_applied_patches', array() );
		if ( ! in_array( $key, $applied_patches ) ) {
			$applied_patches[] = $key;
			$applied_patches   = array_unique( $applied_patches );
			update_site_option( 'avada_applied_patches', $applied_patches );
		}
	}
}
