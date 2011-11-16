<?php

	class BlogModel extends BaseAppletModel {
	
		public function add() {
			// Make sure all required info was submitted.
			$errors = array();
			foreach (array(
				'name'=>'Name',
				'title'=>'Title',
				'content'=>'Content'
			)  as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					$errors[] = 'Field <em>'. $v .'</em> was missing or invalid.';
				}
			}
			if (count($errors)) {
    			return '<strong>Error: </strong><br/>'. implode('<br/>', $errors);
			}
			// Add the new blog.
			$query = $GLOBALS['sizzle']->database->prepare('INSERT INTO blog (name, title, content, timestamp) VALUES (?, ?, ?, ?)');
			$query->execute(array($_POST['name'], $_POST['title'], $_POST['content'], date('Y-m-d H:i:s')));
            // Return.
			$_SESSION['redirect'] = '/backend/blog/view';
			return 'Blog added successfully.';
		}
		
		public function delete() {
			// Make sure all required info was submitted.
			foreach (array(
				'id'=>'Blog ID'
			) as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					return '<strong>Error: </strong><em>'. $v .'</em> was missing or invalid.';
				}
			}
			// Make sure the new exists.
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM blog WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
			if ($query->fetch() === false) {
				return '<strong>Error: </strong>Some required information was missing or invalid.';
			}
			// Delete the existing blog.
			$query = $GLOBALS['sizzle']->database->prepare('DELETE FROM blog WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
            // Return.
			$_SESSION['redirect'] = '/backend/blog/view';
			return 'Blog deleted successfully.';
		}

		public function edit() {
			// Make sure all required info was submitted.
			$errors = array();
			foreach (array(
				'name'=>'Name',
				'title'=>'Title',
				'content'=>'Content',
				'id'=>'Blog ID'
			)  as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					$errors[] = 'Field <em>'. $v .'</em> was missing or invalid.';
				}
			}
			if (count($errors)) {
    			return '<strong>Error: </strong><br/>'. implode('<br/>', $errors);
			}
			// Make sure the new exists.
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM blog WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
			if ($query->fetch() === false) {
				return '<strong>Error: </strong>Some required information was missing or invalid.';
			}
			// Update the existing blog.
			$query = $GLOBALS['sizzle']->database->prepare('UPDATE blog SET name = ?, title = ?, content = ?, timestamp = ? WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['name'], $_POST['title'], $_POST['content'], date('Y-m-d H:i:s'), $_POST['id']));
            // Return.
			return 'Blog edited successfully.';
		}

		public function comment($id=false) {
			$database = $GLOBALS['sizzle']->database;
			// Get an instance of the blog we're working with.
			$query = $database->prepare('SELECT * FROM blog WHERE id = ? LIMIT 1');
			$query->execute(array($id));
			$blog = $query->fetch();
			// Make sure blog is valid.
			if ($blog === false) {
				return '<strong>Error: </strong>Blog is missing or invalid.';
			}
			// Make sure all required info was submitted.
			foreach (array(
    			'name' => 'Your name',
    			'comment' => 'Comments'
			) as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					return '<strong>Error: </strong><em>'. $v .'</em> was missing or invalid.';
				}
			}
			$blog['comments'] = json_decode($blog['comments'], true);
			if (!is_array($blog['comments'])) {
    			$blog['comments'] = array();
			}
			array_unshift($blog['comments'], array(
    			'name' => $_POST['name'],
    			'email' => !empty($_POST['email']) ? $_POST['email'] : '',
    			'comment' => $_POST['comment']
			));
			$query = $database->prepare('UPDATE blog SET comments = ? WHERE id = ? LIMIT 1');
			$query->execute(array(json_encode($blog['comments']), $id));
            // Return.
            return 'Form submitted successfully.';
		}
		
		public function fetch($id=false) {
			$return = parent::fetch($id);
			if ($return) {
    			$return['comments'] = json_decode($return['comments'], true);
			}
			return $return;
		}

	}

?>