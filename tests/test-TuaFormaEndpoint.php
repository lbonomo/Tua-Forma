<?php
/**
 * Class SampleTest
 *
 * @package Tua_Forma
 */


class Test_TuaFormaEndpoint extends WP_UnitTestCase {

	public function test_register_endpoint() {
		// Replace this with some actual testing code.

		$endpoint = new TuaFormaEndpoint();	
		 
		$register_endpoint = has_action( 'init', [ $endpoint, 'register_endpoint' ] );
		$register_endpoint = ( 10 === $register_endpoint);
		$this->assertTrue( $register_endpoint );
		
	}

	public function test_template() {
		// Replace this with some actual testing code.

		$endpoint = new TuaFormaEndpoint();	
	 
		$template = has_action( 'template_redirect', [ $endpoint, 'template' ] );
		$template = ( 10 === $template);
		$this->assertTrue( $template );
		
	}
	
}
