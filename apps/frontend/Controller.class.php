<?php

	class FrontendApp extends BaseApp {
		
		public function blog_dot_xml() {
    		echo $GLOBALS['sizzle']->applets['blog']->outputXml();
			return;
		}
	
		public function index() {
    		$last = count($GLOBALS['sizzle']->request) ? max($GLOBALS['sizzle']->request) : '' ;
    		$mobi_pattern = '/\.MOBI$/i';
    		if (preg_match($mobi_pattern, $last)) {
        		$last = preg_replace($mobi_pattern, '', $last);
        		if (empty($last)) {
            		array_pop($GLOBALS['sizzle']->request);
        		} else {
            		$GLOBALS['sizzle']->request[(count($GLOBALS['sizzle']->request)-1)] = $last;
        		}
    			echo $this->_loadView($GLOBALS['sizzle']->dir .'/'. $this->config->mobile_template);
    		} else {
    			echo $this->_loadView($GLOBALS['sizzle']->dir .'/'. $this->config->template);
    		}
			return;
		}
		
		public function news_dot_xml() {
    		echo $GLOBALS['sizzle']->applets['news']->outputXml();
			return;
		}
		
		public function sitemap_dot_xml($id=false) {
    		if ($id !== false) {
        		echo $GLOBALS['sizzle']->applets['sitemaps']->outputXml($id);
    		} else {
        		echo $GLOBALS['sizzle']->applets['sitemaps']->outputXml();
    		}
			return;
		}
		
	}

?>