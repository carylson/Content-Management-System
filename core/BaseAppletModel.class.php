<?php

	class BaseAppletModel extends BaseModel {

		private $id;
		private $dir;

		public function __construct() {
    		$this->id = strtolower(preg_replace('/MODEL$/i', '', get_class($this)));
    		//$this->dir = dirname(dirname(__FILE__)).'/applets/'. $this->id .'/models';
            return $this;
		}
	
		public function fetch($id=false) {
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM '. $this->id .' WHERE id = ? LIMIT 1');
			$query->execute(array($id));
			return $query->fetch(PDO::FETCH_ASSOC);
		}
		
		public function fetchAll() {
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM '. $this->id .' ORDER BY id');
			$query->execute();
			return $query->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public function fetchOptions() {
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM '. $this->id .'_options LIMIT 1');
			$query->execute();
			return $query->fetch(PDO::FETCH_ASSOC);
		}
	
	}

?>