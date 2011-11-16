<?php
	
	class FormsModel extends BaseAppletModel {
	
		public function add() {
			// Make sure all required info was submitted.
			$errors = array();
			foreach (array(
				'name'=>'Name',
				'to'=>'To',
				'from'=>'From',
				'captcha'=>'Captcha',
				'form'=>'Form fields'
			)  as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					$errors[] = 'Field <em>'. $v .'</em> was missing or invalid.';
				}
			}
			if (count($errors)) {
    			return '<strong>Error: </strong><br/>'. implode('<br/>', $errors);
			}
			// Add the new form.
			$query = $GLOBALS['sizzle']->database->prepare('INSERT INTO forms (name, `to`, `from`, captcha, form) VALUES (?, ?, ?, ?, ?)');
			$query->execute(array($_POST['name'], $_POST['to'], $_POST['from'], $_POST['captcha'], json_encode($_POST['form'])));
            // Return.
			$_SESSION['redirect'] = '/backend/forms/view';
			return 'Form added successfully.';
		}
		
		public function delete() {
			// Make sure all required info was submitted.
			foreach (array(
				'id'=>'Form ID'
			) as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					return '<strong>Error: </strong><em>'. $v .'</em> was missing or invalid.';
				}
			}
			// Make sure the form exists.
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM forms WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
			if ($query->fetch() === false) {
				return '<strong>Error: </strong>Some required information was missing or invalid.';
			}
			// Delete the existing form.
			$query = $GLOBALS['sizzle']->database->prepare('DELETE FROM forms WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
            // Return.
			$_SESSION['redirect'] = '/backend/forms/view';
			return 'Form deleted successfully.';
		}
		
		public function edit() {
			// Make sure all required info was submitted.
			$errors = array();
			foreach (array(
				'name'=>'Name',
				'to'=>'To',
				'from'=>'From',
				'captcha'=>'Captcha',
				'form'=>'Form fields',
				'id'=>'Form ID'
			)  as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					$errors[] = 'Field <em>'. $v .'</em> was missing or invalid.';
				}
			}
			if (count($errors)) {
    			return '<strong>Error: </strong><br/>'. implode('<br/>', $errors);
			}
			// Make sure the form exists.
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM forms WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
			if ($query->fetch() === false) {
				return '<strong>Error: </strong>Some required information was missing or invalid.';
			}
			// Update the existing form.
			$query = $GLOBALS['sizzle']->database->prepare('UPDATE forms SET name = ?, `to` = ?, `from` = ?, captcha = ?, form = ? WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['name'], $_POST['to'], $_POST['from'], $_POST['captcha'], json_encode($_POST['form']), $_POST['id']));
            // Return.
			return 'Form edited successfully.';
		}
		
		public function process($id=false) {
			$database = $GLOBALS['sizzle']->database;
			// Get an instance of the form we're working with.
			$query = $database->prepare('SELECT * FROM forms WHERE id = ? LIMIT 1');
			$query->execute(array($id));
			$form = $query->fetch();
			// Make sure form is valid.
			if ($form === false || !isset($_POST['form'. $id])) {
				return '<strong>Error: </strong>Form is missing or invalid.';
			}
			// Captcha test.
            require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/common/recaptcha/recaptchalib.php');
            $resp = recaptcha_check_answer(
                '6Lf-N8cSAAAAAGg_dwEaktFh5KQmQnmhFVFk3Ctd ',
                $_SERVER['REMOTE_ADDR'],
                $_POST['recaptcha_challenge_field'],
                $_POST['recaptcha_response_field']
            );
            if (!$resp->is_valid) {
				return '<strong>Error: </strong>Captcha incorrect.  Please try again.';
            }
            $fields = json_decode($form['form'], true);
			// Make sure all required info was submitted.
            for ($i=0; $i<count($fields); $i++) {
                if ($fields[$i]['required']) {
                    if (!isset($_POST['form'. $id][$i]) || $_POST['form'. $id][$i] == '') {
    					return '<strong>Error: </strong>Please complete <em>'. $fields[$i]['label'] .'</em> to continue.';
                    }
                }
            }
            // Send an email containing form submission.
            $subject = $form['name'] .' Form Submission';
            $message = '';
            for ($i=0; $i<count($fields); $i++) {
                if (isset($_POST['form'. $id][$i])) {
                    if (is_array($_POST['form'. $id][$i])) {
                        $_POST['form'. $id][$i] = implode(', ', $_POST['form'. $id][$i]);
                    }
                    $message .= '<p><b>'. $fields[$i]['label'] .':</b> '. $_POST['form'. $id][$i] .'</p>';
                }
            }
            $GLOBALS['sizzle']->utils->email($form['to'], $form['from'] , $subject, $message);
            // Return.
            return 'Form submitted successfully.';
		}
		
		public function fetch($id=false) {
			$return = parent::fetch($id);
			if ($return) {
    			$return['form'] = json_decode($return['form'], true);
			}
			return $return;
		}

	}

?>