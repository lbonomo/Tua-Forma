A<?php
/**
 * Class SampleTest
 *
 * @package Tua_Forma
 */


class Test_TuaFormaAdmin extends WP_UnitTestCase {

	public function test_admin_setting() {
		// Verifico que este registrado la "Settings Section"
		$admin_page = new TuaFormaAdmin();	
		$admin_setting = has_action( 'admin_init', [ $admin_page, 'tua_forma_register_setting' ] );	
		$admin_setting = ( 10 === $admin_setting );
		$this->assertTrue( $admin_setting );
	}

	public function test_admin_page() {
		// Verifico que este registrado el menu
		$admin_page = new TuaFormaAdmin();
			
		$admin_menu = has_action( 'admin_menu', [ $admin_page, 'tua_forma_option_page' ] );

		$admin_menu = ( 10 === $admin_menu);
		$this->assertTrue( $admin_menu );
	}

	
}
