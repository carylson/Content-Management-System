<?php

	class ContentModel extends BaseAppletModel {
	
		public function add() {
			$is_page = !empty($_POST['page']) && $_POST['page'] ? true : false ;
			// Construct required fields.
			$required_fields = array(
				'name'=>'Name',
				'content'=>'Content'
			);
			if ($is_page) {
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
			// Make sure URL is unique.
			if ($is_page) {
				$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM content WHERE url = ? LIMIT 1');
				$query->execute(array($_POST['url']));
				if ($query->fetch() !== false) {
					return '<strong>Error: </strong>Content URL must be unique.';
				}
			}
			// Unset appropriate data
			if (!$is_page) {
    			$_POST['url'] = '';
    			$_POST['title'] = '';
    			$_POST['meta_keywords'] = '';
    			$_POST['meta_description'] = '';
			}
			// Add the new content.
			$query = $GLOBALS['sizzle']->database->prepare('INSERT INTO content (name, url, title, meta_keywords, meta_description, content) VALUES (?, ?, ?, ?, ?, ?)');
			$query->execute(array($_POST['name'], $_POST['url'], $_POST['title'], $_POST['meta_keywords'], $_POST['meta_description'], $_POST['content']));
            // Return.
			$_SESSION['redirect'] = '/backend/content/view';
			if ($is_page) {
    			return 'Content added successfully.<br/><a href="http://'. $_SERVER['SERVER_NAME'] .'/'. $_POST['url'] .'" target="_blank">Open page &raquo;</a>';
			} else {
    			return 'Content added successfully.';
			}
		}
		
		public function delete() {
			// Make sure all required info was submitted.
			foreach (array(
				'id'=>'Content ID'
			) as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					return '<strong>Error: </strong><em>'. $v .'</em> was missing or invalid.';
				}
			}
			// Make sure the content exists.
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM content WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
			if ($query->fetch() === false) {
				return '<strong>Error: </strong>Some required information was missing or invalid.';
			}
			// Delete the existing content.
			$query = $GLOBALS['sizzle']->database->prepare('DELETE FROM content WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
            // Return.
			$_SESSION['redirect'] = '/backend/content/view';
			return 'Content deleted successfully.';
		}

		public function edit() {
			$is_page = !empty($_POST['page']) && $_POST['page'] ? true : false ;
			// Construct required fields.
			$required_fields = array(
				'name'=>'Name',
				'content'=>'Content',
				'id'=>'Content ID'
			);
			if ($is_page) {
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
			// Make sure the content exists.
			$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM content WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['id']));			
			if ($query->fetch() === false) {
				return '<strong>Error: </strong>Some required information was missing or invalid.';
			}
			// Make sure URL is unique.
			if ($is_page) {
				$query = $GLOBALS['sizzle']->database->prepare('SELECT * FROM content WHERE url = ? AND id != ? LIMIT 1');
				$query->execute(array($_POST['url'], $_POST['id']));
				if ($query->fetch() !== false) {
					return '<strong>Error: </strong>Content URL must be unique.';
				}
			}
			// Unset appropriate data
			if (!$is_page) {
    			$_POST['url'] = '';
    			$_POST['title'] = '';
    			$_POST['meta_keywords'] = '';
    			$_POST['meta_description'] = '';
			}
			// Update the existing content.
			$query = $GLOBALS['sizzle']->database->prepare('UPDATE content SET name = ?, url = ?, title = ?, meta_keywords = ?, meta_description = ?, content = ? WHERE id = ? LIMIT 1');
			$query->execute(array($_POST['name'], $_POST['url'], $_POST['title'], $_POST['meta_keywords'], $_POST['meta_description'], $_POST['content'], $_POST['id']));
            // Return.
			if ($is_page) {
    			return 'Content edited successfully.<br/><a href="http://'. $_SERVER['SERVER_NAME'] .'/'. $_POST['url'] .'" target="_blank">Open page &raquo;</a>';
			} else {
    			return 'Content edited successfully.';
			}
		}
		
		public function fetchContent() {
    		$query = 'SELECT * FROM content WHERE url = ""';
    		$params = array();
    		$options = $this->fetchOptions();
    		if ($options !== false && !empty($options['homepage'])) {
        		$query .= ' AND id != ?';
        		$params[] = $options['homepage'];
    		}
			$query = $GLOBALS['sizzle']->database->prepare($query);
			$query->execute($params);
			return $query->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public function fetchPages() {
    		$query = 'SELECT * FROM content WHERE url != ""';
    		$params = array();
    		$options = $this->fetchOptions();
    		if ($options !== false && !empty($options['homepage'])) {
        		$query .= ' OR id = ?';
        		$params[] = $options['homepage'];
    		}
			$query = $GLOBALS['sizzle']->database->prepare($query);
			$query->execute($params);
			return $query->fetchAll(PDO::FETCH_ASSOC);
		}

		public function options() {
			// Make sure all required info was submitted.
			foreach (array(
				'homepage'=>'Homepage',
				'errorpage'=>'Errorpage'
			) as $k=>$v) {
				if (!isset($_POST[$k]) || $_POST[$k] == '') {
					return '<strong>Error: </strong><em>'. $v .'</em> was missing or invalid.';
				}
			}
			// Update the existing options.
			$query = $GLOBALS['sizzle']->database->prepare('UPDATE content_options SET homepage = ?, errorpage = ?, title = ?, meta_keywords = ?, meta_description = ? LIMIT 1');
			$query->execute(array($_POST['homepage'], $_POST['errorpage'], $_POST['title'], $_POST['meta_keywords'], $_POST['meta_description']));
            // Return.
			return 'Content options edited successfully.';
		}
		
	}

?>