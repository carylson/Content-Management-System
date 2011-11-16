<?php

	class FormsApplet extends BaseApplet {
	
		public function add() {
			// Authenticate w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authenticate()) {
    			$GLOBALS['sizzle']->utils->redirect('/backend/login', '<strong>Error:</strong> Please login to continue.');
			}
			// Authorize w/ backend app.
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('forms', 'add')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['forms']->add();
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
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('forms', 'delete')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['forms']->delete();
			} else {
    			// Load view.
    			$view = '/views/delete.tpl';
    			if ($id === false 
    			|| $this->models['forms']->fetch($id) === false) {
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
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('forms', 'edit')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    			// Handle form submit.
				return $this->models['forms']->edit();
			} else {
    			// Load view.
    			$view = '/views/edit.tpl';
    			if ($id === false 
    			|| $this->models['forms']->fetch($id) === false) {
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
			if (!$GLOBALS['sizzle']->apps['backend']->models['backend']->authorize('forms', 'view')) {
    			$GLOBALS['sizzle']->utils->redirect('/backend', '<strong>Error:</strong> Insufficient user access.');
			}
			// Load view.
			return $this->_loadView(dirname(__FILE__) .'/views/view.tpl');
		}

		public function output($id=false) {
			$database = $GLOBALS['sizzle']->database;
			if (is_numeric($id)) {
				$query = $database->prepare('SELECT * FROM forms WHERE id = ? LIMIT 1');
				$query->execute(array($id));
			} else {
				$query = $database->prepare('SELECT * FROM forms LIMIT 1');
				$query->execute();
			}
			$form = $query->fetch();
			$output = '';
			if (!empty($form)) {
                $output .= '
                <form action="/frontend/process/applets/forms/process/'. $form['id'] .'" method="post" style="background:#eee; padding:20px; margin:20px 100px;">
                <h3>'. $form['name'] .'</h3>
                <p><em>Fields marked with an asterisk (<span style="color:red;">*</span>) are required.</em></p>
                ';
                $fields = json_decode($form['form'], true);
                for ($i=0; $i<count($fields); $i++) {
                    $name = 'form'. $form['id'] .'['. $i .']';
                    if (!empty($fields[$i]['required'])) {
                        $fields[$i]['label'] = '<span style="color:red;">* </span>'. $fields[$i]['label'];
                    }
                    $output .= '<p><div><strong>'. $fields[$i]['label'] .'</strong></div>';
                    switch ($fields[$i]['type']) {
                        case 'password':
                            $output .= '<input type="password" name="'. $name .'"';
                            if (!empty($fields[$i]['size'])) {
                                switch ($fields[$i]['size']) {
                                    case 'small':
                                        $output .= 'size="10"';
                                        break;
                                    case 'large':
                                        $output .= 'size="30"';
                                        break;
                                    default:
                                        $output .= 'size="20"';
                                        break;
                                }
                            }
                            if (!empty($fields[$i]['defaultvalue'])) {
                                $output .= ' value="'. $fields[$i]['defaultvalue'] .'"';
                            }
                            if (!empty($fields[$i]['maxlength'])) {
                                $output .= ' maxlength="'. $fields[$i]['maxlength'] .'"';
                            }
                            $output .= '/>';
                            break;
                        case 'textarea':
                            $output .= '<textarea name="'. $name .'"';
                            if (!empty($fields[$i]['size'])) {
                                switch ($fields[$i]['size']) {
                                    case 'small':
                                        $output .= 'rows="3" cols="50"';
                                        break;
                                    case 'large':
                                        $output .= 'rows="23" cols="50"';
                                        break;
                                    default:
                                        $output .= 'rows="13" cols="50"';
                                        break;
                                }
                            }
                            $output .= '>';
                            if (!empty($fields[$i]['defaultvalue'])) { $output .= $fields[$i]['defaultvalue']; }
                            $output .= '</textarea>';
                            break;
                        case 'select':
                            $output .= '<select name="'. $name .'">';
                            foreach ($fields[$i]['options'] as $option) {
                                $output .= '<option value="'. $option .'">'. $option .'</option>';
                            }
                            $output .= '</select>';
                            break;
                        case 'radios':
                            foreach ($fields[$i]['options'] as $option) {
                                $output .= '<label><input type="radio" name="'. $name .'" value="'. $option .'"/> '. $option .'</label>';
                            }
                            break;
                        case 'checkboxes':
                            for ($j=0; $j<count($fields[$i]['options']); $j++) {
                                $option = $fields[$i]['options'][$j];
                                $output .= '<label><input type="checkbox" name="'. $name .'['. $j .']" value="'. $option .'"/> '. $option .'</label>';
                            }
                            break;
                        case 'file':
                            $output .= '
                            <div id="file_placeholder"></div>
                            <script type="text/javascript"> 
                            $(document).ready(function(){ 
                            	load_upload();
                            });
                            function load_upload() {
                            	$(\'#file_placeholder\').html(\'<input class="uploadify" id="fileInput" name="fileInput" type="file" />\');
                            	$(\'.uploadify\').uploadify({
                            		\'uploader\': \'/common/jquery.uploadify/uploadify.swf\',
                            		\'script\': \'/common/jquery.uploadify/upload.php\',
                            		\'cancelImg\': \'/common/jquery.uploadify/cancel.png\',
                            		\'auto\': true,
                            		\'folder\': \'/uploads\',
                            		\'buttonText\': \'SELECT FILE\',
                            		\'onOpen\': function(){
                            			$(\':submit\').attr(\'disabled\', \'disabled\');
                            		},
                            		\'onComplete\': function(e,q,f,r,d){
                            			if (r != \'error\') {
                            				var html = \'\
                            				File uploaded successfully! <a href="/uploads/\'+r+\'" target="_blank">Download</a> | \
                            				<a href="#" onclick="load_upload(); return false;">Reset</a>\
                            				<input type="hidden" name="'. $name .'" value="http://'. $_SERVER['SERVER_NAME'] .'/uploads/\'+r+\'"/>\';
                            			} else {
                            				var html = \'Error! <a href="#" onclick="load_upload(); return false;">Reset</a>\';
                            			}
                            			$(\'#file_placeholder\').html(html);
                            			$(\':submit\').removeAttr(\'disabled\');
                            		}
                            	});
                            }
                            </script>
                            ';
                            break;
                        default:
                            $output .= '<input type="text" name="'. $name .'"';
                            if (!empty($fields[$i]['size'])) {
                                switch ($fields[$i]['size']) {
                                    case 'small':
                                        $output .= 'size="10"';
                                        break;
                                    case 'large':
                                        $output .= 'size="30"';
                                        break;
                                    default:
                                        $output .= 'size="20"';
                                        break;
                                }
                            }
                            if (!empty($fields[$i]['defaultvalue'])) {
                                $output .= ' value="'. $fields[$i]['defaultvalue'] .'"';
                            }
                            if (!empty($fields[$i]['maxlength'])) {
                                $output .= ' maxlength="'. $fields[$i]['maxlength'] .'"';
                            }
                            $output .= '/>';
                            break;
                    }
                    if (!empty($fields[$i]['instruction'])) {
                        $output .= '<div><em><small>'. $fields[$i]['instruction'] .'</small></em></div>';
                    }
                    $output .= '</p>';
                }
                if ($form['captcha']) {
                    require_once(dirname(dirname(dirname(__FILE__))).'/common/recaptcha/recaptchalib.php');
                    $output .= '
                    <script type="text/javascript">
                    var RecaptchaOptions = { theme : \'custom\', custom_theme_widget: \'recaptcha_widget\' };
                    </script>
                    <div id="recaptcha_widget" style="display:none">
                        <div id="recaptcha_image" style="margin-bottom:5px;"></div>
                        <div class="recaptcha_only_if_incorrect_sol" style="color:red">Incorrect please try again</div>
                        <span class="recaptcha_only_if_image">Enter the words above:</span>
                        <span class="recaptcha_only_if_audio">Enter the words you hear:</span>
                        <input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />
                        <div><a href="javascript:Recaptcha.reload()">Get another CAPTCHA</a></div>
                        <div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type(\'audio\')">Get an audio CAPTCHA</a></div>
                        <div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type(\'image\')">Get an image CAPTCHA</a></div>
                        <!--<div><a href="javascript:Recaptcha.showhelp()">Help</a></div>-->
                    </div>
                    <script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k=6Lf-N8cSAAAAAArjZQLL9zasPCDDhmcJYF8sGuBT"></script>
                    <noscript>
                        <iframe src="http://www.google.com/recaptcha/api/noscript?k=6Lf-N8cSAAAAAArjZQLL9zasPCDDhmcJYF8sGuBT" height="300" width="500" frameborder="0"></iframe><br>
                        <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
                        <input type="hidden" name="recaptcha_response_field" value="manual_challenge">
                    </noscript>
                    ';
                }
                $output .= '
                <p><button type="submit">Submit</button></p>
                </form>
                ';
    			if (isset($_SESSION['sizzle_user'])) {
                    $output = '
        			<div class="sizzle-content">
            			'. $output .'
            			<a class="sizzle-content-edit" href="/backend/forms/edit/'. $form['id'] .'"></a>
                    </div>
        			';
    			}
			}
			return $output;
		}
		
		public function process($id=false) {
			return $this->models['forms']->process($id);
        }		

	}

?>