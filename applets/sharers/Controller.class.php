<?php

	class SharersApplet extends BaseApplet {
	
		public function add() {
			// Authenticate w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			// Authorize w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('sharers', 'add')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['sharers']->add();
			} else {
    			// Load view.
    			return $this->_loadView(dirname(__FILE__) .'/views/add.tpl');
			}
		}
		
		public function delete($id=false) {
			// Authenticate w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			// Authorize w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('sharers', 'delete')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['sharers']->delete();
			} else {
    			// Load view.
    			$view = '/views/delete.tpl';
    			if ($id === false 
    			|| $this->models['sharers']->fetch($id) === false) {
    				$view = '/views/select.tpl';
    			}
    			return $this->_loadView(dirname(__FILE__) . $view);
			}
		}
		
		public function edit($id=false) {
			// Authenticate w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			// Authorize w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('sharers', 'edit')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['sharers']->edit();
			} else {
    			// Load view.
    			$view = '/views/edit.tpl';
    			if ($id === false 
    			|| $this->models['sharers']->fetch($id) === false) {
    				$view = '/views/select.tpl';
    			}
    			return $this->_loadView(dirname(__FILE__) . $view);
			}
		}
		
		public function view() {
			// Authenticate w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			// Authorize w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('sharers', 'view')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			// Load view.
			return $this->_loadView(dirname(__FILE__) .'/views/view.tpl');
		}

		public function output($id=false) {
			$database = $GLOBALS['sizzle']->database;
			if (is_numeric($id)) {
				$query = $database->prepare('SELECT * FROM sharers WHERE id = ? LIMIT 1');
				$query->execute(array($id));
			} else {
				$query = $database->prepare('SELECT * FROM sharers LIMIT 1');
				$query->execute();
			}
			$sharer = $query->fetch();
			$output = '';
			if (!empty($sharer)) {
				$output = $sharer['content'];
			}
			return $output;
		}
	
	}

?>