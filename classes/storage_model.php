<?php

/**
 * Storage Model
 *
 * @since 2.0.0
 */
abstract class CPAC_Storage_Model {
	
	/**
	 * Label
	 *
	 */
	public $label;
		
	/**
	 * Key
	 *
	 */
	public $key;
	
	/**
	 * Get default columns
	 *
	 * @return array Column Name | Column Label
	 */
	abstract function get_default_columns();
	
	/**
	 * Get custom columns
	 *
	 * @return array Classname | Path
	 */
	abstract function get_custom_columns();
	
	/**
	 * Constructor
	 *
	 */
	function __construct() {}
	
	/**
	 * Restore
	 *
	 * @since 2.0.0
	 */	 
	function restore() {	
	
		delete_option( "cpac_options_{$this->key}" );
		
		CPAC_Utility::admin_message( "<p>" . __( 'Settings succesfully restored.',  CPAC_TEXTDOMAIN ) . "</p>", 'updated' );	
	}
	
	/**
	 * Store
	 *
	 * @since 2.0.0
	 */
	function store() {

		if ( empty( $_POST['columns'] ) )
			return false;
		
		update_option( "cpac_options_{$this->key}", array_filter( $_POST['columns'] ) );
		
		CPAC_Utility::admin_message( "<p>" . __( 'Settings succesfully updated.',  CPAC_TEXTDOMAIN ) . "</p>", 'updated' );	
	}
	
	/**
	 * Get registered columns
	 *
	 * @return array Column Type | Column Instance
	 */
	function get_registered_columns() {
		
		$columns = array();
		
		foreach ( $this->get_default_columns() as $column_name => $label ) {
			
			$column = new CPAC_Column( $this );			
			$column
				->set_type( $column_name )
				->set_label( $label )
				->set_state( 'on' );
			
			if ( 'cb' == $column_name )
				$column->set_hide_label();
			
			$columns[ $column->properties->name ] = $column;			
		}
		
		foreach ( $this->get_custom_columns() as $classname => $path ) {
			include_once $path;	
			
			$column = new $classname( $this );
			$columns[ $column->properties->name ] = $column;
		}
		
		return $columns;
	}
	
	/**
	 * Get column options from DB
	 *
	 * @since 1.0.0
	 *
	 * @paran string $key
	 * @return array Column options
	 */
	private function get_stored_columns() {

		if ( ! $columns = get_option( "cpac_options_{$this->key}" ) )
			return array();
			
		return $columns;
	}
	
	/**
	 * Get Columns
	 *
	 * @since 2.0.0
	 */	 
	function get_columns() {
	
		$columns = array();
		
		// get columns
		$registered_columns = $this->get_registered_columns();
		$stored_columns 	= $this->get_stored_columns();	
		
		// Stored columns
		if ( $stored_columns ) {
			
			$stored_types = array(); 
			
			foreach ( $stored_columns as $name => $options ) {
								
				if ( ! isset( $options['type'] ) )
					continue;
				
				$type = $options['type'];
				
				// remember which types has been used, so we can filter them later
				$stored_types[] = $type;
				
				// In case of a disabled plugin, we will skip column.
				// This means the stored column type is not available anymore.
				if ( ! in_array( $type, array_keys( $registered_columns ) ) )
					continue;
				
				// create clone				
				$column = clone $registered_columns[ $type ];
				
				// add an clone number which defines the instance
				$column->set_clone( $options['clone'] );
				
				// repopulate the options, so they contains the right stored options
				$column->populate_options();
					
				$columns[] = $column;								
			}
			
			// In case of a enabled plugin or added custom column, we will add that column.
			// When $diff contains items, it means an available column has not been stored.
			if ( $diff = array_diff( array_keys( $registered_columns ), $stored_types ) ) {
				foreach ( $diff as $type ) {					
					$columns[] = clone $registered_columns[ $type ];
				}
			}			
		}
		
		// When nothing has been saved yet, we return the available columns.
		else {
		
			$columns = $registered_columns;
		}

		return $columns;		
	}
	
	/**
	 * Render
	 *
	 * @since 2.0.0
	 */	 
	function render() {		
		
		foreach ( $this->get_columns() as $column ) {	
			$column->display();
		}	
	}
	
	/**
	 * Add Headings
	 *
	 * @todo: add column headings that could not be stored from some reason.
	 * @since 2.0.0
	 */
	function add_headings( $columns ) {
		
		global $pagenow;
		
		// only add headings on overview screens, to prevent turning off columns in the Storage Model.
		if ( 'admin.php' == $pagenow )
			return $columns;
		
		if ( ! $stored_columns = get_option( "cpac_options_{$this->key}" ) )
			return $columns;
		
		$column_headings = array();
		
		foreach( $stored_columns as $column_name => $options ) {
			if ( isset( $options[ 'state'] ) && 'on' == $options['state'] ) {				
				$column_headings[ $column_name ] = $options['label'];
			}
		}
		
		// Some 3rd parth columns will no be stored. These still need to be added
		// to the column headings. We check the default stored columns and every columns
		// that is new will be added.
		/* 
		if ( $options = get_option( 'cpac_options_default' ) ) {

			// Get the default columns that have been stored on the settings page.
			$stored_wp_default_columns = $options[$this->storage_key];

			// ... get the 3rd party columns that have not been saved...
			$dif_columns = array_diff( array_keys( $columns ), array_keys( $stored_wp_default_columns ) );

			// ... add those columns to the column headings
			if ( $dif_columns ) {
				foreach ( $dif_columns as $column_name ) {
					$columns_headings[$column_name] = $columns[$column_name];
				}
			}
		} 
		*/

		return $column_headings;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}