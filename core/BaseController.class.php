<?php

	class BaseController {

		public $models;

		public function __call($method, $args) {
			die('Controller "'. get_class($this) .'" does not have a "'. $method .'" method.');
		}

		public function __construct() {
			$this->models = array();
            return $this;
		}
	
		public function _loadModel($model=false, $alt_var=false) {
			if ($model === false 
			|| !file_exists($model)) {
				die('Invalid model loaded: "'. $model .'".');
			}
			require_once($model);
			$chunks = explode('/', $model);
			$class = array_pop($chunks);
			$chunks = explode('.', $class);
			$class = $chunks[0];
			if (is_string($alt_var)) {
				$name = $alt_var;
			} else {
    			$name = strtolower(preg_replace('/MODEL$/i', '', $class));
			}
			$this->models[$name] = new $class;
			return;
		}

		public function _loadView($view=false, $params=false) {
    		if (substr($view, 0, 1) != '/') {
        		$view = $this->dir .'/views/'. $view;
    		}
			if (!file_exists($view)) {
				die('Invalid view loaded: "'. $view .'".');
			}
			$viewer = new SizzleView();
			if (is_array($params)) {
				$viewer->assign($params);
			}
			$return = $viewer->fetch($view);
			unset($viewer);
			return $return;
		}

		public function _loadViewString($view=false, $params=false) {
			if ($view === false) {
				die('Invalid view string loaded.');
			}
			$viewer = new SizzleView();
			if (is_array($params)) {
				$viewer->assign($params);
			}
			$return = $viewer->fetch('string:'.$view);
			unset($viewer);
			return $return;
		}
	
	}

?>