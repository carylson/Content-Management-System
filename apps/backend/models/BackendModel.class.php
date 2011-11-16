<?php

	class BackendModel extends BaseAppModel {

		public function authenticate() {
    		//return true;
    		//return false;
			if (!isset($_SESSION['sizzle_user']) 
			|| $_SESSION['sizzle_user'] === false) {
    			return false;
			}
			return true;
		}

		public function authorize($applet=false, $action=false) {
    		//return true;
    		//return false;
			$sizzle_user = $GLOBALS['sizzle']->applets['users']->models['users']->fetch($_SESSION['sizzle_user']['id']);
            if ($applet === false
            || $action === false
            || !isset($sizzle_user['access'][$applet]) 
            || !in_array($action, $sizzle_user['access'][$applet])) {
    			return false;
            }
			return true;
		}

		public function login() {
			if ($_REQUEST['email'] == '' || $_REQUEST['password'] == '') {
				return '<strong>Error:</strong> Some required information was missing or invalid.';
			}
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM users WHERE email = ? AND password = ? LIMIT 1');
			$query->execute(array($_REQUEST['email'], $_REQUEST['password']));
			$user = $query->fetch(PDO::FETCH_ASSOC);
			if ($user === false) {
				return '<strong>Error:</strong> Invalid e-mail/password combination.';
			}
			$_SESSION['sizzle_user'] = $user;
			$this->login_ajaxplorer();
			$_SESSION['redirect'] = '/backend';
			return 'Login successful!';
		}
		
		private function login_ajaxplorer() {
        	define('AJXP_EXEC', true);
            global $AJXP_GLUE_GLOBALS;
            $AJXP_GLUE_GLOBALS = array();
        	$AJXP_GLUE_GLOBALS['secret'] = $_SERVER['SERVER_NAME'].'-ajaxplorer';
        	$AJXP_GLUE_GLOBALS['plugInAction'] = 'login';
        	$AJXP_GLUE_GLOBALS['login'] = array(
            	'name' => $_SESSION['sizzle_user']['email'],
            	'password' => md5($_SESSION['sizzle_user']['password']),
            	'right' => preg_match('/^staff@noein\.com$/i', $_SESSION['sizzle_user']['email']) ? 'admin' : ''
        	);
        	$AJXP_GLUE_GLOBALS['autoCreate'] = true;
           	require('common/ajaxplorer/plugins/auth.remote/glueCode.php');
    		return;
		}
		
		public function logout() {
			$this->logout_ajaxplorer();
			unset($_SESSION['sizzle_user']);
            return;		
		}
		
		private function logout_ajaxplorer() {
        	define('AJXP_EXEC', true);
            global $AJXP_GLUE_GLOBALS;
            $AJXP_GLUE_GLOBALS = array();
        	$AJXP_GLUE_GLOBALS['secret'] = $_SERVER['SERVER_NAME'].'-ajaxplorer';
        	$AJXP_GLUE_GLOBALS['plugInAction'] = 'logout';
           	require('common/ajaxplorer/plugins/auth.remote/glueCode.php');
    		return;
		}

		public function upgrade() {
    		return 'Upgrade complete!';
		}
		
	}

?>