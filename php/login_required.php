<?php

if(!isset($_SESSION["username"])){
	$redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	if(strrpos($_SERVER['HTTP_HOST'], "entelodonte") !== false || strrpos($_SERVER['HTTP_HOST'], "localhost") !== false) {
		$redirect = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
	$redirect = urlencode($redirect);
	header('Location: login.php?r='.$redirect);
}

?>