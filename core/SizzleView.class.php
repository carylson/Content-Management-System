<?php

	require_once('common/smarty/Smarty.class.php');
	
	class SizzleView extends Smarty {
	
		public function __construct() {
			
			/* Initialize the parent class.  Required by Smarty! */
			
			parent::__construct();
			
			/* Custom configuration for Sizzle!: */
			
			$this->setTemplateDir('templates');
			$this->setCompileDir('cache');
			$this->setConfigDir('common/smarty/configs');
			$this->setCacheDir('cache');
			$this->debugging = true;
			$this->error_reporting = error_reporting();
			$this->caching = Smarty::CACHING_LIFETIME_CURRENT;
			
			// Disable caching in dev.?
			$this->force_compile = true;

			// Get latest version from API...
			$handle = fopen('http://api.thesizzlecms.com/version/', 'rb');
			$latest_version = stream_get_contents($handle);
			$latest_version = json_decode($latest_version, true);
			$latest_version = $latest_version['version'];
			fclose($handle);
			
			// Get system "marketing" message from API...
			$handle = fopen('http://api.thesizzlecms.com/message/', 'rb');
			$system_message = stream_get_contents($handle);
			$system_message = json_decode($system_message, true);
			$system_message = $system_message['message'];
			fclose($handle);

			/* Assign template vars: */

			$params = array(
    			'sizzle' => $GLOBALS['sizzle'],
    			'current_version' => (float)$GLOBALS['sizzle']->config->version,
    			'latest_version' => (float)$latest_version
            );
			
			if (isset($_SESSION['message'])) {
				$params['message'] = $_SESSION['message'];
				unset($_SESSION['message']);
			}
			
			if (isset($system_message)) {
				$params['sysmessage'] = $system_message;
			}
			
			$this->assign($params);
			
            /* Finally, register Sizzle Applet helper functions.  Note: requires magic __call() method shenanigans. */
            
            foreach ($GLOBALS['sizzle']->applets as $applet_id => $applet) {
                if (is_array($applet->config->helpers)) {
                    foreach ($applet->config->helpers as $helper_id => $helper) {
                        $this->registerPlugin('function', $helper_id, array($this, 'map_helper_'.$helper_id));
                    }
                }
            }

		}

		public function __call($name, $args) {

    		if (preg_match('/^map_helper_/', $name)) {
        		$helper_id = implode('_', array_slice(explode('_', $name), 2));
        		$applet = $GLOBALS['sizzle']->helpers[$helper_id]['applet'];
        		$method = $GLOBALS['sizzle']->helpers[$helper_id]['method'];
                $params = array();
                foreach ($GLOBALS['sizzle']->helpers[$helper_id]['params'] as $param_id => $param) {
                    if (!empty($args[0][$param_id])
                    && preg_match('/^'.$param.'$/ui', $args[0][$param_id])) {
                        $params[$param_id] = $args[0][$param_id];
                    }
                }
                return call_user_func_array(array($GLOBALS['sizzle']->applets[$applet], $method), $params);
    		}

    		parent::__call($name, $args);

		}
		
	}

?>