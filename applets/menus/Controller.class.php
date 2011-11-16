<?php

	class MenusApplet extends BaseApplet {
	
		public function add() {
			// Authenticate w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			// Authorize w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('menus', 'add')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['menus']->add();
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
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('menus', 'delete')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['menus']->delete();
			} else {
    			// Load view.
    			$view = '/views/delete.tpl';
    			if ($id === false 
    			|| $this->models['menus']->fetch($id) === false) {
    				$view = '/views/select.tpl';
    			}
    			$params['data'] = $this->models['menus']->fetch($id);
    			return $this->_loadView(dirname(__FILE__) . $view, $params);
			}
		}
		
		public function edit($id=false) {
			// Authenticate w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			// Authorize w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('menus', 'edit')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['menus']->edit();
			} else {
    			// Load view.
    			$view = '/views/edit.tpl';
    			if ($id === false 
    			|| $this->models['menus']->fetch($id) === false) {
    				$view = '/views/select.tpl';
    			}
    			$params['data'] = $this->models['menus']->fetch($id);
    			return $this->_loadView(dirname(__FILE__) . $view, $params);
			}
		}
		
		public function view() {
			// Authenticate w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			// Authorize w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('menus', 'view')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			// Load view.
			return $this->_loadView(dirname(__FILE__) .'/views/view.tpl');
		}
		
		public function output($id=false) {
			$database = $GLOBALS['sizzle']->database;
			if (is_numeric($id)) {
				$query = $database->prepare('SELECT * FROM menus WHERE id = ? LIMIT 1');
				$query->execute(array($id));
			} else {
				$query = $database->prepare('SELECT * FROM menus LIMIT 1');
				$query->execute();
			}
			$menu = $query->fetch();
			$id = $menu['id'];
			$output = '';
			if (!empty($menu)) {
				$menu = json_decode($menu['menu'], true);
				$output = $this->getMenuStructure($menu);
			}
			if (isset($_SESSION['sizzle_user'])) {
                $output = '
    			<div class="sizzle-content">
        			'. $output .'
        			<a class="sizzle-content-edit" href="/backend/menus/edit/'. $id .'"></a>
                </div>
    			';
			}
			return $output;
		}
		
		private function getMenuStructure($menu, $depth=1) {
			$database = $GLOBALS['sizzle']->database;
			$output = '<ul class="menu depth-'. $depth .'">';
			foreach ($menu as $item) {
				$style = '';
				if ($item['hidden'] == 'true') {
					$style = 'display:none;';
				}
				if (!empty($item['id'])) {
					$query = $database->prepare('SELECT * FROM content WHERE id = ? LIMIT 1');
					$query->execute(array($item['id']));
					$content = $query->fetch();
					$name = $content['name'];
					$url = $content['url'];
				}
				if (!empty($item['alt_name'])) {
					$name = $item['alt_name'];
				}
				if (!empty($item['alt_url'])) {
					$url = $item['alt_url'];
				}
				$output .= '<li style="'. $style .'"><a href="'. $url .'">'. $name .'</a>';
				$d = $depth;
				if (!empty($item['children'])) {
					$depth++;
					$output .= $this->getMenuStructure($item['children'], $depth);
				}
				$depth = $d;
				$output .= '</li>';
			}
			$output .= '</ul>';
			return $output;
		}
		
		public function outputBreadcrumbs($id=false) {
			$database = $GLOBALS['sizzle']->database;
			if (is_numeric($id)) {
				$query = $database->prepare('SELECT * FROM menus WHERE id = ? LIMIT 1');
				$query->execute(array($id));
			} else {
				$query = $database->prepare('SELECT * FROM menus LIMIT 1');
				$query->execute();
			}
			$menu = $query->fetch();
			$output = '';
			if (!empty($menu)) {
				$menu = json_decode($menu['menu'], true);
				$query = $database->prepare('SELECT * FROM content WHERE url = ? LIMIT 1');
				$query->execute(array(implode('/', $GLOBALS['sizzle']->request)));
				$page = $query->fetch();
				$content_options = $GLOBALS['sizzle']->applets['content']->models['content']->fetchOptions();
				$query = $database->prepare('SELECT * FROM content WHERE id = ? LIMIT 1');
				$query->execute(array($content_options['homepage']));
				$homepage = $query->fetch();
				$breadcrumbs = $this->getMenuBreadcrumbStructure($page['id'], $menu);
				if (is_array($breadcrumbs)) {
    				array_unshift($breadcrumbs, array('id'=>$homepage['id']));
				} else {
    				$breadcrumbs = array('id'=>$homepage['id']);
				}
				$output = array();
				foreach ($breadcrumbs as $breadcrumb) {
    				if (!empty($breadcrumb['alt_name']) && !empty($breadcrumb['alt_url'])) {
        				$name = $breadcrumb['alt_name'];
        				$url = $breadcrumb['alt_url'];
    				}
    				if (!empty($breadcrumb['id'])) {
        				$query = $database->prepare('SELECT * FROM content WHERE id = ? LIMIT 1');
        				$query->execute(array($breadcrumb['id']));
        				$page = $query->fetch();
        				if ($page !== false) {
            				$name = $page['name'];
            				$url = $page['url'];
        				}
    				}
    				if (!empty($name) && !empty($url)) {
        				$output[] = '<a href="'. $url .'">'. $name .'</a>';
    				}
				}
				$output = implode(' > ', $output);
			}
            return $output;
		}

        private function getMenuBreadcrumbStructure($needle, $haystack, $path=array()) {
            if (!is_array($haystack)) {
                return false;
            }
            foreach ($haystack as $k=>$v) {
                if (isset($v['children']) && $subPath = $this->getMenuBreadcrumbStructure($needle, $v['children'], $path)) {
                    $path = array_merge($path, array($v), $subPath);
                    return $path;
                } elseif ($v['id'] === $needle) {
                    $path[] = $v;
                    return $path;
                }
            }
            return false;
        }

    }

?>