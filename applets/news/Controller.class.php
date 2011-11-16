<?php

	class NewsApplet extends BaseApplet {
	
		public function add() {
			// Authenticate w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			// Authorize w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('news', 'add')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['news']->add();
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
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('news', 'delete')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['news']->delete();
			} else {
    			// Load view.
    			$view = '/views/delete.tpl';
    			if ($id === false 
    			|| $this->models['news']->fetch($id) === false) {
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
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('news', 'edit')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['news']->edit();
			} else {
    			// Load view.
    			$view = '/views/edit.tpl';
    			if ($id === false 
    			|| $this->models['news']->fetch($id) === false) {
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
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('news', 'view')) {
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
				$query = $database->prepare('SELECT * FROM news ORDER BY date DESC LIMIT ?');
				$query->bindParam(1, $article_limit, PDO::PARAM_INT);
				$query->execute();
				$news = $query->fetchAll();
				if (count($news) > 0) {
    				foreach ($news as $item) {
                        $output .= '<h2>'. $item['title'] .'</h2>';
                        if (!empty($item['subtitle'])) {
                            $output .= '<h3>'. $item['subtitle'] .'</h3>';
                        }
                        if (!empty($item['url'])) {
                            $output .= $item['url'];
                        } else {
                            if (strlen($item['content']) > 1000) {
                                $item['content'] = substr($item['content'], 0, 997).'...';
                            }
                            $output .= $item['content'];
                        }
                        $output .= '
                        <p><a href="?id='. $item['id'] .'">View article &raquo;</a></p>
                        <hr style="height:0; border:none; border-bottom:1px dotted #ccc;"/>
                        ';
    				}
				} else {
    				$output = '<p>There is no news in the database.</p>';
				}
            } else {
				$query = $database->prepare('SELECT * FROM news WHERE id = ? LIMIT 1');
				$query->execute(array($_GET['id']));
				$news = $query->fetch();
				if ($news !== false) {
    				if (!empty($news['url'])) {
        				$output = '
        				<h2>'. $news['title'] .'</h2>
        				<h3>'. $news['subtitle'] .'</h3>
        				<p>Redirecting you to the article, just a moment…</p>
        				<p><small>('. $news['url'] .')</small></p>
        				<meta http-equiv="refresh" content="3;url='. $news['url'] .'"/>
        				';
    				} else {
        				$output = '
        				<h2>'. $news['title'] .'</h2>
        				<h3>'. $news['subtitle'] .'</h3>
        				'. $news['content'] .'
                        <p>&nbsp;</p>
                        <hr style="height:0; border:none; border-bottom:1px dotted #ccc;"/>
        				<p><a href="/'. implode('/', $GLOBALS['sizzle']->request) .'">&laquo; Return to news article index</a></p>
        				';
    				}
				} else {
    				$output = '
    				<h2>Unknown/Invalid News Article</h2>
    				<h3>Error: Requested article does not exist.</h3>
    				<p>The requested news article is either unavailable, does not exist, or has been removed.</p>
    				<p><a href="/'. implode('/', $GLOBALS['sizzle']->request) .'">Click here to return to the index of news articles.</a></p>
    				';
				}
            }
            return $output;
		}

		public function outputFeed($article_limit=false) {
			$database = $GLOBALS['sizzle']->database;
			$output = '';
			$article_limit = is_numeric($article_limit) ? $article_limit : 10 ;
			$query = $database->prepare('SELECT * FROM news ORDER BY date DESC LIMIT ?');
			$query->bindParam(1, $article_limit, PDO::PARAM_INT);
			$query->execute();
			$output .= '<ul>';
			foreach ($query->fetchAll() as $item) {
                if (!empty($item['url'])) {
                    $output .= '<li><a href="'. $item['url'] .'">'.$item['title'].'</a></li>';
                } else {
                    $output .= '<li><a href="/news?id='. $item['id'] .'">'.$item['title'].'</a></li>';
                }
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
			<title>News RSS Feed</title>
			<link>http://' . $_SERVER['SERVER_NAME'] . '/</link>
			<description></description>
			<language>en-us</language>
    		');
			$query = $database->prepare('SELECT * FROM news ORDER BY date DESC LIMIT 100');
			$query->execute();
			foreach ($query->fetchAll() as $news) {
                if (strlen($news['content']) > 1000) {
                    $news['content'] = substr($news['content'], 0, 997).'...';
                }
    			$output .= '
    			<item>
    				<title>' . $news['title'] . '</title>
    				<link>http://' . $_SERVER['SERVER_NAME'] . '/news?id=' . $news['id'] . '</link>
    				<guid>http://' . $_SERVER['SERVER_NAME'] . '/news?id=' . $news['id'] . '</guid>
    				<pubDate>'. date(DATE_RSS, strtotime($news['date'])) .'</pubDate>
    				<description>' . strip_tags($news['content']) . '</description>
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
	
	}

?>