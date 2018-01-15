<?php

/**
 * @since NEWVERSION
 */
abstract class AC_Column_Container extends AC_Column {

	/**
	 * Register settings
	 */
	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_Container( $this ) );
	}

	/**
	 * @return AC_Settings_Column_Container|AC_Settings_Column
	 */
	public function get_setting_container() {
		return $this->get_setting( 'container' );
	}

}