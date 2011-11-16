<?php

	class GalleriesModel extends BaseAppletModel {
	
		public function add() {
			// Make sure all required info was submitted.
			$errors = array();
			foreach (array(
				'name'=>'Name',
				'images'=>'Images'
			)  as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					$errors[] = 'Field <em>'. $v .'</em> was missing or invalid.';
				}
			}
			if (count($errors)) {
    			return '<strong>Error: </strong><br/>'. implode('<br/>', $errors);
			}
			// Add the new gallery.
			$query = $GLOBALS['sizzle']->database->prepare('INSERT INTO galleries (name, images) VALUES (?, ?)');
			$query->execute(array($_POST['name'], json_encode(array_values($_POST['images']))));
            // Return        
			$_SESSION['redirect'] = '/backend/galleries/view';
			return 'Gallery added successfully.';
		}
		
		public function delete() {
			// Make sure all required info was submitted.
			foreach (array(
				'id'=>'Gallery ID'
			) as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					return '<strong>Error: </strong><em>'. $v .'</em> was missing or invalid.';
				}
			}
			// Make sure the gallery exists.
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM galleries WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
			if ($query->fetch() === false) {
				return '<strong>Error: </strong>Some required information was missing or invalid.';
			}
			// Delete the existing gallery.
			$query = $GLOBALS['sizzle']->database->prepare('DELETE FROM galleries WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
            // Return        
			$_SESSION['redirect'] = '/backend/galleries/view';
			return 'Gallery deleted successfully.';
		}

		public function edit() {
			// Make sure all required info was submitted.
			$errors = array();
			foreach (array(
				'name'=>'Name',
				'images'=>'Images',
				'id'=>'Gallery ID'
			)  as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					$errors[] = 'Field <em>'. $v .'</em> was missing or invalid.';
				}
			}
			if (count($errors)) {
    			return '<strong>Error: </strong><br/>'. implode('<br/>', $errors);
			}
			// Make sure the gallery exists.
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM galleries WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
			if ($query->fetch() === false) {
				return '<strong>Error: </strong>Some required information was missing or invalid.';
			}
			// Update the existing gallery.
			$query = $GLOBALS['sizzle']->database->prepare('UPDATE galleries SET name = ?, images = ? WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['name'], json_encode(array_values($_POST['images'])), $_POST['id']));
            // Return        
			return 'Gallery edited successfully.';
		}

		public function fetch($id=false) {
			$return = parent::fetch($id);
			if ($return) {
    			$return['images'] = json_decode($return['images'], true);
			}
			return $return;
		}

	}

?>