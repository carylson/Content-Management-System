<?php

	class BaseAppModel extends BaseModel {

		private $id;
		private $dir;

		public function __construct() {
    		$this->id = strtolower(preg_replace('/MODEL$/i', '', get_class($this)));
    		//$this->dir = dirname(dirname(__FILE__)).'/apps/'. $this->id .'/models';
            return $this;
		}
	
	}

?>