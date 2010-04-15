<?php
/**
 * Tests for the ZootoolGatePHP wrapper
 */

class ZootoolGatePHP extends PHPUnit_Framework_TestCase {
	public $zoo;
	
	protected function setUp() {
        $this->zoo = new ZootoolGatePHP();
    }
}