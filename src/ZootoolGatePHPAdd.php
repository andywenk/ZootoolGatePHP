<?php
/**
 * ZootoolGatePHPAdd
 *
 * This is another wrapper for the zootool.com API written in PHP
 *
 * This class provides the ability to add a bookmark to zootool
 *
 * copyright (c) 2010 Andy Wenk <andy@nms.de>
 * license: BSD License
 * requires PHP 5.x
 */
class ZootoolGatePHPAdd extends ZootoolGatePHP {
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * adding an item to the bookmarks. The name is post because that is
	 * the correct HTTP method to add a ressource
	 *
	 * @param string $area
	 * @param array $params 
	 */
	public function post($area, $params = array()) {
		if(!array_key_exists($area, $this->acceptedAreaTypes)) 
			return '{"error" : "this method is not supported"}';
		
		$url = "{$area}/?";
		if(!$params = parent::createParams($params))
			return '{"error" : "invalid parameter submitted"}';

		return parent::requestData(parent::createUrl($url.$params));
	}
}