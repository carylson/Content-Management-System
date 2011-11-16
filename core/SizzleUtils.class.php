<?php

	class SizzleUtils {

		public function redirect($url=false, $message=false) {
			if ($url === false) {
				$url = '/';
			}
			if ($message !== false) {
				$_SESSION['message'] = $message;
			}
			header('location: '. $url);
			exit;
		}
	
		public function email($to, $from, $subject, $message) {
			$message = '
			<h2>'. $subject .'</h2>
			'. stripslashes($message) .'
            <p>&nbsp;</p>
            <hr/>
            <p><small>'. date("F j, Y, g:i a") .'<br/>Automated e-mail sent on behalf of <a href="http://'. $_SERVER['SERVER_NAME'] .'/">http://'. $_SERVER['SERVER_NAME'] .'/</a></small></p>
            <p><small><a href="http://www.thesizzlecms.com/">Powered by Sizzle! CMS</a></small></p>
			';
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '.$from. "\r\n";
			$headers .= 'BCC: aaronc+sizzle@noein.com' . "\r\n";
			return mail($to, $subject, $message, $headers);
		}

	}

?>
