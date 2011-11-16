<?php

	class BaseApp extends BaseController {

		public $id;
		public $dir;

		public function __construct() {
    		parent::__construct();
    		$this->id = strtolower(preg_replace('/APP$/i', '', get_class($this)));
    		$this->dir = dirname(dirname(__FILE__)).'/apps/'. $this->id;
			if (file_exists($this->dir .'/models/'. ucfirst($this->id) .'Model.class.php')) {
                $this->_loadModel($this->dir .'/models/'. ucfirst($this->id) .'Model.class.php');
			}
            return $this;
		}

		public function ajax() {
			$return = array('error' => true, 'message' => 'Invalid request.');
            $result = json_decode($this->_remap('api/index/'. implode('/', array_slice($GLOBALS['sizzle']->request, (array_search('ajax', $GLOBALS['sizzle']->request)+1)))), true);
            if (is_array($result)) $return = $result;
			if (isset($_SESSION['redirect'])) {
				$return['redirect'] = $_SESSION['redirect'];
				unset($_SESSION['redirect']);
			}
			echo json_encode($return);
    		return;
		}
		
		public function process() {
			$redirect = '/';
            $message = 'Invalid request.';
            $result = json_decode($this->_remap('api/index/'. implode('/', array_slice($GLOBALS['sizzle']->request, (array_search('process', $GLOBALS['sizzle']->request)+1)))), true);
            if (is_array($result)) $message = $result['message'];
			if (isset($_SESSION['redirect'])) {
				$redirect = $_SESSION['redirect'];
				unset($_SESSION['redirect']);
			}
			$GLOBALS['sizzle']->utils->redirect($redirect, $message);
    		return;
		}

		public function _remap($alt_url=false) {
    		if (!is_string($alt_url)) {
        		die('Invalid remap URL "'. $alt_url .'".');
    		}
			ob_start();
    		$GLOBALS['sizzle']->dispatch(explode('/', $alt_url));
    		$result = ob_get_clean();
    		return $result;
		}
	
	}

?>