<?php

	class ApiApp extends BaseApp {
	
        public function index($root=false, $controller=false, $method=false) {
            $response = array(
                'error' => true,
                'message' => 'Invalid request.'
            );
            if (in_array($root, array('apps', 'applets'))) {
                $subject = $GLOBALS['sizzle']->$root;
                if (isset($subject[$controller])) {
					if (method_exists($subject[$controller], $method)) {
						$chunks = explode($root.'/'.$controller.'/'.$method.'/', implode('/', $GLOBALS['sizzle']->request));
						$params = count($chunks) > 1 ? array_pop($chunks) : array() ;
						$response = array(
							'error' => false,
							'message' => call_user_func_array(
								array($subject[$controller], $method), 
								$params
							)
						);
					} else {
						$response['message'] = 'Invalid method "'. $method .'".';
					}
                } else {
                    $response['message'] = 'Invalid controller "'. $controller .'".';
                }
            } else {
                $response['message'] = 'Invalid root "'. $root .'".';
            }
            echo json_encode($response);
            return;
        }
	
	}

?>