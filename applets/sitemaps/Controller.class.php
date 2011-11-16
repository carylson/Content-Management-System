<?php

	class SitemapsApplet extends BaseApplet {
	
		public function add() {
			// Authenticate w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			// Authorize w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('sitemaps', 'add')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['sitemaps']->add();
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
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('sitemaps', 'delete')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['sitemaps']->delete();
			} else {
    			// Load view.
    			$view = '/views/delete.tpl';
    			if ($id === false 
    			|| $this->models['sitemaps']->fetch($id) === false) {
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
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('sitemaps', 'edit')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['sitemaps']->edit();
			} else {
    			// Load view.
    			$view = '/views/edit.tpl';
    			if ($id === false 
    			|| $this->models['sitemaps']->fetch($id) === false) {
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
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('sitemaps', 'view')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			// Load view.
			return $this->_loadView(dirname(__FILE__) .'/views/view.tpl');
		}

		public function output($id=false) {
			$database = $GLOBALS['sizzle']->database;
			if (is_numeric($id)) {
				$query = $database->prepare('SELECT * FROM sitemaps WHERE id = ? LIMIT 1');
				$query->execute(array($id));
			} else {
				$query = $database->prepare('SELECT * FROM sitemaps LIMIT 1');
				$query->execute();
			}
			$sitemap = $query->fetch();
			$output = '';
			if ($sitemap !== false) {
    			$sitemap['content'] = json_decode($sitemap['content'], true);
            	$output = '<ul>';
            	foreach ($sitemap['content'] as $id => $enabled) {
            		if (!$enabled) { continue; }
            		$query = $database->prepare('SELECT * FROM content WHERE id = ? LIMIT 1');
            		$query->execute(array($id));
            		$page = $query->fetch();
            		if ($page !== false) {
            			$output .= '<li><a href="/' . $page['url'] .'">'. $page['name'] .'</a></li>';
            		}
            	}
            	$output .= '</ul>';
			}
			return $output;
		}

		public function outputXml($id=false) {
			$database = $GLOBALS['sizzle']->database;
			if (is_numeric($id)) {
				$query = $database->prepare('SELECT * FROM sitemaps WHERE id = ? LIMIT 1');
				$query->execute(array($id));
			} else {
				$query = $database->prepare('SELECT * FROM sitemaps LIMIT 1');
				$query->execute();
			}
			$sitemap = $query->fetch();
			$output = '';
			if ($sitemap !== false) {
    			$sitemap['content'] = json_decode($sitemap['content'], true);
    			header('Content-Type: text/xml; charset: utf-8');
    			$output = '<?xml version="1.0" encoding="UTF-8"?>';
    			$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    			foreach ($sitemap['content'] as $id => $enabled) {
        			if (!$enabled) { continue; }
        			$query = $database->prepare('SELECT * FROM content WHERE id = ? LIMIT 1');
        			$query->execute(array($id));
        			$page = $query->fetch();
        			if ($page !== false) {
            			$output .= '<url><loc>http://'. $_SERVER['SERVER_NAME'] . '/' . $page['url'] .'</loc></url>';
        			}
    			}
    			$output .= '</urlset>';
			}
			return $output;
		}
	
	}

?>