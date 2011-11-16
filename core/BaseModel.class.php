<?php

	class BaseModel {

		public function __call($method, $args) {
			die('Model "'. $this->id .'" does not have a "'. $method .'" method.');
		}

	}

?>