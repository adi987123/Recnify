<!-- temporary image storing -->
<?php
	ob_start();
	session_start();
	$_SESSION['date'] = date("dd_mm_h_i_ss");
	move_uploaded_file($_FILES['webcam']['tmp_name'], '../temp/webcam'.$_SESSION['date'].'.jpg');
	// Storing image-content in Session , used while Login or Signup
	$_SESSION['temp_image'] = file_get_contents('../temp/webcam'.$_SESSION['date'].'.jpg');
	unlink('../temp/webcam'.$_SESSION['date'].'.jpg');
	$_SESSION['date'] = null;
?>