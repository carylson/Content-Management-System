<?php

	class Sizzle {
	
		public $applets;
		public $apps;
		public $config;
		public $database;
		public $debug;
		public $dir;
		public $helpers;
		public $request;
		public $utils;

		public function __construct() {
    		$this->setDir();
			$this->setConfig();
			$this->setDebug();
			$this->setDatabase();
			$this->setRequest();
			$this->setApplets();
			$this->setApps();
			$this->setHelpers();
			$this->setUtils();
			$this->debug->log('Initialized');
			return;
		}

		private function setApplets() {
			$applets = array();
			$applets_dir = 'applets';
			if ($handle = opendir($applets_dir)) {
				while (false !== ($file = readdir($handle))) {
					if (is_dir($applets_dir .'/'. $file)
					&& file_exists($applets_dir .'/'. $file .'/Controller.class.php')) {
						require_once($applets_dir .'/'. $file .'/Controller.class.php');
						$controller = ucfirst($file) .'Applet';
						$applets[$file] = new $controller();
						if (file_exists($applets_dir .'/'. $file .'/config.json')) {
							$applets[$file]->config = new SizzleConfig();
							$applets[$file]->config->parse($applets_dir .'/'. $file .'/config.json');
						}
					}
				}
				closedir($handle);
			}
			$this->applets = $applets;
			$this->debug->log('Applets set');
			return;
		}

		private function setApps() {
			$apps = array();
			$apps_dir = 'apps';
			if ($handle = opendir($apps_dir)) {
				while (false !== ($file = readdir($handle))) {
					if (is_dir($apps_dir .'/'. $file)
					&& file_exists($apps_dir .'/'. $file .'/Controller.class.php')) {
						require_once($apps_dir .'/'. $file .'/Controller.class.php');
						$controller = ucfirst($file) .'App';
						$apps[$file] = new $controller();
						if (file_exists($apps_dir .'/'. $file .'/config.json')) {
							$apps[$file]->config = new SizzleConfig();
							$apps[$file]->config->parse($apps_dir .'/'. $file .'/config.json');
						}
					}
				}
				closedir($handle);
			}
			$this->apps = $apps;
			$this->debug->log('Apps set');
			return;
		}
		
		private function setConfig() {
			$this->config = new SizzleConfig();
			$this->config->parse('core/config.json');
			return;
		}
		
		private function setDatabase() {
			try {
				$database = new PDO(
					'mysql:host='. $this->config->database['host'] .';dbname='. $this->config->database['name'], 
					$this->config->database['user'], 
					$this->config->database['pass']
				);
			} catch(PDOException $e) {  
			    die('Unable to connect to database "'. $this->config->database['name'] .'" using "'. $this->config->database['user'] .':'. $this->config->database['pass'] .'@'. $this->config->database['host'] .'".');
			}
			$this->database = $database;
			$this->debug->log('Database set');
			return;
		}
		
		private function setDebug() {
			$this->debug = new SizzleDebug($this->config->debug_mode);
			$this->debug->log('Debug set');
			return;
		}
		
		private function setDir() {
			$this->dir = dirname(dirname(__FILE__));
			return;
		}
		
		private function setHelpers() {
    		$helpers = array();
    		foreach ($this->applets as $applet_id=>$applet) {
    			if (is_array($this->applets[$applet_id]->config->helpers)) {
    				foreach ($this->applets[$applet_id]->config->helpers as $helper_id => $helper) {
    					$helpers[$helper_id] = $helper;
    					$helpers[$helper_id]['applet'] = $applet_id;
    				}
    			}        		
    		}
    		asort($helpers);
    		$this->helpers = $helpers;
			return;
		}
		
		private function setRequest() {
    		$request = !empty($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : $_SERVER['REQUEST_URI'] ;
    		$request = str_replace($_SERVER['SCRIPT_NAME'], '', $request);
    		$request = str_replace('?'.$_SERVER['QUERY_STRING'], '', $request);
            $request = array_values(array_diff(explode('/', $request), array('')));
            $this->request = $request;
			$this->debug->log('Request set');
			return;
		}
		
		private function setUtils() {
			$this->utils = new SizzleUtils();
			return;
		}
		
		public function dispatch($alt_params=false) {
			$app = $this->config->default_app;
			$method = 'index';
			$params = is_array($alt_params) ? $alt_params : $this->request ;
			for ($i=0; $i<count($params); $i++) {
    			$params[$i] = preg_replace('/\./u', '_dot_', $params[$i]);
    			$params[$i] = preg_replace('/\-/u', '_dash_', $params[$i]);
    			$params[$i] = preg_replace('/\+/u', '_plus_', $params[$i]);
			}
			if (count($params) > 0 
			&& isset($this->apps[$params[0]])) {
				$app = array_shift($params);
			}
			if (count($params) > 0 
			&& substr($params[0], 0, 1) != '_' 
			&& method_exists($this->apps[$app], $params[0])) {
				$method = array_shift($params);
			}
			$log = 'Dispatched to $GLOBALS[\'sizzle\']->apps[\''. $app .'\']->'. $method;
			$log .= count($params) > 0 ? '(\''. implode('\', \'', $params) .'\')' : '()' ;
			$this->debug->log($log);
			call_user_func_array(array($this->apps[$app], $method), $params);
			return;
		}
	
	}

?>