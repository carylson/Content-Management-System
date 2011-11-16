<?php

	class ContentApplet extends BaseApplet {
	
		public function add() {
			// Authenticate w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			// Authorize w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('content', 'add')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['content']->add();
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
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('content', 'delete')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['content']->delete();
			} else {
    			// Load view.
    			$view = '/views/delete.tpl';
    			if ($id === false 
    			|| $this->models['content']->fetch($id) === false) {
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
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('content', 'edit')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['content']->edit();
			} else {
    			// Load view.
    			$view = '/views/edit.tpl';
    			if ($id === false 
    			|| $this->models['content']->fetch($id) === false) {
    				$view = '/views/select.tpl';
    			}
    			return $this->_loadView(dirname(__FILE__) . $view);
			}
		}

		public function options() {
			// Authenticate w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			// Authorize w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('content', 'options')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['content']->options();
			} else {
    			// Load view.
    			return $this->_loadView(dirname(__FILE__) .'/views/options.tpl');
			}
		}

		public function view() {
			// Authenticate w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			// Authorize w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('content', 'view')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			// Load view.
			return $this->_loadView(dirname(__FILE__) .'/views/view.tpl');
		}
		
		public function outputById($id=false) {
			$database = $GLOBALS['sizzle']->database;
			if (is_numeric($id)) {
				$query = $database->prepare('SELECT * FROM content WHERE id = ? LIMIT 1');
				$query->execute(array($id));
			} else {
				$query = $database->prepare('SELECT * FROM content LIMIT 1');
				$query->execute();
			}
			$content = $query->fetch();
			$output = '';
			if (!empty($content)) {
    			if (isset($_SESSION['sizzle_user'])) {
                    $content['content'] = '
        			<div class="sizzle-content">
            			'. $content['content'] .'
            			<a class="sizzle-content-edit" href="/backend/content/edit/'. $content['id'] .'"></a>
                    </div>
        			';
    			}
				$output = $this->_loadViewString($content['content']);
			}
			return $output;
		}

		public function outputByUrl($id=false) {
			$database = $GLOBALS['sizzle']->database;
			$query = $database->prepare('SELECT * FROM content_options LIMIT 1');
			$query->execute(array());
			$options = $query->fetch();
			if (empty($GLOBALS['sizzle']->request)) {
				if (is_numeric($options['homepage'])) {
					$query = $database->prepare('SELECT * FROM content WHERE id = ? LIMIT 1');
					$query->execute(array($options['homepage']));
					$content = $query->fetch();
				}
			} else {
				$query = $database->prepare('SELECT * FROM content WHERE url = ? LIMIT 1');
				$query->execute(array(implode('/', $GLOBALS['sizzle']->request)));
				//$query->execute(array(array_shift($GLOBALS['sizzle']->request)));
				$content = $query->fetch();
			}
			$output = '';
			if (empty($content)) {
                if (is_numeric($options['errorpage'])) {
    				$query = $database->prepare('SELECT * FROM content WHERE id = ? LIMIT 1');
    				$query->execute(array($options['errorpage']));
    				$content = $query->fetch();
                }
                if (empty($content)) {
                    $output = '
                    <h1>Error 404!</h1>
                    <p>&nbsp;</p>
                    <h3>The page you requested could not be found.</h3>
                    <p>Click <a href="/">here</a> to return to the homepage.</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    ';
                }
                header("HTTP/1.0 404 Not Found");
			}
			if (!empty($content)) {
    			if (isset($_SESSION['sizzle_user'])) {
                    $content['content'] = '
        			<div class="sizzle-content">
            			'. $content['content'] .'
            			<a class="sizzle-content-edit" href="/backend/content/edit/'. $content['id'] .'"></a>
                    </div>
        			';
    			}
				$output = $this->_loadViewString($content['content']);
			}
			return $output;
		}
		
		public function outputMeta($type='title') {
			$database = $GLOBALS['sizzle']->database;
			$query = $database->prepare('SELECT * FROM content_options LIMIT 1');
			$query->execute(array());
			$options = $query->fetch();
			if (empty($GLOBALS['sizzle']->request)) {
				if (is_numeric($options['homepage'])) {
					$query = $database->prepare('SELECT * FROM content WHERE id = ? LIMIT 1');
					$query->execute(array($options['homepage']));
					$content = $query->fetch();
				}
			} else {
    			$query = $database->prepare('SELECT * FROM content WHERE url = ? LIMIT 1');
    			$query->execute(array(implode('/', $GLOBALS['sizzle']->request)));
    			$content = $query->fetch();
			}
			$output = '';
			if (!empty($content)) {
				switch($type) {
				    case 'keywords':
				        $output = !empty($content['meta_keywords']) ? $content['meta_keywords'] : $options['meta_keywords'] ;
				        break;
				    case 'description':
				        $output = !empty($content['meta_description']) ? $content['meta_description'] : $options['meta_description'] ;
				        break;
				    case 'title':
				        $output = !empty($content['title']) ? $content['title'] : $options['title'] ;
				        break;
				}
			}
			return $output;
		}
	
	}

?>