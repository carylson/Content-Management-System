<?php

	class SizzleDebug {
	
		public $mode;
		private $log;
		private $start_microtime;
		
		public function __construct($mode=false) {
    		$this->start_microtime = microtime(true);
			$this->setMode($mode);
			$this->setErrorHandler();
			return $this;
		}
		
		public function handleError($errno, $errstr, $errfile, $errline) {
			$admin_email = 'aaronc+sizzle@noein.com';
			$error_log = 'logs/error.log';
			$timestamp = date('m/d/Y h:i:s a');
			if (!file_exists(dirname(dirname(__FILE__)).'/'.$error_log)) {
    			touch(dirname(dirname(__FILE__)).'/'.$error_log);
			}
			switch ($errno) {
				case E_USER_ERROR:
					$errmsg = "*\n* http://" . $_SERVER['SERVER_NAME'] . " Fatal Error:\n*\n\nTimestamp:\t$timestamp\nCode:\t\t$errno\nFile:\t\t$errfile\nLine:\t\t$errline\nError:\t\t$errstr\n\n";
					error_log($errmsg, 1, $admin_email);
					error_log($errmsg, 3, $error_log);
					break;
				case E_USER_WARNING:
					$errmsg = "*\n* http://" . $_SERVER['SERVER_NAME'] . " Warning:\n*\n\nTimestamp:\t$timestamp\nCode:\t\t$errno\nFile:\t\t$errfile\nLine:\t\t$errline\nError:\t\t$errstr\n\n";
					error_log($errmsg, 3, $error_log);
					break;
				case E_USER_NOTICE:
					$errmsg = "*\n* http://" . $_SERVER['SERVER_NAME'] . " Notice:\n*\n\nTimestamp:\t$timestamp\nCode:\t\t$errno\nFile:\t\t$errfile\nLine:\t\t$errline\nError:\t\t$errstr\n\n";
					error_log($errmsg, 3, $error_log);
					break;
				default:
					$errmsg = "*\n* http://" . $_SERVER['SERVER_NAME'] . " Unknown Error:\n*\n\nTimestamp:\t$timestamp\nCode:\t\t$errno\nFile:\t\t$errfile\nLine:\t\t$errline\nError:\t\t$errstr\n\n";
					error_log($errmsg, 3, $error_log);
					break;
			}			
			return false; // return false means execute PHP's internal error handler too!
		}
		
		public function injectDebugger() {
			echo '
			<style type="text/css">
			.sizzle-debug { width:750px; top:10px; right:10px; position:fixed; background:#fff; border:3px solid #6AB000; z-index:99999; font-size:12px; }
			.sizzle-debug span { float:left; position:relative; top:10px; left:10px; font-weight:bold; }
			.sizzle-debug ul { width:600px; float:right; margin:0; padding:0; position:relative; }
			.sizzle-debug ul li { float:left; list-style:none; margin-left:10px; }
			.sizzle-debug ul a { display:block; padding:10px 0; color:#666; text-decoration:none; }
			.sizzle-debug ul ul { width:600px; max-height:450px; overflow:auto; float:none; position:absolute; top:90%; right:10px; background:#fff; padding:10px; border:1px solid #6AB000; border-top:none; font-size:10px; display:none; }
			.sizzle-debug ul ul li { float:none; margin-left:0; }
			.sizzle-debug ul li:hover ul { display:block; }
			</style>
			<div class="sizzle-debug">
				<span>Sizzle Debug:</span>
				<ul>
			';
			$this->log('Final runtime');
            foreach (array(
				'$_GET' => $_GET, 
				'$_POST' => $_POST, 
				'$_SESSION' => $_SESSION, 
				'$_SERVER' => $_SERVER,
				'$sizzle' => $GLOBALS['sizzle'],
				'->Apps' => $GLOBALS['sizzle']->apps,
				'->Applets' => $GLOBALS['sizzle']->applets,
				'->Request' => $GLOBALS['sizzle']->request,
				'->Debug->Log' => $GLOBALS['sizzle']->debug->log				
			) as $k=>$v) {
				echo '
				<li>
    				<a href="#">' . $k . '</a>
    				<ul>
        				<li>
            				<p><strong>'. $k .'</strong></p>
                ';
				echo '<pre>';
    			ob_start();
				var_dump($v);
    			echo ob_get_clean();
				echo '</pre>';
                echo '
        				</li>
    				</ul>
				</li>
				';
			}
			echo '
				</ul>
				<div style="clear:both;"></div>
			</div>
			';
			return;
		}
		
		public function log($message='Event', $microtime=false) {
    		if (!is_array($this->log)) {
        		$this->log = array();    		
    		}
    		if ($microtime === false) {
        		$microtime = (microtime(true)-$this->start_microtime);
    		}
    		$this->log[] = $message .', +'. $microtime;
    		return;
		}
		
		private function setErrorHandler() {
			set_error_handler(array($this, 'handleError'));
			return;
		}
		
		private function setMode($mode) {
			if (!is_bool($mode)) {
			    die('Invalid debugger mode "'. $mode .'".');
			}
			$this->mode = $mode;
			return;
		}
		
	}

?>