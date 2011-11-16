<?php

	class UsersApplet extends BaseApplet {
	
		public function add() {
			// Authenticate w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			// Authorize w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('users', 'add')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['users']->add();
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
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('users', 'delete')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['users']->delete();
			} else {
    			// Load view.
    			$view = '/views/delete.tpl';
    			if ($id === false 
    			|| $this->models['users']->fetch($id) === false) {
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
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('users', 'edit')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['users']->edit();
			} else {
    			// Load view.
    			$view = '/views/edit.tpl';
    			if ($id === false 
    			|| $this->models['users']->fetch($id) === false) {
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
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('users', 'view')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			// Load view.
			return $this->_loadView(dirname(__FILE__) .'/views/view.tpl');
		}
	
	}

?>