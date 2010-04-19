<?php
/**
 * Tests for the ZootoolGatePHP wrapper
 *
 * copyright (c) 2010 Andy Wenk <andy@nms.de>
 * license: BSD License
 * requires PHP 5.x
 */
require_once 'PHPUnit/Framework.php';
require_once '../src/ZootoolGatePHP.php';
require_once '../src/ZootoolGatePHPAdd.php';

class ZootoolGatePHPTest extends PHPUnit_Framework_TestCase {
	public $zoo;
	
	protected function setUp() {
        $this->zoo = new ZootoolGatePHPProxy();
    }

	public function test_get() {
		$exp = '{"error" : "this area is not supported"}';
		$this->assertSame($exp, $this->zoo->get('bla', 'blub'));
		
		$exp = '{"error" : "this method is not supported"}';
		$this->assertSame($exp, $this->zoo->get('users', 'blub'));
	} 
	
	public function test_parseResult() {
		$exp = (string) '{"name" : "test", "type" : "test"}';

		$this->zoo->setResultFormat();
		$this->assertSame($exp, $this->zoo->parseResult($exp));
		
		$this->zoo->setResultFormat('array');
		$this->assertSame(json_decode($exp, true), $this->zoo->parseResult($exp));
		
		$this->zoo->setResultFormat('object');
		$this->assertEquals(json_decode($exp), $this->zoo->parseResult($exp));
	}
}

// Proxy helper class for having direct access to the protected methods 
class ZootoolGatePHPProxy extends ZootoolGatePHP {
	public function __construct() {
		parent::__construct();
	}
	
	public function parseResult($result) {
		return parent::parseResult($result);
	}
}