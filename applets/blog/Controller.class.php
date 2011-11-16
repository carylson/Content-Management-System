<?php

	class BlogApplet extends BaseApplet {
	
		public function add() {
			// Authenticate w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			// Authorize w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('blog', 'add')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['blog']->add();
			} else {
    			// Load view.
    			return $this->_loadView(dirname(__FILE__) .'/views/add.tpl');
			}
		}
		
		public function delete($id=false) {
			// Authenticate w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			// Authorize w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('blog', 'delete')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['blog']->delete();
			} else {
    			// Load view.
    			$view = '/views/delete.tpl';
    			if ($id === false 
    			|| $this->models['blog']->fetch($id) === false) {
    				$view = '/views/select.tpl';
    			}
    			return $this->_loadView(dirname(__FILE__) . $view);
            }
		}
		
		public function edit($id=false) {
			// Authenticate w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			// Authorize w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('blog', 'edit')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['blog']->edit();
			} else {
    			// Load view.
    			$view = '/views/edit.tpl';
    			if ($id === false 
    			|| $this->models['blog']->fetch($id) === false) {
    				$view = '/views/select.tpl';
    			}
    			return $this->_loadView(dirname(__FILE__) . $view);
            }
		}
		
		public function view() {
			// Authenticate w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			// Authorize w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('blog', 'view')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			// Load view.
			return $this->_loadView(dirname(__FILE__) .'/views/view.tpl');
		}

		public function output($article_limit=false) {
			$database = $GLOBALS['sizzle']->database;
			$output = '';
            if (empty($_GET['id']) 
            || !is_numeric($_GET['id'])) {
				$article_limit = is_numeric($article_limit) ? $article_limit : 10 ;
				$query = $database->prepare('SELECT * FROM blog ORDER BY timestamp DESC LIMIT ?');
				$query->bindParam(1, $article_limit, PDO::PARAM_INT);
				$query->execute();
				$blog = $query->fetchAll();
				if (count($blog) > 0) {
    				foreach ($blog as $item) {
                        $item['comments'] = json_decode($item['comments'], true);
                        $output .= '
                        <h2><a href="?id='. $item['id'] .'">'. $item['title'] .'</a></h2>
                        <p>Posted: '. date('F j, Y, g:i a', strtotime($item['timestamp'])) .' | <a href="?id='. $item['id'] .'">Comments ('. count($item['comments']) .')</a></p>
                        ';
                        if (strlen($item['content']) > 1000) {
                            $item['content'] = substr($item['content'], 0, 997).'...';
                        }
                        $output .= $item['content']. '
                        <hr style="height:0; border:none; border-bottom:1px dotted #ccc;"/>
                        ';
    				}
				} else {
    				$output = '<p>There are no blog articles in the database.</p>';
				}
            } else {
				$query = $database->prepare('SELECT * FROM blog WHERE id = ? LIMIT 1');
				$query->execute(array($_GET['id']));
				$blog = $query->fetch();
				if ($blog !== false) {
    				$output = '
    				<h2>'. $blog['title'] .'</h2>
                    <p>Posted: '. date('F j, Y, g:i a', strtotime($blog['timestamp'])) .'</p>
    				'. $blog['content'] .'
                    <p>&nbsp;</p>
                    <hr style="height:0; border:none; border-bottom:1px dotted #ccc;"/>
                    <h2>Comments</h2>
                    ';
                    $comments = json_decode($blog['comments'], true);
                    if (count($comments)) {
                        foreach ($comments as $comment) {
                            if (!empty($comment['email'])) {
                                $comment['name'] = '<a href="mailto:'. $comment['email'] .'">'. $comment['name'] .'</a>';
                            }
                            $output .= '
                            <h3>'. $comment['name'] .' says:</h3>
                            <p>'. $comment['comment'] .'</p>
                            ';
                        }
                    } else {
                        $output .= '<p>No comments.</p>';
                    }
                    $output .= '
                    <p>&nbsp;</p>
                    <hr style="height:0; border:none; border-bottom:1px dotted #ccc;"/>
                    <h2>Post a Comment</h2>
                    <form action="/process/applets/blog/comment/'. $blog['id'] .'" method="post">
                        <p>
                            Your name:<br/>
                            <input type="text" name="name"/>
                        </p>
                        <p>
                            Your email:<br/>
                            <input type="text" name="email"/>
                        </p>
                        <p>
                            Comments:<br/>
                            <textarea name="comment" rows="5" style="width:80%;"></textarea>
                        </p>
                        <p>
                            <input type="submit" value="Submit"/>
                        </p>
                    </form>
                    <p>&nbsp;</p>
                    <hr style="height:0; border:none; border-bottom:1px dotted #ccc;"/>
    				<p><a href="/'. implode('/', $GLOBALS['sizzle']->request) .'">&laquo; Return to blog article index</a></p>
    				';
				} else {
    				$output = '
    				<h2>Unknown/Invalid Blog Article</h2>
    				<h3>Error: Requested article does not exist.</h3>
    				<p>The requested blog article is either unavailable, does not exist, or has been removed.</p>
    				<p><a href="/'. implode('/', $GLOBALS['sizzle']->request) .'">Click here to return to the index of blog articles.</a></p>
    				';
				}
            }
            return $output;
		}

		public function outputFeed($article_limit=false) {
			$database = $GLOBALS['sizzle']->database;
			$output = '';
			$article_limit = is_numeric($article_limit) ? $article_limit : 10 ;
			$query = $database->prepare('SELECT * FROM blog ORDER BY timestamp DESC LIMIT ?');
			$query->bindParam(1, $article_limit, PDO::PARAM_INT);
			$query->execute();
			$output .= '<ul>';
			foreach ($query->fetchAll() as $item) {
                $output .= '
                <li>
                    <a href="/blog?id='. $item['id'] .'">'.$item['title'].'</a><br/>
                    '. date('m/d/y h:i a', strtotime($item['timestamp'])) .'
                </li>
                ';
			}
			$output .= '</ul>';
			return $output;
		}

		public function outputXml($id=false) {
			$database = $GLOBALS['sizzle']->database;
    		$output = ltrim('
    		<?xml version="1.0" encoding="utf-8"?>
			<rss version="2.0">
			<channel>
			<title>Blog RSS Feed</title>
			<link>http://' . $_SERVER['SERVER_NAME'] . '/</link>
			<description></description>
			<language>en-us</language>
    		');
			$query = $database->prepare('SELECT * FROM blog ORDER BY timestamp DESC LIMIT 100');
			$query->execute();
			foreach ($query->fetchAll() as $blog) {
                if (strlen($blog['content']) > 1000) {
                    $blog['content'] = substr($blog['content'], 0, 997).'...';
                }
    			$output .= '
    			<item>
    				<title>' . $blog['title'] . '</title>
    				<link>http://' . $_SERVER['SERVER_NAME'] . '/blog?id=' . $blog['id'] . '</link>
    				<guid>http://' . $_SERVER['SERVER_NAME'] . '/blog?id=' . $blog['id'] . '</guid>
    				<pubDate>'. date(DATE_RSS, strtotime($blog['timestamp'])) .'</pubDate>
    				<description>' . strip_tags($blog['content']) . '</description>
    			</item>
    			';
			}
    		$output .= rtrim('
			</channel>
    		</rss>
    		');
    		header('Content-Type: text/xml; charset: utf-8');
			return $output;
		}
		
		public function comment($id=false) {
			return $this->models['blog']->comment($id);
        }		

	}

?>