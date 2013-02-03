<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$userDA = new \model\access\UserAccess();

	if( !$_SESSION['active'] ){
		header('Location: ' . APPLICATION_ROOT_URL . 'index.php?code=2');
	}
	elseif( $_SESSION['roleid'] > 1 ){
		header('Location: ' . APPLICATION_ROOT_URL . 'index.php?code=2');
	}

	$data['pass'] = isset($_POST['pass']) ? $_POST['pass'] : null;
	$data['fname'] = isset($_POST['fname']) ? $_POST['fname'] : null;
	$data['lname'] = isset($_POST['lname']) ? $_POST['lname'] : null;
	$data['email'] = isset($_POST['email']) ? $_POST['email'] : null;
	$data['gender'] = isset($_POST['gender']) ? $_POST['gender'] : null;
	$data['phone'] = isset($_POST['phone']) ? $_POST['phone'] : null;

	$roleid = isset($_POST['rid']) ? $_POST['rid'] : 2;

	if( !$data['fname'] || !$data['lname'] || !$data['email'] || !$data['pass'] ){
		array_shift($data);
		header('Location: ' . APPLICATION_ROOT_URL . 'account.php?a=addnew&code=2&' . http_build_query($data));
	}
	else{
		$new = $userDA->add($data['fname'], $data['lname'], $data['email'], $data['pass'], $roleid);
		if( $new ){
			header('Location: ' . APPLICATION_ROOT_URL . 'users.php?code=1');
		}
		else{
			header('Location: ' . APPLICATION_ROOT_URL . 'account.php?a=addnew&code=3&' . http_build_query($data));
		}
	}
?>
