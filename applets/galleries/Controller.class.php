<?php

	class GalleriesApplet extends BaseApplet {
	
		public function add() {
			// Authenticate w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			// Authorize w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('galleries', 'add')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['galleries']->add();
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
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('galleries', 'delete')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['galleries']->delete();
			} else {
    			// Load view.
    			$view = '/views/delete.tpl';
    			if ($id === false 
    			|| $this->models['galleries']->fetch($id) === false) {
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
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('galleries', 'edit')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['galleries']->edit();
			} else {
    			// Load view.
    			$view = '/views/edit.tpl';
    			if ($id === false 
    			|| $this->models['galleries']->fetch($id) === false) {
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
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('galleries', 'view')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			// Load view.
			return $this->_loadView(dirname(__FILE__) .'/views/view.tpl');
		}

		public function output($id=false) {
			$database = $GLOBALS['sizzle']->database;
			if (is_numeric($id)) {
				$query = $database->prepare('SELECT * FROM galleries WHERE id = ? LIMIT 1');
				$query->execute(array($id));
			} else {
				$query = $database->prepare('SELECT * FROM galleries LIMIT 1');
				$query->execute();
			}
			$gallery = $query->fetch();
			$gallery['images'] = json_decode($gallery['images'], true);
			$output = '';
			if (!empty($gallery)) {
        		if (count($gallery['images']) > 0) {
            		$output .= '
            		<link rel="stylesheet" type="text/css" href="/common/jquery.fancybox/jquery.fancybox-1.3.4.css"/>
            		<script type="text/javascript" src="/common/jquery.fancybox/jquery.fancybox-1.3.4.pack.js"></script>
            		<script type="text/javascript" src="/common/jquery.fancybox/jquery.easing-1.3.pack.js"></script>
            		<script type="text/javascript">
        			$(document).ready(function(){	
        				$(\'a[rel="gallery"]\').fancybox({ titlePosition: \'over\' });
        			});	
            		</script>
            		';
        			foreach ($gallery['images'] as $image) {
        				$caption = array();
        				if (!empty($image['caption'])) $caption[] = $image['caption'];
        				if (!empty($image['date'])) $caption[] = $image['date'];
        				$caption = implode(', ', $caption);
        				$output .= '
        				<a href="/uploads/galleries/'. $image['image'] .'" target="_blank" title="'. $caption .'" rel="gallery">
                			<img src="/common/phpthumb/?src=/uploads/galleries/'. $image['image'] .'&x=128&y=128"/>
        				</a>
        				';
        			}
        		}
			}
			return $output;
		}

		public function outputCarousel($id=false) {
			$database = $GLOBALS['sizzle']->database;
			if (is_numeric($id)) {
				$query = $database->prepare('SELECT * FROM galleries WHERE id = ? LIMIT 1');
				$query->execute(array($id));
			} else {
				$query = $database->prepare('SELECT * FROM galleries LIMIT 1');
				$query->execute();
			}
			$gallery = $query->fetch();
			$gallery['images'] = json_decode($gallery['images'], true);
			$output = '';
			if (!empty($gallery)) {
        		if (count($gallery['images']) > 0) {
            		$output .= '
                    <link rel="stylesheet" href="/common/jquery.nivoslider/nivo-slider.css" type="text/css" media="screen"/>
                    <link rel="stylesheet" href="/common/jquery.nivoslider/themes/default/default.css" type="text/css" media="screen" />
                    <script src="/common/jquery.nivoslider/jquery.nivo.slider.pack.js" type="text/javascript"></script>
            		<script type="text/javascript">
        			$(document).ready(function(){	
        				$(\'div.nivoSlider\').nivoSlider();
        			});	
            		</script>
                    <div class="slider-wrapper theme-default">
                        <div class="ribbon"></div>
                        <div id="slider" class="nivoSlider">
            		';
        			foreach ($gallery['images'] as $image) {
        				$caption = array();
        				if (!empty($image['caption'])) $caption[] = $image['caption'];
        				if (!empty($image['date'])) $caption[] = $image['date'];
        				$caption = implode(', ', $caption);
        				$output .= '<img src="/uploads/galleries/'. $image['image'] .'" title="'. $caption .'"/>';
        			}
        			$output .= '
            			</div>
        			</div>
        			';
        		}
			}
			return $output;
		}

		public function outputRandom($id=false) {
			$database = $GLOBALS['sizzle']->database;
			if (is_numeric($id)) {
				$query = $database->prepare('SELECT * FROM galleries WHERE id = ? LIMIT 1');
				$query->execute(array($id));
			} else {
				$query = $database->prepare('SELECT * FROM galleries LIMIT 1');
				$query->execute();
			}
			$gallery = $query->fetch();
			$gallery['images'] = json_decode($gallery['images'], true);
			$output = '';
			if (!empty($gallery)) {
        		if (count($gallery['images']) > 0) {
        			if (empty($_SESSION['sizzle-galleries-random'])) {
            			$_SESSION['sizzle-galleries-random'] = $gallery['images'];
        			}
        			if (!empty($_SESSION['sizzle-galleries-random'])) {
            			shuffle($_SESSION['sizzle-galleries-random']);
            			$image = array_shift($_SESSION['sizzle-galleries-random']);
            			$output = '<img src="/uploads/galleries/'. $image['image'] .'" alt=""/>';
        			}
        		}
			}
			return $output;
		}
	
	}

?>