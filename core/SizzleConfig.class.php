<?php

	class SizzleConfig {
	
		private $config;
		
		public function __construct() {
			$this->config = array();
		}

		public function __get($var) {
			if (!isset($this->config[$var])) {
				return false;
			}
			return $this->config[$var];
		}
		
		public function __set($var, $val) {
			$this->config[$var] = $val;
			return;
		}
		
		public function parse($file=false) {
			if (!file_exists($file)) {
				die('Invalid config loaded "'. $file .'".');
			}
			$config = json_decode(file_get_contents($file), true);
			if (!is_array($config)) {
				die('Invalid config loaded "'. $file .'".');
			}
			$this->config = array_merge_recursive($this->config, $config);
			return;
		}

	}

?>