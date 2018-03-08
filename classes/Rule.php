<?php

abstract class AC_Rule {

	/**
	 * @var AC_ListScreen
	 */
	protected $list_screen;

	/**
	 * @param AC_ListScreen $list_screen
	 */
	public function __construct( AC_ListScreen $list_screen ) {
		$this->list_screen = $list_screen;
	}

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	abstract public function assert();

	/**
	 * @return void
	 */
	abstract public function settings();

	/**
	 * @return string
	 */
	abstract public function get_key();

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	protected function get_options() {
		$data = $this->list_screen->get_option( 'rules' );

		if ( ! isset( $data[ $this->get_key() ] ) ) {
			return false;
		}

		return $data[ $this->get_key() ];
	}

	public function register() {
		// TODO
	}

}