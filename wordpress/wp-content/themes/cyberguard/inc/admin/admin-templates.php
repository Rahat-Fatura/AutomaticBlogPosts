<?php

if( !defined( 'ABSPATH' ) )
	exit; 

class cyberguard_Admin_Templates extends cyberguard_Base{

	public function __construct() {
		$this->add_action( 'admin_menu', 'register_page', 20 );
	}
 
	public function register_page() {
		add_submenu_page(
			'pxlart',
		    esc_html__( 'Templates', 'cyberguard' ),
		    esc_html__( 'Templates', 'cyberguard' ),
		    'manage_options',
		    'edit.php?post_type=pxl-template',
		    false
		);
	}
}
new cyberguard_Admin_Templates;
