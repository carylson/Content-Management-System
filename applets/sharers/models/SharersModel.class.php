<?php

	class SharersModel extends BaseAppletModel {
	
		public function add() {
			// Make sure all required info was submitted.
			$errors = array();
			foreach (array(
				'name'=>'Name',
				'content'=>'Code snippet'
			)  as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					$errors[] = 'Field <em>'. $v .'</em> was missing or invalid.';
				}
			}
			if (count($errors)) {
    			return '<strong>Error: </strong><br/>'. implode('<br/>', $errors);
			}
			// Add the new analytic.
			$query = $GLOBALS['sizzle']->database->prepare('INSERT INTO sharers (name, content) VALUES (?, ?)');
			$query->execute(array($_POST['name'], $_POST['content']));
            // Return.
			$_SESSION['redirect'] = '/backend/sharers/view';
			return 'Sharer added successfully.';
		}
		
		public function delete() {
			// Make sure all required info was submitted.
			foreach (array(
				'id'=>'Sharer ID'
			) as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					return '<strong>Error: </strong><em>'. $v .'</em> was missing or invalid.';
				}
			}
			// Make sure the sharers exists.
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM sharers WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
			if ($query->fetch() === false) {
				return '<strong>Error: </strong>Some required information was missing or invalid.';
			}
			// Delete the existing analytic.
			$query = $GLOBALS['sizzle']->database->prepare('DELETE FROM sharers WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
            // Return.
			$_SESSION['redirect'] = '/backend/sharers/view';
			return 'Sharer deleted successfully.';
		}

		public function edit() {
			// Make sure all required info was submitted.
			$errors = array();
			foreach (array(
				'name'=>'Name',
				'content'=>'Code snippet',
				'id'=>'Sharer ID'
			)  as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					$errors[] = 'Field <em>'. $v .'</em> was missing or invalid.';
				}
			}
			if (count($errors)) {
    			return '<strong>Error: </strong><br/>'. implode('<br/>', $errors);
			}
			// Make sure the sharers exists.
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM sharers WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
			if ($query->fetch() === false) {
				return '<strong>Error: </strong>Some required information was missing or invalid.';
			}
			// Update the existing analytic.
			$query = $GLOBALS['sizzle']->database->prepare('UPDATE sharers SET name = ?, content = ? WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['name'], $_POST['content'], $_POST['id']));
            // Return.
			return 'Sharer edited successfully.';
		}

	}

?>