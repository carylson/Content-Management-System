<?php

	class NewsModel extends BaseAppletModel {
	
		public function add() {
			$is_content = !empty($_POST['type']) && $_POST['type'] == 'content' ? true : false ;
			// Construct required fields.
			$required_fields = array(
				'name'=>'Name',
				'title'=>'Title',
				'date'=>'Date'
			);
			if ($is_content) {
    			$required_fields['content'] = 'Content';
			} else {
    			$required_fields['url'] = 'URL';
			}
			// Make sure all required info was submitted.
			$errors = array();
			foreach ($required_fields as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					$errors[] = 'Field <em>'. $v .'</em> was missing or invalid.';
				}
			}
			if (count($errors)) {
    			return '<strong>Error: </strong><br/>'. implode('<br/>', $errors);
			}
			// Unset appropriate data
			if ($is_content) {
    			$_POST['url'] = '';
			} else {
    			$_POST['content'] = '';
			}
			// Add the new news.
			$query = $GLOBALS['sizzle']->database->prepare('INSERT INTO news (name, title, subtitle, date, url, content) VALUES (?, ?, ?, ?, ?, ?)');
			$query->execute(array($_POST['name'], $_POST['title'], $_POST['subtitle'], date('Y-m-d', strtotime($_POST['date'])), $_POST['url'], $_POST['content']));
            // Return.
			$_SESSION['redirect'] = '/backend/news/view';
			return 'News added successfully.';
		}
		
		public function delete() {
			// Make sure all required info was submitted.
			foreach (array(
				'id'=>'News ID'
			) as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					return '<strong>Error: </strong><em>'. $v .'</em> was missing or invalid.';
				}
			}
			// Make sure the new exists.
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM news WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
			if ($query->fetch() === false) {
				return '<strong>Error: </strong>Some required information was missing or invalid.';
			}
			// Delete the existing news.
			$query = $GLOBALS['sizzle']->database->prepare('DELETE FROM news WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
            // Return.
			$_SESSION['redirect'] = '/backend/news/view';
			return 'News deleted successfully.';
		}

		public function edit() {
			$is_content = !empty($_POST['type']) && $_POST['type'] == 'content' ? true : false ;
			// Construct required fields.
			$required_fields = array(
				'name'=>'Name',
				'title'=>'Title',
				'date'=>'Date',
				'id'=>'News ID'
			);
			if ($is_content) {
    			$required_fields['content'] = 'Content';
			} else {
    			$required_fields['url'] = 'URL';
			}
			// Make sure all required info was submitted.
			$errors = array();
			foreach ($required_fields as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					$errors[] = 'Field <em>'. $v .'</em> was missing or invalid.';
				}
			}
			if (count($errors)) {
    			return '<strong>Error: </strong><br/>'. implode('<br/>', $errors);
			}
			// Make sure the new exists.
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM news WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
			if ($query->fetch() === false) {
				return '<strong>Error: </strong>Some required information was missing or invalid.';
			}
			// Unset appropriate data
			if ($is_content) {
    			$_POST['url'] = '';
			} else {
    			$_POST['content'] = '';
			}
			// Update the existing news.
			$query = $GLOBALS['sizzle']->database->prepare('UPDATE news SET name = ?, title = ?, subtitle = ?, date = ?, url = ?, content = ? WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['name'], $_POST['title'], $_POST['subtitle'], date('Y-m-d', strtotime($_POST['date'])), $_POST['url'], $_POST['content'], $_POST['id']));
            // Return.
			return 'News edited successfully.';
		}

	}

?>