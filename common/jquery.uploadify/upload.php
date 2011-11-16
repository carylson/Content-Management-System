<?php

	if (!empty($_FILES)) {
		
		$file_info = pathinfo($_FILES['Filedata']['name']);

		$base_path = dirname(dirname(dirname(__FILE__)));

		$target_path = $base_path .'/' . $_POST['folder'];
		$filename = md5(uniqid()) . '.' . $file_info['extension'];
		$target_file = str_replace('//', '/', $target_path . '/' . $filename);

		while (file_exists($target_file)) {
			$filename = md5(uniqid()) . '.' . $file_info['extension'];
			$target_file = str_replace('//', '/', $target_path . '/' . $filename);
		}

		if (move_uploaded_file($_FILES['Filedata']['tmp_name'], $target_file)) {
			echo $filename;
		} else {
			echo 'error: '. $target_file;
		}
		
	} else {
		echo 'error';
	}

?>