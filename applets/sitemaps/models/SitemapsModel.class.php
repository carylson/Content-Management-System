<?php

	class SitemapsModel extends BaseAppletModel {
	
		public function add() {
			// Make sure all required info was submitted.
			$errors = array();
			foreach (array(
				'name'=>'Name',
				'sitemap'=>'Sitemap'
			)  as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					$errors[] = 'Field <em>'. $v .'</em> was missing or invalid.';
				}
			}
			if (count($errors)) {
    			return '<strong>Error: </strong><br/>'. implode('<br/>', $errors);
			}
			// Add the new sitemap.
			$query = $GLOBALS['sizzle']->database->prepare('INSERT INTO sitemaps (name, content) VALUES (?, ?)');
			$query->execute(array($_POST['name'], json_encode($_POST['sitemap'])));
            // Return.
			$_SESSION['redirect'] = '/backend/sitemaps/view';
			return 'Sitemap added successfully.';
		}
		
		public function delete() {
			// Make sure all required info was submitted.
			foreach (array(
				'id'=>'Sitemap ID'
			) as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					return '<strong>Error: </strong><em>'. $v .'</em> was missing or invalid.';
				}
			}
			// Make sure the sitemap exists.
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM sitemaps WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
			if ($query->fetch() === false) {
				return '<strong>Error: </strong>Some required information was missing or invalid.';
			}
			// Delete the existing sitemap.
			$query = $GLOBALS['sizzle']->database->prepare('DELETE FROM sitemaps WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
            // Return.
			$_SESSION['redirect'] = '/backend/sitemaps/view';
			return 'Sitemap deleted successfully.';
		}

		public function edit() {
			// Make sure all required info was submitted.
			$errors = array();
			foreach (array(
				'name'=>'Name',
				'sitemap'=>'Sitemap',
				'id'=>'Sitemap ID'
			)  as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					$errors[] = 'Field <em>'. $v .'</em> was missing or invalid.';
				}
			}
			if (count($errors)) {
    			return '<strong>Error: </strong><br/>'. implode('<br/>', $errors);
			}
			// Make sure the sitemap exists.
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM sitemaps WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
			if ($query->fetch() === false) {
				return '<strong>Error: </strong>Some required information was missing or invalid.';
			}
			// Update the existing sitemap.
			$query = $GLOBALS['sizzle']->database->prepare('UPDATE sitemaps SET name = ?, content = ? WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['name'], json_encode($_POST['sitemap']), $_POST['id']));
            // Return.
			return 'Sitemap edited successfully.';
		}
		
		public function fetch($id=false) {
			$return = parent::fetch($id);
			if ($return) {
    			$return['content'] = json_decode($return['content'], true);
			}
			return $return;
		}

	}

?>