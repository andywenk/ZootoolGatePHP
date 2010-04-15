<?php
/**
 * ZootoolGatePHP
 *
 * This is another wrapper for the zootool.com API written in PHP
 *
 * copyright (c) 2010 Andy Wenk <andy@nms.de>
 * license: BSD License
 * requires PHP 5.x
 */
class ZootoolGatePHP {
	protected $username;
	protected $password;
	protected $apikey;
	protected $limit = 100;
	protected $format;
	protected $zootoolApiUrl = 'http://zootool.com/api/';
	protected $acceptedAreaTypes = array('items' 	=> array('popular', 'info'), 
							   			 'users' 	=> array('items', 'info', 
															 'friends', 'followers'),
							   			 'add' 		=> array()
								   );
	protected $acceptedParams = array('type' => array('all', 'month', 'week', 'today'),
									  'username', 'login', 'offset', 'limit', 'search',
									  'url', 'title', 'tags', 'description', 'referer',
									  'public' => array('y', 'n'));
	
	/**
	 * constructor
	 */
	public function __construct() { } 
	
	/**
	 * Set the username.
	 * 
	 * @param string $username
	 */
	public function setUsername($username) {
		$this->username = $username;
	}
	
	/**
	 * Set the users password. The password will be encrypted with the sha1 algorithm
	 * 
	 * @param string $username
	 */
	public function setPassword($password) {
		$this->password = sha1($password);
	}
	
	/**
	 * Set the users API key. The API key is provided by zootool.com and
	 * is looking like cb6330f9d3532b7b0a7e5c80c5182d93 
	 *
	 * @param string $apikey 
	 */
	public function setApikey($apikey) {
		$this->apikey = $apikey;
	}
	
	/**
	 * Set the limit for how many entrys are going to be requested
	 *
	 * @param int $limit
	 */
	public function setLimit($limit) {
		$this->limit = $limit;
	}
	
	/**
	 * define the putput format for the result
	 *
	 * @param string $format (default json) 
	 */
	public function setResultFormat($format = 'json') {
		$this->format = $format;
	}
	
	/**
	 * This is the central call to receive the data from the webservice. It 
	 * requires allways an area like items or users and the type. The parameter
	 * for the URL in the corresponding method are provided via an array
	 *
	 * @param string $area
	 * @param string $type
	 * @param array $options (optional)
	 * @return json result from the called method 
	 */
	public function get($area, $type, $params = array()) {
		if(!in_array($type, $this->acceptedAreaTypes[$area])) 
			return '{"error" : "this method is not supported"}';
		
		$url = "{$area}/{$type}/?";
		if(!$params = self::createParams($params))
			return '{"error" : "invalid parameter submitted"}';
		
		return self::requestData(self::createUrl($url.$params));
	}
	
	/**
	 * There are some parameters in the URL which are put together here.
	 * 
	 * @param array $params
	 * @return string $paramURL
	 */
	protected function createParams($params) {
		foreach($params as $key => $val) {
			if(!self::checkParams($key, $val)) return false;
			$val = urlencode($val);
			$paramURL[] = "{$key}={$val}";
		}
		return implode('&', $paramURL);
	}
	
	/**
	 * There are some valid parameters defined in $acceptedParams. Here we check,
	 * if the submitted parameter ar valid.
	 *
	 * @param string $key
	 * @param string $val
	 */
	protected function checkParams($key, $val) {
		if(array_key_exists($key, $this->acceptedParams) || in_array($key, $this->acceptedParams)) {
			if(is_array($this->acceptedParams[$key])) {
				if(!in_array($val, $this->acceptedParams[$key])) {
					return false;
				}
			}
			return true;
		}
		return false;
	}
	
	/**
	 * Helper method to put the URL for the reuqest together
	 * 
	 * @return string url  
	 */
	protected function createURL($urlPart) {
		return $this->zootoolApiUrl . $urlPart . '&apikey=' . $this->apikey . '&limit=' . $this->limit;
	}
	
	/**
	 * Request the data with the curl library.
	 *
	 * @param string $url
	 * @return result from the http request
	 */
	protected function requestData($url) {
		try {
			if(!function_exists('curl_init'))
				throw new Exception('{"error" : "curl_init is not available! Please install the php_curl extension"}');
			if(!$curlHandle = curl_init()) 
				throw new Exception('{"error" : "curl_init failed!"}');
			
			curl_setopt($curlHandle, CURLOPT_URL, $url);

			// HTTP Digest Authentication
			curl_setopt($curlHandle, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
			
			if($this->username && $this->password) {
				curl_setopt($curlHandle, CURLOPT_USERPWD, 
					strtolower($this->username) . ':' . $this->password);
			}

			curl_setopt($curlHandle, CURLOPT_USERAGENT, 'My PHP Script');
			curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);

			if(!$result = curl_exec($curlHandle)) 
				throw new exception ('{"error" : "curl_exec failed!"}');
			
			curl_close($curlHandle);
		} catch (Exception $e) {
			return $e->getMessage();
		}
		
		return self::parseResult($result);
	}
	
	/**
	 * There are different formats available in which the result is provided.
	 * The default is json and there is also as an array or as an object.
	 *
	 * @param $result
	 * @param encoded result
	 */
	protected function parseResult($result) {
		switch($this->format) {
			default:
				return $result;
			break;
			
			case 'array':
				return json_decode($result, true);
			break;
			
			case 'object':
				return json_decode($result);
			break;
		}
	}
}