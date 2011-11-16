<?php

	class UsersModel extends BaseAppletModel {
	
		public function add() {
			// Make sure all required info was submitted.
			$errors = array();
			foreach (array(
				'email'=>'E-mail address',
				'password'=>'Password',
				'password_confirm'=>'Password confirmation'
			) as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					$errors[] = 'Field <em>'. $v .'</em> was missing or invalid.';
				}
			}
			if (count($errors)) {
    			return '<strong>Error: </strong><br/>'. implode('<br/>', $errors);
			}
			// Make sure password & password_confirm match.
			if ($_POST['password'] != $_POST['password_confirm']) {
				return '<strong>Error: </strong>passwords do not match.';
			}
			// Make sure the email is unique.	
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
			$query->execute(array($_POST['email']));
			if ($query->fetch() !== false) {
				return '<strong>Error: </strong>E-mail not unique.';
			}
			//  Make sure you can't grant permissions you don't have.
			$sizzle_user = $GLOBALS['sizzle']->applets['users']->models['users']->fetch($_SESSION['sizzle_user']['id']);
			$access = array();
			if (isset($_POST['access']) && is_array($_POST['access'])) {
    			foreach ($_POST['access'] as $applet=>$views) {
                    foreach ($views as $view) {
                        // Authorize w/ backend app.
                        if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize($applet, $view)) {
                            $_POST['access'][$applet] = array_diff($_POST['access'][$applet], array($view));
                        }
                    }
                    if (empty($_POST['access'][$applet])) {
                        unset($_POST['access'][$applet]);
                    }
    			}
    			$access = $_POST['access'];
			}
			// Add the new user.
			$query = $GLOBALS['sizzle']->database->prepare('INSERT INTO users (first_name, last_name, password, email, access) VALUES (?, ?, ?, ?, ?)');
			$query->execute(array($_POST['first_name'], $_POST['last_name'], $_POST['password'], $_POST['email'], json_encode($access)));
            // Return.
			$_SESSION['redirect'] = '/backend/users/view';
			return 'User added successfully.';
		}
		
		public function delete() {
			// Make sure all required info was submitted.
			foreach (array(
				'id'=>'User ID'
			) as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					return '<strong>Error: </strong><em>'. $v .'</em> was missing or invalid.';
				}
			}
			// Make sure the user exists.
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
			if ($query->fetch() === false) {
				return '<strong>Error: </strong>Some required information was missing or invalid.';
			}
			// Make sure you can't delete yourself.
			if ($_POST['id'] == $_SESSION['sizzle_user']['id']) {
				return '<strong>Error: </strong>You cannot delete yourself.';
			}
			// Delete the existing user.
			$query = $GLOBALS['sizzle']->database->prepare('DELETE FROM users WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
            // Return.
			$_SESSION['redirect'] = '/backend/users/view';
			return 'User deleted successfully.';
		}

		public function edit() {
			// Make sure all required info was submitted.
			$errors = array();
			foreach (array(
				'email'=>'E-mail address',
				'password'=>'Password',
				'password_confirm'=>'Password confirmation',
				'id'=>'User ID'
			) as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					$errors[] = 'Field <em>'. $v .'</em> was missing or invalid.';
				}
			}
			if (count($errors)) {
    			return '<strong>Error: </strong><br/>'. implode('<br/>', $errors);
			}
			// Make sure the user exists.
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
			if ($query->fetch() === false) {
				return '<strong>Error: </strong>Some required information was missing or invalid.';
			}
			// Make sure password & password_confirm match.
			if ($_POST['password'] != $_POST['password_confirm']) {
				return '<strong>Error: </strong>Passwords do not match.';
			}
			// Make sure the email is unique.	
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM users WHERE email = ? AND id != ? LIMIT 1');
			$query->execute(array($_POST['email'], $_POST['id']));
			if ($query->fetch() !== false) {
				return '<strong>Error: </strong>E-mail not unique.';
			}
			//  Make sure you can't grant permissions you don't have.
			$sizzle_user = $GLOBALS['sizzle']->applets['users']->models['users']->fetch($_SESSION['sizzle_user']['id']);
			$access = array();
			if (isset($_POST['access']) && is_array($_POST['access'])) {
    			foreach ($_POST['access'] as $applet=>$views) {
                    foreach ($views as $view) {
                        // Authorize w/ backend app.
                        if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize($applet, $view)) {
                            $_POST['access'][$applet] = array_diff($_POST['access'][$applet], array($view));
                        }
                    }
                    if (empty($_POST['access'][$applet])) {
                        unset($_POST['access'][$applet]);
                    }
    			}
    			$access = $_POST['access'];
			}
			// Update the existing user.
			$query = $GLOBALS['sizzle']->database->prepare('UPDATE users SET first_name = ?, last_name = ?, password = ?, email = ?, access = ? WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['first_name'], $_POST['last_name'], $_POST['password'], $_POST['email'], json_encode($access), $_POST['id']));
            // Return.
			return 'User edited successfully.';
		}
		
		public function fetch($id=false) {
			$return = parent::fetch($id);
			if ($return) {
    			$return['access'] = json_decode($return['access'], true);
			}
			return $return;
		}
		
	}

?>