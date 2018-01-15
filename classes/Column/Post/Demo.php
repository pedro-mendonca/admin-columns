<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Demo extends AC_Column_Container {

	public function __construct() {
		$this->set_type( 'column-demo' );
		$this->set_label( __( 'Demo', 'codepress-admincolumns' ) );
	}

}