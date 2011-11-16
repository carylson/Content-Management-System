<?php
	
	class MenusModel extends BaseAppletModel {
	
		public function add() {
			// Make sure all required info was submitted.
			$errors = array();
			foreach (array(
				'name'=>'Name',
				'menu'=>'Menu'
			) as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					$errors[] = 'Field <em>'. $v .'</em> was missing or invalid.';
				}
			}
			if (count($errors)) {
    			return '<strong>Error: </strong><br/>'. implode('<br/>', $errors);
			}
			// Add the new menu.
			$query = $GLOBALS['sizzle']->database->prepare('INSERT INTO menus (name, menu) VALUES (?, ?)');
			$query->execute(array($_POST['name'], json_encode($_POST['menu'])));
            // Return.
			$_SESSION['redirect'] = '/backend/menus/view';
			return 'Menu added successfully.';
		}
		
		public function delete() {
			// Make sure all required info was submitted.
			foreach (array(
				'id'=>'Menu ID'
			) as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					return '<strong>Error: </strong><em>'. $v .'</em> was missing or invalid.';
				}
			}
			// Make sure the menu exists.
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM menus WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
			if ($query->fetch() === false) {
				return '<strong>Error: </strong>Some required information was missing or invalid.';
			}
			// Delete the existing menu.
			$query = $GLOBALS['sizzle']->database->prepare('DELETE FROM menus WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
            // Return.
			$_SESSION['redirect'] = '/backend/menus/view';
			return 'Menu deleted successfully.';
		}
		
		public function edit() {
			// Make sure all required info was submitted.
			$errors = array();
			foreach (array(
				'name'=>'Name',
				'menu'=>'Menu',
				'id'=>'Menu ID'
			) as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					$errors[] = 'Field <em>'. $v .'</em> was missing or invalid.';
				}
			}
			if (count($errors)) {
    			return '<strong>Error: </strong><br/>'. implode('<br/>', $errors);
			}
			// Make sure the menu exists.
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM menus WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
			if ($query->fetch() === false) {
				return '<strong>Error: </strong>Some required information was missing or invalid.';
			}
			// Update the existing menu.
			$query = $GLOBALS['sizzle']->database->prepare('UPDATE menus SET name = ?, menu = ? WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['name'], json_encode($_POST['menu']), $_POST['id']));
            // Return.
			return 'Menu edited successfully.';
		}
		
		public function fetch($id=false) {
			$return = parent::fetch($id);
			if ($return) {
    			$return['menu'] = json_decode($return['menu'], true);
			}
			return $return;
		}

	}

?>