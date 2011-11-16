<?php

	class BaseApplet extends BaseController {

		public $id;
		public $dir;

		public function __construct() {
    		parent::__construct();
    		$this->id = strtolower(preg_replace('/APPLET$/i', '', get_class($this)));
    		$this->dir = dirname(dirname(__FILE__)).'/applets/'. $this->id;
			if (file_exists($this->dir .'/models/'. ucfirst($this->id) .'Model.class.php')) {
                $this->_loadModel($this->dir .'/models/'. ucfirst($this->id) .'Model.class.php');
			}
            return $this;
		}
	
	}

?>