<?php

class AC_Settings_Column_Container extends AC_Settings_Column {

	private $column_type;

	public function get_name() {
		return 'container';
	}

	protected function define_options() {
		return array(
			'column_type' => '',
		);
	}

	/**
	 * @return array
	 */
	private function get_column_types() {
		$types = array();

		foreach ( $this->column->get_list_screen()->get_column_types() as $column ) {
			if ( ! $column->get_container() ) {
				continue;
			}

			if ( get_class( $this->column ) !== get_class( $column->get_container() ) ) {
				continue;
			}

			$types[ $column->get_type() ] = trim( strip_tags( $column->get_label() ) );
		}

		return $types;
	}

	public function create_view() {

		$setting = $this->create_element( 'select' )

						// TODO: load selected item as a column
						->set_attribute( 'data-refresh', 'selection' )
		                ->set_options( $this->get_column_types() );

		$view = new AC_View( array(
			'label'   => __( 'Field', 'codepress-admin-columns' ),
			'setting' => $setting,
		) );

		return $view;
	}

	/**
	 * @return int
	 */
	public function get_column_type() {
		return $this->column_type;
	}

	/**
	 * @param int $column_type
	 *
	 * @return bool
	 */
	public function set_column_type( $column_type ) {
		$this->column_type = $column_type;

		return true;
	}

}