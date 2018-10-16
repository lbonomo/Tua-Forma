<?php
/**
 * Class SampleTest
 *
 * @package Tua_Forma
 */


class Test_TuaForma extends WP_UnitTestCase {

	public function test_shortcode() {
		// Verifico que el short-code se verifique correctamente

        $shortcode = new TuaFormaShortCode();

		// Creo un pagina  con el short code
		$html = '[tua-forma][/tua-forma]';

		$page_id = $this->factory->post->create([
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_title' => 'Tua forma Test',
			'post_content' => $html
		]);

		$shortcode = do_shortcode(get_post_field('post_content', $page_id));
		$shortcode = substr($shortcode, 1, 117);
		// echo $shortcode;
		$shortcode = ( $shortcode === "<form accept-charset='UTF-8' action='/tua-forma-send' autocomplete='off' enctype='multipart/form-data' method='POST'>" );
		$this->assertTrue( $shortcode );
	}
	
}
