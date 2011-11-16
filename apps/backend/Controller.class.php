<?php

	class BackendApp extends BaseApp {
		
		public function index($req_applet=false, $req_action=false, $req_id=false) {
			// Authenticate.
			if (!$this->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			// Load the dashboard or a view, using passed data.
			$params = array();
			if ($req_applet !== false) {
    			$managed_views = $this->config->managed_views;
    			foreach ($managed_views as $k => $v) {
        			foreach ($v as $k2 => $v2) {
            			if ($k2 == $req_applet) {
            				$params['applet'] = $req_applet;
            				if ($req_action !== false
            				&& isset($v2[$req_action])) {
            					$params['action'] = $req_action;
            				} else {
                				$view_uris = array_keys($v2);
                				$params['action'] = array_shift($view_uris);
            				}
                			if (is_numeric($req_id)) {
                    			$params['id'] = $req_id;
                			}
            			}
        			}
    			}
			}
			echo $this->_loadView('manage.tpl', $params);
			return;
		}

		public function login() {
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				return $this->models['backend']->login();
			}
			echo $this->_loadView('login.tpl');
			return;
		}

		public function logout() {
			// Authenticate.
			if (!$this->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			unset($_SESSION['sizzle_user']);
			$GLOBALS['sizzle']->utils->redirect('/backend/login', 'Logout successful!');
			return;
		}

		public function support() {
			// Authenticate.
			if (!$this->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			$handle = fopen('http://api.thesizzlecms.com/faqs/', 'rb');
			$faqs = stream_get_contents($handle);
			$faqs = json_decode($faqs, true);
			fclose($handle);
			echo $this->_loadView('support.tpl', array('faqs' => $faqs));
			return;
		}

		public function upgrade() {
			// Authenticate.
			if (!$this->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				return $this->models['backend']->upgrade();
			}
			echo $this->_loadView('upgrade.tpl');
			return;
		}
		
	}

?>