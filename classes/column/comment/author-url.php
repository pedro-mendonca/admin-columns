<?php
/**
 * CPAC_Column_Comment_Author_Url
 *
 * @since 2.0.0
 */
class CPAC_Column_Comment_Author_Url extends CPAC_Column {

	function __construct( $storage_model ) {		
		
		$this->properties['type']	 = 'column-author_url';
		$this->properties['label']	 = __( 'Author url', CPAC_TEXTDOMAIN );
		
		parent::__construct( $storage_model );
	}
	
	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {	
		
		$comment = get_comment( $id );
		
		return $this->get_shorten_url( $comment->comment_author_url );
	}
}